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
        // Validar que los datos enviados sean correctos
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:20',
            'second_name' => 'nullable|string|max:20',
            'first_lastname' => 'required|string|max:20',
            'second_lastname' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,correo|max:255',
            'password' => 'required|string|min:8|max:255',
            'phone' => 'required|string|max:10',
            'roles' => 'required|array',
            'roles.*' => 'exists:rols,id_rol'
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Crear nuevo usuario en la base de datos
            $user = User::create([
                'primer_nombre' => $request->first_name,
                'segundo_nombre' => $request->second_name,
                'primer_apellido' => $request->first_lastname,
                'segundo_apellido' => $request->second_lastname,
                'correo' => $request->email,
                'contrasena_hash' => Hash::make($request->password),
                'telefono' => $request->phone
            ]);

            // Asignar roles al usuario
            $user->roles()->attach($request->roles);

            // Cargar las relaciones del usuario creado
            $user->load(['customer', 'deliveryPerson', 'roles']);

            // Devolver respuesta exitosa con los datos del usuario creado
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User created successfully'
            ], 201);
        } catch (\Exception $e) {
            // Si hay error al crear, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
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
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:20',
            'second_name' => 'nullable|string|max:20',
            'first_lastname' => 'sometimes|required|string|max:20',
            'second_lastname' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:users,correo,' . $user->id_usuario . ',id_usuario|max:255',
            'password' => 'sometimes|required|string|min:8|max:255',
            'phone' => 'sometimes|required|string|max:10',
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:rols,id_rol'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [];

            if ($request->has('first_name')) $updateData['primer_nombre'] = $request->first_name;
            if ($request->has('second_name')) $updateData['segundo_nombre'] = $request->second_name;
            if ($request->has('first_lastname')) $updateData['primer_apellido'] = $request->first_lastname;
            if ($request->has('second_lastname')) $updateData['segundo_apellido'] = $request->second_lastname;
            if ($request->has('email')) $updateData['correo'] = $request->email;
            if ($request->has('password')) $updateData['contrasena_hash'] = Hash::make($request->password);
            if ($request->has('phone')) $updateData['telefono'] = $request->phone;

            $user->update($updateData);

            // Update roles if provided
            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }

            $user->load(['customer', 'deliveryPerson', 'roles']);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function getByRole(Request $request): JsonResponse
    {
        // Validar que se envíe un ID de rol válido
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:rols,id_rol'
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buscar usuarios que tengan el rol especificado
            $users = User::whereHas('roles', function($query) use ($request) {
                $query->where('id_rol', $request->role_id);
            })->with(['customer', 'deliveryPerson', 'roles'])->get();

            // Devolver lista de usuarios con el rol especificado
            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved by role successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users by role: ' . $e->getMessage()
            ], 500);
        }
    }
}
