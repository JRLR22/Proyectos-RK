<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Actualizar perfil de usuario
     * PUT /api/user/profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
        ]);
        
        try {
            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Actualizar contraseña
     * PUT /api/user/password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ]);
        
        // Verificar contraseña actual
        if (!Hash::check($validated['current_password'], $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta',
            ], 400);
        }
        
        // Verificar que la nueva contraseña sea diferente
        if (Hash::check($validated['new_password'], $user->password_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'La nueva contraseña debe ser diferente a la actual',
            ], 400);
        }
        
        try {
            $user->update([
                'password_hash' => Hash::make($validated['new_password']),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la contraseña: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Obtener información del usuario actual
     * GET /api/user
     */
    public function show()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'user_id' => $user->user_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_admin' => $user->is_admin ?? false,
                'created_at' => $user->created_at->format('d/m/Y'),
                'member_since' => $user->created_at->format('F Y'),
            ],
        ]);
    }
}