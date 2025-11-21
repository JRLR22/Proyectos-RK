<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', [PageController::class, 'inicio'])->name('inicio');

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

// Rutas de carrito y favoritos
Route::middleware(['auth'])->group(function () {
    Route::get('/mi-compra', [CartController::class, 'miCompra'])->name('mi.compra');
    Route::get('/favoritos', [CartController::class, 'favoritos'])->name('favoritos');
    Route::post('/carrito/agregar', [CartController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
});

// Rutas de autenticación (si usas Laravel Breeze/Jetstream, estas ya vienen incluidas)
Route::get('/mi-cuenta', function () {
    return view('auth.login');
})->name('mi.cuenta');