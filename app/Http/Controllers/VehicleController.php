<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los vehículos con sus relaciones (domiciliario y usuario)
            $vehicles = Vehicle::with(['deliveryPerson.user'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'delivery_person_id' => 'required|exists:delivery_people,id_domiciliario',
            'license_plate' => 'required|string|max:10|unique:vehicles,placa',
            'vehicle_type' => 'required|string',
            'run_valid_until' => 'required|date|after:today',
            'insurance_valid_until' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicle = Vehicle::create([
                'id_domiciliario' => $request->delivery_person_id,
                'placa' => $request->license_plate,
                'tipo_vehiculo' => $request->vehicle_type,
                'run_vigente' => $request->run_valid_until,
                'seguro_vigente' => $request->insurance_valid_until
            ]);

            $vehicle->load(['deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => 'Vehicle created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        try {
            $vehicle->load(['deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => 'Vehicle retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, Vehicle $vehicle): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'delivery_person_id' => 'sometimes|required|exists:delivery_people,id_domiciliario',
            'license_plate' => 'sometimes|required|string|max:10|unique:vehicles,placa,' . $vehicle->id_vehiculo . ',id_vehiculo',
            'vehicle_type' => 'sometimes|required|string',
            'run_valid_until' => 'sometimes|required|date',
            'insurance_valid_until' => 'sometimes|required|date'
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
            if ($request->has('license_plate')) $updateData['placa'] = $request->license_plate;
            if ($request->has('vehicle_type')) $updateData['tipo_vehiculo'] = $request->vehicle_type;
            if ($request->has('run_valid_until')) $updateData['run_vigente'] = $request->run_valid_until;
            if ($request->has('insurance_valid_until')) $updateData['seguro_vigente'] = $request->insurance_valid_until;

            $vehicle->update($updateData);
            $vehicle->load(['deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $vehicle,
                'message' => 'Vehicle updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        try {
            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicles by delivery person
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
            $vehicles = Vehicle::where('id_domiciliario', $request->delivery_person_id)
                ->with(['deliveryPerson.user'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles retrieved by delivery person successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicles by delivery person: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicles with expired documents
     */
    public function getWithExpiredDocuments(): JsonResponse
    {
        try {
            // Obtener la fecha de hoy para comparar
            $today = now()->toDateString();

            // Buscar vehículos con documentos vencidos (RUN o seguro)
            $vehicles = Vehicle::where(function($query) use ($today) {
                $query->where('run_vigente', '<', $today)
                      ->orWhere('seguro_vigente', '<', $today);
            })->with(['deliveryPerson.user'])->get();

            // Devolver lista de vehículos con documentos vencidos
            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles with expired documents retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicles with expired documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicles expiring soon (within 30 days)
     */
    public function getExpiringSoon(): JsonResponse
    {
        try {
            $thirtyDaysFromNow = now()->addDays(30)->toDateString();
            $today = now()->toDateString();

            $vehicles = Vehicle::where(function($query) use ($today, $thirtyDaysFromNow) {
                $query->whereBetween('run_vigente', [$today, $thirtyDaysFromNow])
                      ->orWhereBetween('seguro_vigente', [$today, $thirtyDaysFromNow]);
            })->with(['deliveryPerson.user'])->get();

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles expiring soon retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicles expiring soon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicles by type
     */
    public function getByType(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehicles = Vehicle::where('tipo_vehiculo', $request->vehicle_type)
                ->with(['deliveryPerson.user'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles retrieved by type successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vehicles by type: ' . $e->getMessage()
            ], 500);
        }
    }
}
