<?php

namespace App\Http\Controllers\Web;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Obtener wishlist del usuario
     * GET /api/wishlist
     */
    public function index()
    {
        $user = Auth::user();
        
        $wishlistData = Wishlist::with(['book.authors', 'book.category'])
                            ->where('user_id', $user->user_id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $wishlist = $wishlistData->map(function ($item) {
            return [
                'wishlist_id' => $item->wishlist_id,
                'book' => [
                    'book_id' => $item->book->book_id,
                    'title' => $item->book->title,
                    'authors' => $item->book->authors_list,
                    'cover_url' => $item->book->cover_url,
                    'price' => $item->book->price,
                    'discounted_price' => $item->book->discounted_price,
                ],
            ];
        });

        return view('wishlist', compact('wishlist'));
    }

    /**
     * Agregar libro a wishlist
     * POST /api/wishlist/add/{book_id}
     */
    public function add($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $exists = Wishlist::where('user_id', $user->user_id)
                        ->where('book_id', $bookId)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Este libro ya está en tu lista de deseos');
        }

        Wishlist::create([
            'user_id' => $user->user_id,
            'book_id' => $bookId,
        ]);

        return back()->with('success', '¡Libro agregado a tu lista de deseos! ❤️');
    }

        /**
     * Remover libro a wishlist
     * POST /api/wishlist/remove/{book_id}
     */

    public function remove($id)
    {
        $user = Auth::user();
        
        Wishlist::where('user_id', $user->user_id)
                ->where('wishlist_id', $id)
                ->delete();

        return redirect()->route('wishlist.index')->with('success', 'Libro eliminado de tu lista');
    }

    /**
     * Eliminar por book_id
     * DELETE /api/wishlist/book/{book_id}
     */
    public function removeByBookId($bookId)
    {
        $user = Auth::user();

        $deleted = Wishlist::where('user_id', $user->user_id)
                           ->where('book_id', $bookId)
                           ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Este libro no está en tu lista de deseos',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Libro eliminado de tu lista de deseos',
        ]);
    }

    /**
     * Verificar si un libro está en wishlist
     * GET /api/wishlist/check/{book_id}
     */
    public function check($bookId)
    {
        $user = Auth::user();

        $inWishlist = Wishlist::where('user_id', $user->user_id)
                              ->where('book_id', $bookId)
                              ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
        ]);
    }

    /**
     * Limpiar toda la wishlist
     * DELETE /api/wishlist/clear
     */
    public function clear()
    {
        $user = Auth::user();

        $deleted = Wishlist::where('user_id', $user->user_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lista de deseos limpiada',
            'items_deleted' => $deleted,
        ]);
    }

    /**
     * Mover item de wishlist al carrito
     * POST /api/wishlist/{id}/move-to-cart
     */
    public function moveToCart(Request $request, $id)
    {
        $user = Auth::user();

        $wishlistItem = Wishlist::where('user_id', $user->user_id)
                                ->with('book')
                                ->findOrFail($id);

        $book = $wishlistItem->book;

        // Verificar stock
        if (!$book->in_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Este libro no está disponible',
            ], 400);
        }

        // Agregar al carrito (usando CartController internamente)
        $cartController = new CartController();
        $cartRequest = new Request([
            'book_id' => $book->book_id,
            'quantity' => 1,
        ]);

        $cartResponse = $cartController->add($cartRequest);

        // Si se agregó correctamente, eliminar de wishlist
        if ($cartResponse->getStatusCode() === 200) {
            $wishlistItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Libro movido al carrito',
            ]);
        }

        return $cartResponse;
    }
}