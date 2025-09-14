<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los roles con sus usuarios asignados
            $roles = Role::with(['users'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Roles retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving roles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:50|unique:rols,nombre_rol'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create([
                'nombre_rol' => $request->role_name
            ]);

            $role->load(['users']);

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Role created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role): JsonResponse
    {
        try {
            $role->load(['users']);

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Role retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'sometimes|required|string|max:50|unique:rols,nombre_rol,' . $role->id_rol . ',id_rol'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update([
                'nombre_rol' => $request->role_name ?? $role->nombre_rol
            ]);

            $role->load(['users']);

            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Role updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role): JsonResponse
    {
        try {
            // Check if role has users assigned
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role. It has users assigned to it.'
                ], 400);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users with specific role
     */
    public function getUsers(Role $role): JsonResponse
    {
        try {
            $users = $role->users()->with(['customer', 'deliveryPerson'])->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users with role retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving users with role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign role to user
     */
    public function assignToUser(Request $request, Role $role): JsonResponse
    {
        // Validar que se envíe un ID de usuario válido
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id_usuario'
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
            // Buscar el usuario por ID
            $user = \App\Models\User::find($request->user_id);

            // Verificar si el usuario ya tiene este rol
            if ($user->roles()->where('id_rol', $role->id_rol)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has this role'
                ], 400);
            }

            // Asignar el rol al usuario
            $user->roles()->attach($role->id_rol);

            // Devolver respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Role assigned to user successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error assigning role to user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove role from user
     */
    public function removeFromUser(Request $request, Role $role): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id_usuario'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = \App\Models\User::find($request->user_id);
            $user->roles()->detach($role->id_rol);

            return response()->json([
                'success' => true,
                'message' => 'Role removed from user successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing role from user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get role statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $roles = Role::withCount('users')->get();

            $statistics = $roles->map(function ($role) {
                return [
                    'role_id' => $role->id_rol,
                    'role_name' => $role->nombre_rol,
                    'users_count' => $role->users_count
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Role statistics retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving role statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
