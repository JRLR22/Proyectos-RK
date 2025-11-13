<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
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

// Rutas de libros
Route::prefix('libros')->name('libros.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/busqueda-avanzada', [BookController::class, 'busquedaAvanzada'])->name('busqueda.avanzada');
    Route::get('/{id}', [BookController::class, 'show'])->name('show');
    Route::post('/buscar', [BookController::class, 'buscar'])->name('buscar');
});

// Rutas de carrito y favoritos (protegidas con autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/mi-compra', [CartController::class, 'miCompra'])->name('mi.compra');
    Route::get('/favoritos', [CartController::class, 'favoritos'])->name('favoritos');
    Route::post('/carrito/agregar', [CartController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
});

// Ruta hacia Politicas de seguridad 
Route::get('/proteccion-datos', function () {
    return view('proteccion-datos');
})->name('proteccion-datos');