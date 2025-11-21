<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Mostrar vista del carrito (WEB)
     * GET /cart
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cartData = $this->formatCartResponse($cart);

        return view('cart', [
            'cart' => $cartData['items'],
            'summary' => $cartData['summary']
        ]);
    }

    /**
     * Agregar producto al carrito (WEB)
     * POST /cart/add
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
            return redirect()->back()->with('error', 'Stock insuficiente. Solo quedan ' . $book->stock_quantity . ' unidades.');
        }

        $cart = $this->getOrCreateCart();

        // Buscar si el libro ya está en el carrito
        $cartItem = $cart->items()->where('book_id', $request->book_id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Verificar que no exceda el stock
            if ($newQuantity > $book->stock_quantity) {
                return redirect()->back()->with('error', 'No puedes agregar más de ' . $book->stock_quantity . ' unidades.');
            }

            $cartItem->incrementQuantity($request->quantity);
        } else {
            // Crear nuevo item en el carrito
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'book_id' => $book->book_id,
                'quantity' => $request->quantity,
                'price_at_addition' => $book->discounted_price ?? $book->price,            
            ]);
        }

        $cart->touch();

        return redirect()->route('cart.index')->with('success', '✅ Producto agregado al carrito');
    }

    /**
     * Actualizar cantidad de un item (WEB)
     * PUT /cart/{book_id}
     */
    public function update(Request $request, $bookId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($bookId);
        $cart = $this->getOrCreateCart();

        $cartItem = $cart->items()->where('book_id', $bookId)->first();

        if (!$cartItem) {
            return redirect()->route('cart.index')->with('error', 'Producto no encontrado en el carrito');
        }

        // Verificar stock
        if ($request->quantity > $book->stock_quantity) {
            return redirect()->route('cart.index')->with('error', 'Stock insuficiente. Solo quedan ' . $book->stock_quantity . ' unidades.');
        }

        $cartItem->updateQuantity($request->quantity);
        $cart->touch();

        return redirect()->route('cart.index')->with('success', 'Cantidad actualizada');
    }

    /**
     * Eliminar producto del carrito (WEB)
     * DELETE /cart/{book_id}
     */
    public function remove($bookId)
    {
        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('book_id', $bookId)->first();

        if (!$cartItem) {
            return redirect()->route('cart.index')->with('error', 'Producto no encontrado en el carrito');
        }

        $cartItem->delete();
        $cart->touch();

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Vaciar el carrito (WEB)
     * DELETE /cart/clear
     */
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->clear();

        return redirect()->route('cart.index')->with('success', 'Carrito vaciado');
    }

    /**
     * Aplicar cupón de descuento (WEB)
     * POST /cart/apply-coupon
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = $this->getOrCreateCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío');
        }

        // Buscar cupón
        $coupon = Coupon::byCode($request->coupon_code)->valid()->first();

        if (!$coupon) {
            return redirect()->route('cart.index')->with('error', 'Cupón inválido o expirado');
        }

        // Verificar si el usuario puede usar el cupón
        $userId = Auth::id();
        $validation = $coupon->canBeUsedBy($userId, $cart->subtotal);

        if (!$validation['valid']) {
            return redirect()->route('cart.index')->with('error', $validation['message']);
        }

        // Aplicar cupón al carrito
        $cart->applyCoupon($coupon, $validation['discount']);

        return redirect()->route('cart.index')->with('success', 'Cupón aplicado correctamente');
    }

    /**
     * Remover cupón aplicado (WEB)
     * DELETE /cart/remove-coupon
     */
    public function removeCoupon()
    {
        $cart = $this->getOrCreateCart();
        $cart->removeCoupon();

        return redirect()->route('cart.index')->with('success', 'Cupón removido');
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Obtener o crear carrito según el contexto del usuario
     */
    private function getOrCreateCart()
    {
        $userId = Auth::id();

        if ($userId) {
            // Usuario autenticado: buscar por user_id
            $cart = Cart::with(['items.book.category', 'items.book.authors'])
                ->forUser($userId)
                ->active()
                ->first();

            if (!$cart) {
                // Crear carrito para usuario autenticado
                $cart = Cart::create([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);

                // Migrar carrito de sesión si existe
                $this->migrateGuestCartToUser($cart);
            }
        } else {
            // Usuario guest: usar session_id
            $sessionId = $this->getOrCreateSessionId();

            $cart = Cart::with(['items.book.category', 'items.book.authors'])
                ->forSession($sessionId)
                ->active()
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => null,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(30), // Expirar en 30 días
                ]);
            }
        }

        return $cart;
    }

    /**
     * Obtener o crear session_id para guests
     */
    private function getOrCreateSessionId()
    {
        // Para auth normal de Laravel, usa el session ID nativo
        return session()->getId();
    }

    /**
     * Migrar carrito de guest a usuario autenticado
     */
    private function migrateGuestCartToUser(Cart $userCart)
    {
        $sessionId = session()->getId();

        if (!$sessionId) {
            return;
        }

        $guestCart = Cart::forSession($sessionId)->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        // Migrar items del carrito guest al carrito del usuario
        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()
                ->where('book_id', $guestItem->book_id)
                ->first();

            if ($existingItem) {
                // Si ya existe, sumar cantidades
                $existingItem->incrementQuantity($guestItem->quantity);
            } else {
                // Si no existe, crear nuevo item
                CartItem::create([
                    'cart_id' => $userCart->cart_id,
                    'book_id' => $guestItem->book_id,
                    'quantity' => $guestItem->quantity,
                    'price_at_addition' => $guestItem->price_at_addition,
                ]);
            }
        }

        // Eliminar carrito guest
        $guestCart->delete();
    }

    /**
     * Formatear respuesta del carrito
     */
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
}