<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{
    /**
     * Mostrar formulario de login/register
     */
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al inicio
        if (Auth::check()) {
            return redirect()->route('profile');
        }
        
        return view('micuenta');
    }

    /**
     * Procesar registro de nuevo usuario
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ], [
                'first_name.required' => 'El nombre es requerido',
                'last_name.required' => 'El apellido es requerido',
                'email.required' => 'El email es requerido',
                'email.email' => 'Formato de email inválido',
                'email.unique' => 'Este email ya está registrado',
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
            ]);

            // Crear usuario
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // Iniciar sesión automáticamente
            Auth::login($user);

            return redirect()->route('inicio')->with('success', '¡Cuenta creada exitosamente! Bienvenido ' . $user->first_name);

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()->withErrors([
                'email' => 'Las credenciales son incorrectas.',
            ])->withInput();
        }

        // Autenticar al usuario
        Auth::login($user);

        // Redirigir según el rol
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard'); 
        }

        return redirect()->route('profile');
    }

    /**
     * Mostrar perfil del usuario
     */
    public function showProfile()
    {
        return view('profile');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $isAdmin = auth()->user()->is_admin ?? false;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Sesión cerrada correctamente');
    }
}