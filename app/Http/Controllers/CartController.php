<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Obtener el carrito actual
     * GET /api/cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'cart' => $cartWithDetails,
            'summary' => $summary,
        ]);
    }

    /**
     * Agregar producto al carrito
     * POST /api/cart/add
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,book_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Verificar stock disponible
        if ($book->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $book->stock_quantity . ' unidades.',
            ], 400);
        }

        $cart = $this->getCart();

        // Si el libro ya está en el carrito, sumar la cantidad
        $existingItemKey = $this->findItemInCart($cart, $request->book_id);

        if ($existingItemKey !== null) {
            $newQuantity = $cart[$existingItemKey]['quantity'] + $request->quantity;

            // Verificar que no exceda el stock
            if ($newQuantity > $book->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes agregar más de ' . $book->stock_quantity . ' unidades.',
                ], 400);
            }

            $cart[$existingItemKey]['quantity'] = $newQuantity;
        } else {
            // Agregar nuevo item al carrito
            $cart[] = [
                'book_id' => $book->book_id,
                'quantity' => $request->quantity,
                'added_at' => now()->toIso8601String(),
            ];
        }

        $this->saveCart($cart);

        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'cart' => $cartWithDetails,
            'summary' => $summary,
        ]);
    }

    /**
     * Actualizar cantidad de un item
     * PUT /api/cart/{book_id}
     */
    public function update(Request $request, $bookId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($bookId);
        $cart = $this->getCart();

        $itemKey = $this->findItemInCart($cart, $bookId);

        if ($itemKey === null) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        // Verificar stock
        if ($request->quantity > $book->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $book->stock_quantity . ' unidades.',
            ], 400);
        }

        $cart[$itemKey]['quantity'] = $request->quantity;
        $this->saveCart($cart);

        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'cart' => $cartWithDetails,
            'summary' => $summary,
        ]);
    }

    /**
     * Eliminar producto del carrito
     * DELETE /api/cart/{book_id}
     */
    public function remove($bookId)
    {
        $cart = $this->getCart();
        $itemKey = $this->findItemInCart($cart, $bookId);

        if ($itemKey === null) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        unset($cart[$itemKey]);
        $cart = array_values($cart); // Reindexar el array
        $this->saveCart($cart);

        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'cart' => $cartWithDetails,
            'summary' => $summary,
        ]);
    }

    /**
     * Vaciar el carrito
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        $this->saveCart([]);

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'cart' => [],
            'summary' => [
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'items_count' => 0,
            ],
        ]);
    }

    /**
     * Aplicar cupón de descuento
     * POST /api/cart/apply-coupon
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío',
            ], 400);
        }

        // Buscar cupón
        $coupon = Coupon::byCode($request->coupon_code)->valid()->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupón inválido o expirado',
            ], 404);
        }

        // Calcular subtotal
        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        // Verificar si el usuario puede usar el cupón
        $userId = Auth::id();
        $validation = $coupon->canBeUsedBy($userId, $summary['subtotal']);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message'],
            ], 400);
        }

        // Guardar cupón en sesión
        Session::put('cart_coupon', [
            'coupon_id' => $coupon->coupon_id,
            'code' => $coupon->code,
            'discount_amount' => $validation['discount'],
            'discount_text' => $coupon->discount_text,
        ]);

        // Recalcular con cupón
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'message' => 'Cupón aplicado correctamente',
            'coupon' => Session::get('cart_coupon'),
            'summary' => $summary,
        ]);
    }

    /**
     * Remover cupón aplicado
     * DELETE /api/cart/remove-coupon
     */
    public function removeCoupon()
    {
        Session::forget('cart_coupon');

        $cart = $this->getCart();
        $cartWithDetails = $this->enrichCartWithBookDetails($cart);
        $summary = $this->calculateCartSummary($cartWithDetails);

        return response()->json([
            'success' => true,
            'message' => 'Cupón removido',
            'summary' => $summary,
        ]);
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Obtener carrito de la sesión
     */
    private function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Guardar carrito en sesión
     */
    private function saveCart($cart)
    {
        Session::put('cart', $cart);
    }

    /**
     * Buscar un item en el carrito por book_id
     */
    private function findItemInCart($cart, $bookId)
    {
        foreach ($cart as $key => $item) {
            if ($item['book_id'] == $bookId) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Enriquecer el carrito con detalles de los libros
     */
    private function enrichCartWithBookDetails($cart)
    {
        if (empty($cart)) {
            return [];
        }

        $bookIds = array_column($cart, 'book_id');
        $books = Book::with(['category', 'authors'])
                     ->whereIn('book_id', $bookIds)
                     ->get()
                     ->keyBy('book_id');

        return array_map(function ($item) use ($books) {
            $book = $books->get($item['book_id']);

            if (!$book) {
                return null;
            }

            return [
                'book_id' => $book->book_id,
                'title' => $book->title,
                'authors' => $book->authors_list,
                'cover_url' => $book->cover_url,
                'price' => $book->price,
                'discount_percentage' => $book->discount_percentage,
                'discounted_price' => $book->discounted_price,
                'quantity' => $item['quantity'],
                'subtotal' => round($book->discounted_price * $item['quantity'], 2),
                'stock_available' => $book->stock_quantity,
                'in_stock' => $book->in_stock,
                'weight' => $book->weight ?? 0,
            ];
        }, $cart);
    }

    /**
     * Calcular resumen del carrito
     */
    private function calculateCartSummary($cartWithDetails)
    {
        $cartWithDetails = array_filter($cartWithDetails); // Remover nulls

        $subtotal = array_sum(array_column($cartWithDetails, 'subtotal'));
        $itemsCount = array_sum(array_column($cartWithDetails, 'quantity'));
        $totalWeight = array_sum(array_map(function ($item) {
            return $item['weight'] * $item['quantity'];
        }, $cartWithDetails));

        // Obtener descuento del cupón si existe
        $couponDiscount = 0;
        $coupon = Session::get('cart_coupon');
        if ($coupon) {
            $couponDiscount = $coupon['discount_amount'];
        }

        $total = $subtotal - $couponDiscount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($couponDiscount, 2),
            'total' => round(max($total, 0), 2), // No puede ser negativo
            'items_count' => $itemsCount,
            'total_weight_grams' => $totalWeight,
            'total_weight_kg' => round($totalWeight / 1000, 2),
        ];
    }
}