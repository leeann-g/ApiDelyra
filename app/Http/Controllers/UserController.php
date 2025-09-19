<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los usuarios con sus relaciones (cliente, domiciliario, roles)
            $users = User::with(['customer', 'deliveryPerson', 'roles'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request): JsonResponse
    {
        // Crear nuevo usuario
        $user = User::create([
            'primer_nombre' => $request->first_name,
            'segundo_nombre' => $request->second_name,
            'primer_apellido' => $request->first_lastname,
            'segundo_apellido' => $request->second_lastname,
            'correo' => $request->email,
            'contrasena_hash' => Hash::make($request->password),
            'telefono' => $request->phone
        ]);

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        try {
            $user->load(['customer', 'deliveryPerson', 'roles']);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user): JsonResponse
    {
        // Actualizar el usuario
        $user->update([
            'primer_nombre' => $request->first_name,
            'segundo_nombre' => $request->second_name,
            'primer_apellido' => $request->first_lastname,
            'segundo_apellido' => $request->second_lastname,
            'correo' => $request->email,
            'telefono' => $request->phone
        ]);

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User updated successfully'
        ], 200);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        // Eliminar el usuario
        $user->delete();

        // Devolver respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
}
