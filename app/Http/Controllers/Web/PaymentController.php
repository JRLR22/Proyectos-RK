<?php

namespace App\Http\Controllers\Web;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; 
use Stripe\Stripe;                  
use Stripe\PaymentIntent;           

class PaymentController extends Controller
{
    public function __construct()
    {
        // Configurar Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ==================== CHECKOUT ====================
    
    public function showCheckout()
    {
        $cart = $this->getOrCreateCart();
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        $cartData = $this->formatCartResponse($cart);
        
        // Crear orden pendiente
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => Order::generateOrderNumber(),
            'status' => 'pendiente',
            'payment_status' => 'pendiente',
            'subtotal' => $cart->subtotal,
            'discount_amount' => $cart->discount_amount,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total' => $cart->total,
        ]);

        // Crear items de la orden
        foreach ($cart->items as $item) {
            $order->items()->create([
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->price_at_addition,
            ]);
        }

        return view('checkout', [
            'cart' => $cartData['items'],
            'summary' => $cartData['summary'],
            'order_id' => $order->order_id,
        ]);
    }

    // ==================== STRIPE ====================

    public function createStripeIntent(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,order_id',
            ]);

            $order = Order::with('items.book')
                ->where('order_id', $request->order_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Crear Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => round($order->total * 100), // Convertir a centavos
                'currency' => 'mxn',
                'metadata' => [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'user_id' => Auth::id(),
                ],
                'description' => 'Pedido #' . $order->order_number,
            ]);

            // Crear registro de pago
            $payment = Payment::create([
                'order_id' => $order->order_id,
                'payment_method' => Payment::METHOD_STRIPE,
                'status' => Payment::STATUS_PENDING,
                'amount' => $order->total,
                'currency' => 'MXN',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'payment_details' => [
                    'client_secret' => $paymentIntent->client_secret,
                ],
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->payment_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error creando Payment Intent: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function confirmStripePayment(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
            ]);

            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            $payment = Payment::where('stripe_payment_intent_id', $request->payment_intent_id)
                ->firstOrFail();

            if ($paymentIntent->status === 'succeeded') {
                $payment->markAsCompleted();
                
                // Actualizar orden
                $order = Order::find($payment->order_id);
                $order->status = 'procesando';
                $order->payment_status = 'completado';
                $order->save();
                
                // GENERAR FACTURA AUTOMÁTICAMENTE
                $this->generateInvoice($order);

                // Limpiar carrito
                $cart = Cart::forUser(Auth::id())->first();
                if ($cart) {
                    $cart->clear();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pago completado exitosamente',
                    'order_id' => $payment->order_id,
                ]);
            } else {
                $payment->markAsFailed('Payment Intent status: ' . $paymentIntent->status);

                return response()->json([
                    'success' => false,
                    'message' => 'El pago no se completó correctamente',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error confirmando pago Stripe: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ==================== PAYPAL ====================

    private function getPayPalAccessToken()
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $mode = config('services.paypal.mode');
        
        $url = $mode === 'sandbox' 
            ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token'
            : 'https://api-m.paypal.com/v1/oauth2/token';

        $response = Http::withBasicAuth($clientId, $secret)
            ->asForm()
            ->post($url, [
                'grant_type' => 'client_credentials'
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Error obteniendo token de PayPal: ' . $response->body());
    }

    public function createPayPalPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
        ]);

        $order = Order::with('items.book')
            ->where('order_id', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $accessToken = $this->getPayPalAccessToken();
            $mode = config('services.paypal.mode');
            
            $url = $mode === 'sandbox'
                ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders'
                : 'https://api-m.paypal.com/v2/checkout/orders';

            $amount = number_format((float)$order->total, 2, '.', '');

            Log::info('PayPal Order Data:', [
                'order_id' => $order->order_id,
                'amount' => $amount,
                'currency' => 'MXN'
            ]);

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($url, [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => (string)$order->order_number,
                            'description' => 'Pedido Librerías Gonvill #' . $order->order_number,
                            'amount' => [
                                'currency_code' => 'MXN',
                                'value' => $amount
                            ]
                        ]
                    ],
                    'application_context' => [
                        'brand_name' => 'Librerías Gonvill',
                        'locale' => 'es-MX',
                        'landing_page' => 'BILLING',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('paypal.success'),
                        'cancel_url' => route('paypal.cancel')
                    ]
                ]);

            Log::info('PayPal API Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error('PayPal API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers()
                ]);
                throw new \Exception('Error al crear orden en PayPal: ' . $response->body());
            }

            $paypalData = $response->json();

            $payment = Payment::create([
                'order_id' => $order->order_id,
                'payment_method' => Payment::METHOD_PAYPAL,
                'status' => Payment::STATUS_PENDING,
                'amount' => $order->total,
                'currency' => 'MXN',
                'paypal_order_id' => $paypalData['id'],
                'payment_details' => $paypalData
            ]);

            $approvalUrl = collect($paypalData['links'])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            if (!$approvalUrl) {
                throw new \Exception('No se pudo obtener la URL de aprobación de PayPal');
            }

            return response()->json([
                'success' => true,
                'approval_url' => $approvalUrl,
                'payment_id' => $payment->payment_id,
                'paypal_order_id' => $paypalData['id']
            ]);

        } catch (\Exception $e) {
            Log::error('Error creando pago PayPal:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pago de PayPal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function capturePayPalPayment(Request $request)
    {
        $request->validate([
            'paypal_order_id' => 'required|string',
        ]);

        try {
            $paypalOrderId = $request->paypal_order_id;
            
            Log::info('=== INICIANDO CAPTURA PAYPAL ===');
            Log::info('PayPal Order ID: ' . $paypalOrderId);

            $accessToken = $this->getPayPalAccessToken();
            $mode = config('services.paypal.mode');
            
            $url = $mode === 'sandbox'
                ? "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$paypalOrderId}/capture"
                : "https://api-m.paypal.com/v2/checkout/orders/{$paypalOrderId}/capture";

            Log::info('URL de captura: ' . $url);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
            ]);

            $responseBody = curl_exec($ch);
            $httpCode = curl_getInfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            Log::info('Respuesta HTTP Code: ' . $httpCode);
            Log::info('Respuesta Body: ' . $responseBody);

            if ($httpCode !== 201 && $httpCode !== 200) {
                throw new \Exception('PayPal capture falló (HTTP ' . $httpCode . '): ' . $responseBody);
            }

            $captureData = json_decode($responseBody, true);

            if (!$captureData || !isset($captureData['status'])) {
                throw new \Exception('Respuesta inválida de PayPal');
            }

            Log::info('Estado de captura: ' . $captureData['status']);

            if ($captureData['status'] === 'COMPLETED') {
                $payment = Payment::where('paypal_order_id', $paypalOrderId)->firstOrFail();
                
                $payment->update([
                    'payment_details' => array_merge(
                        $payment->payment_details ?? [],
                        ['capture' => $captureData]
                    )
                ]);
                
                $payment->markAsCompleted();

                $order = Order::find($payment->order_id);
                $order->status = 'procesando';
                $order->payment_status = 'completado';
                $order->save();

                // GENERAR FACTURA AUTOMÁTICAMENTE
                $this->generateInvoice($order);

                Log::info('=== PAGO COMPLETADO EXITOSAMENTE ===');
                Log::info('Payment ID: ' . $payment->payment_id);
                Log::info('Order ID: ' . $order->order_id);

                $cart = Cart::forUser(Auth::id())->first();
                if ($cart) {
                    $cart->clear();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Pago completado exitosamente',
                    'order_id' => $payment->order_id,
                ]);
            } else {
                $payment = Payment::where('paypal_order_id', $paypalOrderId)->first();
                if ($payment) {
                    $payment->markAsFailed('PayPal status: ' . $captureData['status']);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'El pago no se completó. Estado: ' . $captureData['status'],
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('=== ERROR EN CAPTURA PAYPAL ===');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function executePayPalPayment(Request $request)
    {
        return redirect()->route('profile')
            ->with('success', 'Procesando tu pago de PayPal...');
    }

    public function cancelPayPalPayment(Request $request)
    {
        return redirect()->route('cart.index')
            ->with('error', 'Has cancelado el pago de PayPal');
    }

    // ==================== WEBHOOK DE STRIPE ====================

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            if ($webhookSecret) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                $event = json_decode($payload);
            }

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    
                    $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                    
                    if ($payment && $payment->status === Payment::STATUS_PENDING) {
                        $payment->markAsCompleted();
                        
                        $order = Order::find($payment->order_id);
                        if ($order) {
                            $order->status = 'procesando';
                            $order->payment_status = 'completado';
                            $order->save();
                            
                            // GENERAR FACTURA AUTOMÁTICAMENTE
                            $this->generateInvoice($order);
                        }
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    
                    $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                    
                    if ($payment) {
                        $payment->markAsFailed($paymentIntent->last_payment_error->message ?? 'Unknown error');
                    }
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // ==================== HELPERS ====================

    private function getOrCreateCart()
    {
        $userId = Auth::id();

        if ($userId) {
            $cart = Cart::with(['items.book.category', 'items.book.authors'])
                ->forUser($userId)
                ->active()
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        } else {
            $sessionId = session()->getId();

            $cart = Cart::with(['items.book.category', 'items.book.authors'])
                ->forSession($sessionId)
                ->active()
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => null,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(30),
                ]);
            }
        }

        return $cart;
    }

    private function formatCartResponse(Cart $cart)
    {
        $items = $cart->items->map(function ($item) {
            $book = $item->book;

            return [
                'book_id' => $book->book_id,
                'title' => $book->title,
                'authors' => $book->authors_list ?? 'Sin autor',
                'cover_url' => $book->cover_url,
                'price' => $book->price,
                'discount_percentage' => $book->discount_percentage,
                'discounted_price' => $book->discounted_price ?? $book->price,
                'quantity' => $item->quantity,
                'subtotal' => round($item->subtotal, 2),
                'stock_available' => $book->stock_quantity,
                'in_stock' => $book->in_stock,
                'weight' => $book->weight ?? 0,
            ];
        })->values()->toArray();

        $summary = [
            'subtotal' => round($cart->subtotal, 2),
            'discount' => round($cart->discount_amount, 2),
            'total' => round($cart->total, 2),
            'items_count' => $cart->items_count,
            'total_weight_grams' => $cart->total_weight,
            'total_weight_kg' => round($cart->total_weight / 1000, 2),
        ];

        return [
            'items' => $items,
            'summary' => $summary,
        ];
    }

    /**
     * Generar factura automáticamente para una orden pagada
     */
    private function generateInvoice($order)
    {
        try {
            // Verificar que no exista ya una factura
            if ($order->invoice) {
                Log::info('La orden ya tiene factura generada: ' . $order->order_number);
                return;
            }

            // Generar número de factura único
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            
            $invoice = Invoice::create([
                'order_id' => $order->order_id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $order->subtotal,
                'tax' => $order->tax_amount ?? 0,
                'total' => $order->total,
                'issue_date' => now(),
            ]);

            Log::info('Factura generada automáticamente: ' . $invoiceNumber . ' para orden #' . $order->order_number);
            
        } catch (\Exception $e) {
            Log::error('Error generando factura automática: ' . $e->getMessage());
        }
    }
}