<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WishlistController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\UserController;

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

    Route::get('/perfil', [ProfileController::class, 'index'])
        ->name('profile');

    Route::put('/user/profile', [UserController::class, 'updateProfile'])
        ->name('user.profile.update');

    Route::put('/user/password', [UserController::class, 'updatePassword'])
        ->name('user.password.update');
});

Route::middleware(['auth'])->group(function () {
    // Rutas AJAX para el perfil
    Route::get('/api/orders', [\App\Http\Controllers\Web\OrderController::class, 'getUserOrders']);
    Route::get('/api/invoices', [\App\Http\Controllers\Web\InvoiceController::class, 'getUserInvoices']);
    
    // Rutas de direcciones
    Route::get('/api/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::get('/addresses/{id}', [AddressController::class, 'show']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::put('/addresses/{id}/set-default', [AddressController::class, 'setDefault']);
});

Route::middleware(['auth'])->group(function () {
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');
        
    Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->name('orders.show');

});

Route::middleware(['auth'])->group(function () {
    Route::put('/orders/{order}/update-address', [OrderController::class, 'updateAddress']);
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
Route::get('/proteccion-datos', function () {
    return view('proteccion-datos');
})->name('proteccion-datos');

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

// Rutas de Wishlist
Route::middleware(['auth'])->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add/{book_id}', [WishlistController::class, 'add'])->name('add');
    Route::delete('/{id}', [WishlistController::class, 'remove'])->name('remove');
});

// Rutas de Facturas (requieren autenticación)
Route::middleware(['auth'])->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/{id}/download', [InvoiceController::class, 'download'])->name('download');
    Route::get('/{id}/preview', [InvoiceController::class, 'preview'])->name('preview');
});

// Solicitar factura para una orden
Route::middleware(['auth'])->post('/orders/{order_id}/request-invoice', [InvoiceController::class, 'requestInvoice'])->name('orders.request-invoice');

// Rutas de reseñas
Route::middleware(['auth'])->group(function () {
    Route::post('/libros/{book_id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Rutas de testing
Route::get('/test-redis-speed', function () {
    $start = microtime(true);
    $books1 = DB::table('books')->limit(10)->get();
    $time1 = (microtime(true) - $start) * 1000;

    $start = microtime(true);
    $books2 = Cache::remember('speed-test', 60, function () {
        return DB::table('books')->limit(10)->get();
    });
    $time2 = (microtime(true) - $start) * 1000;

    return response()->json([
        'sin_cache' => round($time1, 2) . ' ms',
        'con_cache' => round($time2, 2) . ' ms',
        'mejora' => $time2 > 0 ? round($time1 / $time2, 1) . 'x más rápido' : 'N/A',
        'libros' => $books2->count()
    ]);
});