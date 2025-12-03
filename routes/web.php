<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;

// Ruta principal
Route::get('/', [PageController::class, 'inicio'])->name('inicio');

// Rutas de autenticación WEB
Route::get('/mi-cuenta', [WebAuthController::class, 'showLoginForm'])->name('mi.cuenta');
Route::post('/register', [WebAuthController::class, 'register'])->name('register');
Route::post('/login', [WebAuthController::class, 'login'])->name('login');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [WebAuthController::class, 'logout'])->name('admin.logout');

// Perfil de usuario (requiere autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [WebAuthController::class, 'showProfile'])->name('profile');
});

// Dashboard de administrador y CRUD de libros
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // CRUD de libros
    Route::get('/books', [AdminController::class, 'getBooks'])->name('books.index');
    Route::get('/books/create', [AdminController::class, 'createBookForm'])->name('books.create');
    Route::post('/books', [AdminController::class, 'createBook'])->name('books.store');
    Route::get('/books/{id}/edit', [AdminController::class, 'editBookForm'])->name('books.edit');
    Route::put('/books/{id}', [AdminController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{id}', [AdminController::class, 'deleteBook'])->name('books.destroy');
});

// Rutas de páginas estáticas

Route::get('/impresion-bajo-demanda', [PageController::class, 'impresionBajoDemanda'])->name('impresion.demanda');
Route::get('/sobre-nosotros', [PageController::class, 'sobreNosotros'])->name('sobre.nosotros');
Route::get('/nuestras-librerias', [PageController::class, 'nuestrasLibrerias'])->name('nuestras.librerias');
Route::get('/bolsa-de-trabajo', [PageController::class, 'bolsaTrabajo'])->name('bolsa.trabajo');
Route::get('/ayuda', [PageController::class, 'ayuda'])->name('ayuda');
Route::get('/schoolshop', [PageController::class, 'schoolShop'])->name('schoolshop');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::get('/new-releases', [PageController::class, 'newReleases'])->name('new.releases');
Route::get('/politicas-envios', [PageController::class, 'politicasenvios'])->name('politicas.envios');


// Rutas de libros
Route::prefix('libros')->name('libros.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/busqueda-avanzada', [BookController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');
    Route::get('/{id}', [BookController::class, 'show'])->name('show');
    Route::post('/buscar', [BookController::class, 'buscar'])->name('buscar');
});

// Rutas de carrito y favoritos (protegidas con autenticación)
Route::middleware(['auth'])->group(function () {
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{book_id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{book_id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear/all', [CartController::class, 'clear'])->name('clear');
        Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('applyCoupon');
        Route::delete('/remove-coupon', [CartController::class, 'removeCoupon'])->name('removeCoupon');
    });
});

// Rutas de pago (TODAS protegidas con autenticación)
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout');
    
    // Stripe
    Route::post('/payment/stripe/intent', [PaymentController::class, 'createStripeIntent'])->name('stripe.intent');
    Route::post('/payment/stripe/confirm', [PaymentController::class, 'confirmStripePayment'])->name('stripe.confirm');
    
    // PayPal
    Route::post('/payment/paypal/create', [PaymentController::class, 'createPayPalPayment'])->name('paypal.create');
    Route::post('/payment/paypal/capture', [PaymentController::class, 'capturePayPalPayment'])->name('paypal.capture'); 
    Route::get('/payment/paypal/success', [PaymentController::class, 'executePayPalPayment'])->name('paypal.success');
    Route::get('/payment/paypal/cancel', [PaymentController::class, 'cancelPayPalPayment'])->name('paypal.cancel');
});

// Webhook de Stripe (sin middleware de autenticación)
Route::post('/webhook/stripe', [PaymentController::class, 'handleStripeWebhook'])->name('stripe.webhook');

// Ruta hacia Politicas de seguridad 
Route::get('/proteccion-datos', function () {
    return view('proteccion-datos');
})->name('proteccion-datos');
