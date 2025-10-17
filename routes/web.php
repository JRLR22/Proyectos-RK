<?php

use App\Http\Controllers\Controller;

Route::get('/', [Controller::class, 'welcome']);
Route::get('/login', [Controller::class, 'login']);
Route::get('/categorias', [Controller::class, 'categorias']);


