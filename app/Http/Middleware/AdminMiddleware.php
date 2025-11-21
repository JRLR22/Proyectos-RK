<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('mi.cuenta')->with('error', 'Debes iniciar sesión');
        }

        if (!Auth::user()->is_admin) {
            return redirect()->route('inicio')->with('error', 'No tienes permisos de administrador');
        }

        return $next($request);
    }
}