<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\Address;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShippingController extends Controller
{
    /**
     * Listar todos los métodos de envío activos
     * GET /api/shipping-methods
     */
    public function index(Request $request)
    {
        $query = ShippingMethod::active()->ordered();

        // Filtrar por estado si se proporciona
        if ($request->has('state') && $request->state) {
            $query->availableForState($request->state);
        }

        $methods = $query->get();

        return response()->json([
            'success' => true,
            'shipping_methods' => $methods->map(function ($method) {
                return [
                    'shipping_method_id' => $method->shipping_method_id,
                    'name' => $method->name,
                    'description' => $method->description,
                    'base_cost' => $method->base_cost,
                    'cost_per_kg' => $method->cost_per_kg,
                    'free_shipping_threshold' => $method->free_shipping_threshold,
                    'estimated_delivery' => $method->estimated_delivery,
                    'estimated_days_min' => $method->estimated_days_min,
                    'estimated_days_max' => $method->estimated_days_max,
                    'is_free_shipping_available' => $method->is_free_shipping_available,
                ];
            }),
        ]);
    }

    /**
     * Ver detalle de un método de envío
     * GET /api/shipping-methods/{id}
     */
    public function show($id)
    {
        $method = ShippingMethod::active()->findOrFail($id);

        return response()->json([
            'success' => true,
            'shipping_method' => [
                'shipping_method_id' => $method->shipping_method_id,
                'name' => $method->name,
                'description' => $method->description,
                'base_cost' => $method->base_cost,
                'cost_per_kg' => $method->cost_per_kg,
                'free_shipping_threshold' => $method->free_shipping_threshold,
                'estimated_delivery' => $method->estimated_delivery,
                'estimated_days_min' => $method->estimated_days_min,
                'estimated_days_max' => $method->estimated_days_max,
                'available_states' => $method->available_states,
                'is_free_shipping_available' => $method->is_free_shipping_available,
            ],
        ]);
    }

    /**
     * Calcular costo de envío para el carrito actual
     * POST /api/shipping/calculate
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,shipping_method_id',
            'address_id' => 'nullable|exists:addresses,address_id',
        ]);

        $user = Auth::user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Obtener método de envío
        $shippingMethod = ShippingMethod::active()->findOrFail($request->shipping_method_id);

        // Verificar disponibilidad por estado si se proporciona dirección
        if ($request->has('address_id')) {
            $address = Address::where('user_id', $user->user_id)
                              ->findOrFail($request->address_id);

            if (!$shippingMethod->isAvailableForState($address->state)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este método de envío no está disponible para tu estado',
                ], 400);
            }
        }

        // Calcular peso total y subtotal del carrito
        $bookIds = array_column($cart, 'book_id');
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy('book_id');

        $totalWeight = 0; // en gramos
        $subtotal = 0;

        foreach ($cart as $item) {
            $book = $books->get($item['book_id']);
            if ($book) {
                $totalWeight += ($book->weight ?? 0) * $item['quantity'];
                $subtotal += $book->discounted_price * $item['quantity'];
            }
        }

        // Aplicar descuento de cupón si existe
        $couponData = Session::get('cart_coupon');
        $discountAmount = $couponData['discount_amount'] ?? 0;
        $subtotalAfterDiscount = $subtotal - $discountAmount;

        // Convertir peso a kilogramos
        $totalWeightKg = $totalWeight / 1000;

        // Calcular costo de envío
        $shippingCost = $shippingMethod->calculateShippingCost($totalWeightKg, $subtotalAfterDiscount);

        // Verificar si aplica envío gratis
        $isFreeShipping = $shippingCost === 0 && $shippingMethod->free_shipping_threshold;
        
        return response()->json([
            'success' => true,
            'calculation' => [
                'shipping_method' => [
                    'shipping_method_id' => $shippingMethod->shipping_method_id,
                    'name' => $shippingMethod->name,
                    'estimated_delivery' => $shippingMethod->estimated_delivery,
                ],
                'weight' => [
                    'total_grams' => $totalWeight,
                    'total_kg' => round($totalWeightKg, 2),
                ],
                'costs' => [
                    'subtotal' => round($subtotal, 2),
                    'discount' => round($discountAmount, 2),
                    'subtotal_after_discount' => round($subtotalAfterDiscount, 2),
                    'base_cost' => $shippingMethod->base_cost,
                    'weight_cost' => round($shippingMethod->cost_per_kg * $totalWeightKg, 2),
                    'shipping_cost' => round($shippingCost, 2),
                    'is_free_shipping' => $isFreeShipping,
                ],
                'free_shipping_info' => $shippingMethod->getFreeShippingMessage($subtotalAfterDiscount),
            ],
        ]);
    }

    /**
     * Calcular costos para todos los métodos disponibles
     * POST /api/shipping/calculate-all
     */
    public function calculateAll(Request $request)
    {
        $request->validate([
            'address_id' => 'nullable|exists:addresses,address_id',
        ]);

        $user = Auth::user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Obtener estado si hay dirección
        $state = null;
        if ($request->has('address_id')) {
            $address = Address::where('user_id', $user->user_id)
                              ->findOrFail($request->address_id);
            $state = $address->state;
        }

        // Obtener métodos disponibles
        $query = ShippingMethod::active()->ordered();
        if ($state) {
            $query->availableForState($state);
        }
        $shippingMethods = $query->get();

        // Calcular peso total y subtotal
        $bookIds = array_column($cart, 'book_id');
        $books = Book::whereIn('book_id', $bookIds)->get()->keyBy('book_id');

        $totalWeight = 0;
        $subtotal = 0;

        foreach ($cart as $item) {
            $book = $books->get($item['book_id']);
            if ($book) {
                $totalWeight += ($book->weight ?? 0) * $item['quantity'];
                $subtotal += $book->discounted_price * $item['quantity'];
            }
        }

        $couponData = Session::get('cart_coupon');
        $discountAmount = $couponData['discount_amount'] ?? 0;
        $subtotalAfterDiscount = $subtotal - $discountAmount;

        $totalWeightKg = $totalWeight / 1000;

        // Calcular para cada método
        $calculations = $shippingMethods->map(function ($method) use ($totalWeightKg, $subtotalAfterDiscount) {
            $shippingCost = $method->calculateShippingCost($totalWeightKg, $subtotalAfterDiscount);
            $isFreeShipping = $shippingCost === 0 && $method->free_shipping_threshold;

            return [
                'shipping_method_id' => $method->shipping_method_id,
                'name' => $method->name,
                'description' => $method->description,
                'estimated_delivery' => $method->estimated_delivery,
                'shipping_cost' => round($shippingCost, 2),
                'is_free_shipping' => $isFreeShipping,
                'free_shipping_message' => $method->getFreeShippingMessage($subtotalAfterDiscount),
            ];
        });

        return response()->json([
            'success' => true,
            'weight' => [
                'total_grams' => $totalWeight,
                'total_kg' => round($totalWeightKg, 2),
            ],
            'subtotal_after_discount' => round($subtotalAfterDiscount, 2),
            'shipping_options' => $calculations,
        ]);
    }

    /**
     * Verificar disponibilidad de un método para un estado
     * GET /api/shipping-methods/{id}/check-availability
     */
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'state' => 'required|string',
        ]);

        $method = ShippingMethod::active()->findOrFail($id);
        $isAvailable = $method->isAvailableForState($request->state);

        return response()->json([
            'success' => true,
            'shipping_method' => $method->name,
            'state' => $request->state,
            'is_available' => $isAvailable,
            'message' => $isAvailable 
                ? 'Disponible para ' . $request->state
                : 'No disponible para ' . $request->state,
        ]);
    }
}