<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\ShippingController;
use App\Http\Controllers\Web\WishlistController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\UserController;



// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Rutas de libros
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::get('/books/{id}/detail', [BookController::class, 'detail']); 
Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/featured', [BookController::class, 'featured']);

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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [ApiOrderController::class, 'index']);
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::put('/orders/{id}/cancel', [\App\Http\Controllers\Api\OrderController::class, 'cancel']);
});

// Rutas de direcciones (requieren autenticación)
Route::middleware('auth:web')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::get('/addresses/{id}', [AddressController::class, 'show']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::put('/addresses/{id}/set-default', [AddressController::class, 'setDefault']);
    Route::get('/addresses/default', [AddressController::class, 'getDefault']);
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

// Rutas públicas (sin autenticación) - para reset password
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/verify-token', [PasswordResetController::class, 'verifyToken']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

// Ruta protegida (requiere autenticación) - para cambiar contraseña desde ajustes
Route::middleware('auth:sanctum')->post('/password/change', [PasswordResetController::class, 'change']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::get('/user', [UserController::class, 'show']);
});