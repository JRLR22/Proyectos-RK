<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Listar órdenes del usuario
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::with(['items.book', 'address', 'shippingMethod'])
                      ->where('user_id', $user->user_id)
                      ->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'status_color' => $order->status_color,
                    'total' => $order->total,
                    'total_items' => $order->total_items,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'can_be_cancelled' => $order->can_be_cancelled,
                ];
            }),
        ]);
    }

    /**
     * Ver detalle de una orden
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $order = Order::with([
            'items.book.authors',
            'address',
            'shippingMethod',
            'coupon',
            'payment'
        ])->where('user_id', $user->user_id)
          ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => [
                'order_id' => $order->order_id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'status_color' => $order->status_color,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'tracking_number' => $order->tracking_number,
                
                // Items
                'items' => $order->items->map(function ($item) {
                    return [
                        'book_id' => $item->book_id,
                        'title' => $item->book->title,
                        'authors' => $item->book->authors_list,
                        'cover_url' => $item->book->cover_url,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'discount_percentage' => $item->discount_percentage,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                
                // Totales
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'shipping_cost' => $order->shipping_cost,
                'tax_amount' => $order->tax_amount,
                'total' => $order->total,
                
                // Dirección
                'address' => $order->address ? [
                    'recipient_name' => $order->address->recipient_name,
                    'phone' => $order->address->phone,
                    'full_address' => $order->address->full_address,
                    'formatted' => $order->address->formatted_address,
                ] : null,
                
                // Envío
                'shipping_method' => $order->shippingMethod ? [
                    'name' => $order->shippingMethod->name,
                    'description' => $order->shippingMethod->description,
                    'estimated_delivery' => $order->shippingMethod->estimated_delivery,
                ] : null,
                
                // Cupón
                'coupon' => $order->coupon ? [
                    'code' => $order->coupon->code,
                    'discount_text' => $order->coupon->discount_text,
                ] : null,
                
                // Fechas
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'shipped_at' => $order->shipped_at?->format('d/m/Y H:i'),
                'delivered_at' => $order->delivered_at?->format('d/m/Y H:i'),
                'cancelled_at' => $order->cancelled_at?->format('d/m/Y H:i'),
                'cancellation_reason' => $order->cancellation_reason,
                
                'can_be_cancelled' => $order->can_be_cancelled,
            ],
        ]);
    }

    /**
     * Crear orden desde el carrito
     * POST /api/orders
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,address_id',
            'shipping_method_id' => 'required|exists:shipping_methods,shipping_method_id',
            'payment_method' => 'required|string|in:card,paypal,cash_on_delivery',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Verificar que la dirección pertenezca al usuario
        $address = Address::where('address_id', $request->address_id)
                          ->where('user_id', $user->user_id)
                          ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Dirección inválida',
            ], 404);
        }

        // Verificar método de envío
        $shippingMethod = ShippingMethod::where('shipping_method_id', $request->shipping_method_id)
                                        ->where('active', true)
                                        ->first();

        if (!$shippingMethod) {
            return response()->json([
                'success' => false,
                'message' => 'Método de envío inválido',
            ], 404);
        }

        // Obtener cupón de sesión (si existe)
        $couponData = Session::get('cart_coupon');
        $couponId = $couponData['coupon_id'] ?? null;

        DB::beginTransaction();

        try {
            // Crear la orden
            $order = Order::create([
                'user_id' => $user->user_id,
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'address_id' => $address->address_id,
                'shipping_method_id' => $shippingMethod->shipping_method_id,
                'coupon_id' => $couponId,
                'notes' => $request->notes,
                'subtotal' => 0,
                'discount_amount' => 0,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total' => 0,
            ]);

            // Agregar items al pedido
            $bookIds = array_column($cart, 'book_id');
            $books = Book::whereIn('book_id', $bookIds)->get()->keyBy('book_id');

            foreach ($cart as $item) {
                $book = $books->get($item['book_id']);

                if (!$book) {
                    throw new \Exception('Libro no encontrado: ' . $item['book_id']);
                }

                // Verificar stock
                if ($book->stock_quantity < $item['quantity']) {
                    throw new \Exception('Stock insuficiente para: ' . $book->title);
                }

                // Crear item de la orden
                $orderItem = OrderItem::create([
                    'order_id' => $order->order_id,
                    'book_id' => $book->book_id,
                    'quantity' => $item['quantity'],
                    'price' => $book->price,
                    'discount_percentage' => $book->discount_percentage ?? 0,
                    'subtotal' => 0,
                ]);

                $orderItem->calculateSubtotal();
            }

            // Calcular totales de la orden
            $order->calculateTotals();

            DB::commit();

            // Limpiar carrito y cupón de sesión
            Session::forget('cart');
            Session::forget('cart_coupon');

            // Recargar orden con relaciones
            $order->load(['items.book', 'address', 'shippingMethod', 'coupon']);

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'order' => [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total' => $order->total,
                    'payment_method' => $order->payment_method,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancelar una orden
     * PUT /api/orders/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        
        $order = Order::where('user_id', $user->user_id)
                      ->findOrFail($id);

        if (!$order->can_be_cancelled) {
            return response()->json([
                'success' => false,
                'message' => 'Esta orden no puede ser cancelada',
            ], 400);
        }

        try {
            $order->cancel($request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Orden cancelada exitosamente',
                'order' => [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'cancelled_at' => $order->cancelled_at->format('d/m/Y H:i'),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener resumen antes de crear orden (pre-checkout)
     * POST /api/orders/preview
     */
    public function preview(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,address_id',
            'shipping_method_id' => 'required|exists:shipping_methods,shipping_method_id',
        ]);

        $user = Auth::user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Calcular subtotal
        $bookIds = array_column($cart, 'book_id');
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy('book_id');

        $subtotal = 0;
        $totalWeight = 0;

        foreach ($cart as $item) {
            $book = $books->get($item['book_id']);
            if ($book) {
                $itemPrice = $book->discounted_price * $item['quantity'];
                $subtotal += $itemPrice;
                $totalWeight += ($book->weight ?? 0) * $item['quantity'];
            }
        }

        // Calcular descuento
        $couponData = Session::get('cart_coupon');
        $discountAmount = $couponData['discount_amount'] ?? 0;

        // Calcular envío
        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
        $shippingCost = $shippingMethod->calculateShippingCost(
            $totalWeight / 1000, // Convertir a kg
            $subtotal - $discountAmount
        );

        // Calcular impuestos (16%)
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = round($taxableAmount * 0.16, 2);

        // Total
        $total = $subtotal - $discountAmount + $shippingCost + $taxAmount;

        return response()->json([
            'success' => true,
            'preview' => [
                'subtotal' => round($subtotal, 2),
                'discount' => round($discountAmount, 2),
                'shipping' => round($shippingCost, 2),
                'tax' => round($taxAmount, 2),
                'total' => round($total, 2),
                'items_count' => array_sum(array_column($cart, 'quantity')),
            ],
        ]);
    }
}