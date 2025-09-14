<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DeliveryPersonController extends Controller
{
    /**
     * Display a listing of delivery people
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los domiciliarios con sus relaciones (usuario, vehículos, entregas)
            $deliveryPeople = DeliveryPerson::with(['user', 'vehicles', 'deliveries.order.customer.user'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $deliveryPeople,
                'message' => 'Delivery people retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery people: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created delivery person
     */
    public function store(Request $request): JsonResponse
    {
        // Validar que los datos enviados sean correctos
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id_usuario|unique:delivery_people,id_usuario',
            'availability_status' => 'boolean'
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
            // Crear nuevo domiciliario en la base de datos
            $deliveryPerson = DeliveryPerson::create([
                'id_usuario' => $request->user_id,
                'estado_dis' => $request->availability_status ?? false
            ]);

            // Cargar las relaciones del domiciliario creado
            $deliveryPerson->load(['user', 'vehicles', 'deliveries']);

            // Devolver respuesta exitosa con los datos del domiciliario creado
            return response()->json([
                'success' => true,
                'data' => $deliveryPerson,
                'message' => 'Delivery person created successfully'
            ], 201);
        } catch (\Exception $e) {
            // Si hay error al crear, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error creating delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified delivery person
     */
    public function show(DeliveryPerson $deliveryPerson): JsonResponse
    {
        try {
            $deliveryPerson->load(['user', 'vehicles', 'deliveries.order.customer.user']);

            return response()->json([
                'success' => true,
                'data' => $deliveryPerson,
                'message' => 'Delivery person retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified delivery person
     */
    public function update(Request $request, DeliveryPerson $deliveryPerson): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'availability_status' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deliveryPerson->update([
                'estado_dis' => $request->availability_status ?? $deliveryPerson->estado_dis
            ]);

            $deliveryPerson->load(['user', 'vehicles', 'deliveries']);

            return response()->json([
                'success' => true,
                'data' => $deliveryPerson,
                'message' => 'Delivery person updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified delivery person
     */
    public function destroy(DeliveryPerson $deliveryPerson): JsonResponse
    {
        try {
            $deliveryPerson->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delivery person deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available delivery people
     */
    public function getAvailable(): JsonResponse
    {
        try {
            // Buscar solo los domiciliarios que están disponibles (estado_dis = true)
            $availableDeliveryPeople = DeliveryPerson::where('estado_dis', true)
                ->with(['user', 'vehicles'])
                ->get();

            // Devolver lista de domiciliarios disponibles
            return response()->json([
                'success' => true,
                'data' => $availableDeliveryPeople,
                'message' => 'Available delivery people retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available delivery people: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update availability status
     */
    public function updateAvailability(Request $request, DeliveryPerson $deliveryPerson): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'availability_status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deliveryPerson->update([
                'estado_dis' => $request->availability_status
            ]);

            return response()->json([
                'success' => true,
                'data' => $deliveryPerson,
                'message' => 'Availability status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating availability status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery person by user ID
     */
    public function getByUserId(Request $request): JsonResponse
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
            $deliveryPerson = DeliveryPerson::where('id_usuario', $request->user_id)
                ->with(['user', 'vehicles', 'deliveries.order.customer.user'])
                ->first();

            if (!$deliveryPerson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery person not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $deliveryPerson,
                'message' => 'Delivery person retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery person statistics
     */
    public function getStatistics(DeliveryPerson $deliveryPerson): JsonResponse
    {
        try {
            // Contar total de entregas del domiciliario
            $totalDeliveries = $deliveryPerson->deliveries()->count();
            // Contar entregas completadas (estado = true)
            $completedDeliveries = $deliveryPerson->deliveries()->where('estado', true)->count();
            // Contar entregas pendientes (estado = false)
            $pendingDeliveries = $deliveryPerson->deliveries()->where('estado', false)->count();

            // Calcular estadísticas del domiciliario
            $statistics = [
                'total_deliveries' => $totalDeliveries,
                'completed_deliveries' => $completedDeliveries,
                'pending_deliveries' => $pendingDeliveries,
                // Calcular porcentaje de completitud (evitar división por cero)
                'completion_rate' => $totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100, 2) : 0
            ];

            // Devolver estadísticas del domiciliario
            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Delivery person statistics retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery person statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
