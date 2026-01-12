<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Enviar email con token de reseteo
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'No existe una cuenta con este correo electrónico'
        ]);

        // Generar token único
        $token = Str::random(60);

        // Guardar token en la base de datos
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Obtener usuario
        $user = User::where('email', $request->email)->first();

        // Enviar email
        Mail::send('emails.password-reset', [
            'token' => $token,
            'email' => $request->email,
            'name' => $user->first_name
        ], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Restablecer Contraseña - Gonvill');
        });

        return response()->json([
            'success' => true,
            'message' => 'Te hemos enviado un correo con las instrucciones para restablecer tu contraseña'
        ]);
    }

    /**
     * Verificar si el token es válido
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ], 400);
        }

        // Verificar si el token es correcto
        if (!Hash::check($request->token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido'
            ], 400);
        }

        // Verificar si el token no ha expirado (60 minutos)
        if (now()->diffInMinutes($record->created_at) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'El token ha expirado. Solicita uno nuevo.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token válido'
        ]);
    }

    /**
     * Cambiar la contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ]);

        // Verificar el token
        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ], 400);
        }

        // Verificar expiración (60 minutos)
        if (now()->diffInMinutes($record->created_at) > 60) {
            return response()->json([
                'success' => false,
                'message' => 'El token ha expirado'
            ], 400);
        }

        // Actualizar contraseña
        $user = User::where('email', $request->email)->first();
        $user->password_hash = Hash::make($request->password);
        $user->save();

        // Eliminar el token usado
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function change(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Obtener usuario autenticado
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ], 400);
        }

        // Actualizar contraseña
        $user->password_hash = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }
}