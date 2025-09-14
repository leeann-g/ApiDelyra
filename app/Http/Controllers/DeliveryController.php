<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las entregas con sus relaciones (pedido, cliente, usuario, domiciliario)
            $deliveries = Delivery::with(['order.customer.user', 'deliveryPerson.user'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $deliveries,
                'message' => 'Deliveries retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving deliveries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created delivery
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id_pedido|unique:deliveries,id_pedido',
            'delivery_person_id' => 'nullable|exists:delivery_people,id_domiciliario',
            'shipping_address' => 'required|string|max:255',
            'status' => 'boolean',
            'estimated_time' => 'nullable|date_format:H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $delivery = Delivery::create([
                'id_pedido' => $request->order_id,
                'id_domiciliario' => $request->delivery_person_id,
                'direccion_envio' => $request->shipping_address,
                'estado' => $request->status ?? false,
                'hora_estimada' => $request->estimated_time
            ]);

            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery): JsonResponse
    {
        try {
            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified delivery
     */
    public function update(Request $request, Delivery $delivery): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'delivery_person_id' => 'sometimes|nullable|exists:delivery_people,id_domiciliario',
            'shipping_address' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|boolean',
            'estimated_time' => 'sometimes|nullable|date_format:H:i:s'
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

            if ($request->has('delivery_person_id')) $updateData['id_domiciliario'] = $request->delivery_person_id;
            if ($request->has('shipping_address')) $updateData['direccion_envio'] = $request->shipping_address;
            if ($request->has('status')) $updateData['estado'] = $request->status;
            if ($request->has('estimated_time')) $updateData['hora_estimada'] = $request->estimated_time;

            $delivery->update($updateData);
            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified delivery
     */
    public function destroy(Delivery $delivery): JsonResponse
    {
        try {
            $delivery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Delivery deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign delivery person to delivery
     */
    public function assignDeliveryPerson(Request $request, Delivery $delivery): JsonResponse
    {
        // Validar que se envíe un ID de domiciliario válido
        $validator = Validator::make($request->all(), [
            'delivery_person_id' => 'required|exists:delivery_people,id_domiciliario'
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
            // Verificar si el domiciliario está disponible
            $deliveryPerson = DeliveryPerson::find($request->delivery_person_id);
            if (!$deliveryPerson->estado_dis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery person is not available'
                ], 400);
            }

            // Asignar el domiciliario a la entrega
            $delivery->update(['id_domiciliario' => $request->delivery_person_id]);
            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            // Devolver respuesta exitosa con la entrega actualizada
            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery person assigned successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error assigning delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update delivery status
     */
    public function updateStatus(Request $request, Delivery $delivery): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $delivery->update(['estado' => $request->status]);
            $delivery->load(['order.customer.user', 'deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $delivery,
                'message' => 'Delivery status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating delivery status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get deliveries by delivery person
     */
    public function getByDeliveryPerson(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'delivery_person_id' => 'required|exists:delivery_people,id_domiciliario'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deliveries = Delivery::where('id_domiciliario', $request->delivery_person_id)
                ->with(['order.customer.user', 'deliveryPerson.user'])
                ->orderBy('fecha_entrega', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $deliveries,
                'message' => 'Deliveries by delivery person retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving deliveries by delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending deliveries (without delivery person assigned)
     */
    public function getPending(): JsonResponse
    {
        try {
            // Buscar entregas que no tienen domiciliario asignado (id_domiciliario es null)
            $pendingDeliveries = Delivery::whereNull('id_domiciliario')
                ->with(['order.customer.user'])
                ->orderBy('fecha_entrega', 'asc')
                ->get();

            // Devolver lista de entregas pendientes
            return response()->json([
                'success' => true,
                'data' => $pendingDeliveries,
                'message' => 'Pending deliveries retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving pending deliveries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get completed deliveries
     */
    public function getCompleted(): JsonResponse
    {
        try {
            $completedDeliveries = Delivery::where('estado', true)
                ->with(['order.customer.user', 'deliveryPerson.user'])
                ->orderBy('fecha_entrega', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $completedDeliveries,
                'message' => 'Completed deliveries retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving completed deliveries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get deliveries by date range
     */
    public function getByDateRange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $deliveries = Delivery::whereBetween('fecha_entrega', [$request->start_date, $request->end_date])
                ->with(['order.customer.user', 'deliveryPerson.user'])
                ->orderBy('fecha_entrega', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $deliveries,
                'message' => 'Deliveries by date range retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving deliveries by date range: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $totalDeliveries = Delivery::count();
            $completedDeliveries = Delivery::where('estado', true)->count();
            $pendingDeliveries = Delivery::where('estado', false)->count();
            $unassignedDeliveries = Delivery::whereNull('id_domiciliario')->count();

            $statistics = [
                'total_deliveries' => $totalDeliveries,
                'completed_deliveries' => $completedDeliveries,
                'pending_deliveries' => $pendingDeliveries,
                'unassigned_deliveries' => $unassignedDeliveries,
                'completion_rate' => $totalDeliveries > 0 ? round(($completedDeliveries / $totalDeliveries) * 100, 2) : 0
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Delivery statistics retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving delivery statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
