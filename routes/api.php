<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;


// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Libros - rutas públicas
Route::get('/books', [BookController::class, 'index']); 
Route::get('/books/featured', [BookController::class, 'featured']);
Route::get('/books/{id}', [BookController::class, 'show']);

// Categorías - rutas públicas
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

});

// Rutas del carrito (requieren autenticación)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::put('/{book_id}', [CartController::class, 'update']);
    Route::delete('/{book_id}', [CartController::class, 'remove']);
    Route::delete('/clear/all', [CartController::class, 'clear']);
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/remove-coupon', [CartController::class, 'removeCoupon']);
});

// Rutas de órdenes (requieren autenticación)
Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::post('/preview', [OrderController::class, 'preview']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}/cancel', [OrderController::class, 'cancel']);
});

// Rutas de direcciones (requieren autenticación)
Route::middleware('auth:sanctum')->prefix('addresses')->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::get('/default', [AddressController::class, 'getDefault']);
    Route::post('/', [AddressController::class, 'store']);
    Route::get('/{id}', [AddressController::class, 'show']);
    Route::put('/{id}', [AddressController::class, 'update']);
    Route::delete('/{id}', [AddressController::class, 'destroy']);
    Route::put('/{id}/set-default', [AddressController::class, 'setDefault']);
    Route::get('/{id}/validate', [AddressController::class, 'validate']);
});

// Rutas de métodos de envío (requieren autenticación)
Route::middleware('auth:sanctum')->prefix('shipping')->group(function () {
    Route::get('/methods', [ShippingController::class, 'index']);
    Route::get('/methods/{id}', [ShippingController::class, 'show']);
    Route::post('/calculate', [ShippingController::class, 'calculate']);
    Route::post('/calculate-all', [ShippingController::class, 'calculateAll']);
    Route::get('/methods/{id}/check-availability', [ShippingController::class, 'checkAvailability']);
});

// Rutas de Wishlist (requieren autenticación)
Route::middleware('auth:sanctum')->prefix('wishlist')->group(function () {
    Route::get('/', [WishlistController::class, 'index']);
    Route::post('/add/{book_id}', [WishlistController::class, 'add']);
    Route::delete('/{id}', [WishlistController::class, 'remove']);
    Route::delete('/book/{book_id}', [WishlistController::class, 'removeByBookId']);
    Route::get('/check/{book_id}', [WishlistController::class, 'check']);
    Route::post('/clear', [WishlistController::class, 'clear']);
    Route::post('/{id}/move-to-cart', [WishlistController::class, 'moveToCart']);
});

// Rutas de Reviews
Route::prefix('books/{book_id}/reviews')->group(function () {
    // Públicas
    Route::get('/', [ReviewController::class, 'index']);
    
    // Requieren autenticación
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ReviewController::class, 'store']);
        Route::get('/my-review', [ReviewController::class, 'myReview']);
        Route::get('/can-review', [ReviewController::class, 'canReview']);
    });
});

Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
});