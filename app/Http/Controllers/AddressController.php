<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Listar todas las direcciones del usuario
     * GET /api/addresses
     */
    public function index()
    {
        $user = Auth::user();
        
        $addresses = Address::where('user_id', $user->user_id)
                            ->orderBy('is_default', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses->map(function ($address) {
                return [
                    'address_id' => $address->address_id,
                    'recipient_name' => $address->recipient_name,
                    'phone' => $address->phone,
                    'street_address' => $address->street_address,
                    'apartment' => $address->apartment,
                    'neighborhood' => $address->neighborhood,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country' => $address->country,
                    'references' => $address->references,
                    'is_default' => $address->is_default,
                    'full_address' => $address->full_address,
                    'formatted_address' => $address->formatted_address,
                ];
            }),
        ]);
    }

    /**
     * Ver detalle de una dirección
     * GET /api/addresses/{id}
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $address = Address::where('user_id', $user->user_id)
                          ->findOrFail($id);

        return response()->json([
            'success' => true,
            'address' => [
                'address_id' => $address->address_id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'street_address' => $address->street_address,
                'apartment' => $address->apartment,
                'neighborhood' => $address->neighborhood,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
                'references' => $address->references,
                'is_default' => $address->is_default,
                'full_address' => $address->full_address,
                'formatted_address' => $address->formatted_address,
            ],
        ]);
    }

    /**
     * Crear nueva dirección
     * POST /api/addresses
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'references' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        
        // Si es la primera dirección, marcarla como predeterminada
        $isFirstAddress = !$user->hasAddresses();
        
        if ($isFirstAddress) {
            $validated['is_default'] = true;
        }

        $validated['user_id'] = $user->user_id;
        $validated['country'] = $validated['country'] ?? 'México';

        $address = Address::create($validated);

        // Si se marca como predeterminada, quitar default de las demás
        if ($address->is_default) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Dirección creada exitosamente',
            'address' => [
                'address_id' => $address->address_id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'full_address' => $address->full_address,
                'is_default' => $address->is_default,
            ],
        ], 201);
    }

    /**
     * Actualizar dirección
     * PUT /api/addresses/{id}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'recipient_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'street_address' => 'sometimes|required|string|max:255',
            'apartment' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:10',
            'country' => 'nullable|string|max:100',
            'references' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        
        $address = Address::where('user_id', $user->user_id)
                          ->findOrFail($id);

        $address->update($validated);

        // Si se marca como predeterminada, quitar default de las demás
        if (isset($validated['is_default']) && $validated['is_default']) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Dirección actualizada exitosamente',
            'address' => [
                'address_id' => $address->address_id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'full_address' => $address->full_address,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Eliminar dirección
     * DELETE /api/addresses/{id}
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $address = Address::where('user_id', $user->user_id)
                          ->findOrFail($id);

        // No permitir eliminar si es la única dirección
        if ($user->addresses()->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu única dirección. Agrega otra primero.',
            ], 400);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // Si era la predeterminada, marcar otra como default
        if ($wasDefault) {
            $newDefault = $user->addresses()->first();
            if ($newDefault) {
                $newDefault->setAsDefault();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Dirección eliminada exitosamente',
        ]);
    }

    /**
     * Marcar dirección como predeterminada
     * PUT /api/addresses/{id}/set-default
     */
    public function setDefault($id)
    {
        $user = Auth::user();
        
        $address = Address::where('user_id', $user->user_id)
                          ->findOrFail($id);

        $address->setAsDefault();

        return response()->json([
            'success' => true,
            'message' => 'Dirección marcada como predeterminada',
            'address' => [
                'address_id' => $address->address_id,
                'recipient_name' => $address->recipient_name,
                'full_address' => $address->full_address,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Obtener dirección predeterminada
     * GET /api/addresses/default
     */
    public function getDefault()
    {
        $user = Auth::user();
        
        $address = $user->getDefaultAddress();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes una dirección predeterminada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'address' => [
                'address_id' => $address->address_id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'full_address' => $address->full_address,
                'formatted_address' => $address->formatted_address,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Validar si una dirección está completa
     * GET /api/addresses/{id}/validate
     */
    public function validate($id)
    {
        $user = Auth::user();
        
        $address = Address::where('user_id', $user->user_id)
                          ->findOrFail($id);

        $isComplete = $address->isComplete();

        return response()->json([
            'success' => true,
            'is_complete' => $isComplete,
            'message' => $isComplete ? 'Dirección completa' : 'Faltan datos en la dirección',
        ]);
    }
}