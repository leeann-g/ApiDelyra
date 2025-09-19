<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los vehículos
        $vehicles = Vehicle::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $vehicles,
            'message' => 'Vehículos obtenidos correctamente'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Crear nuevo vehículo
        $vehicle = Vehicle::create([
            'id_domiciliario' => $request->id_domiciliario,
            'placa' => $request->placa,
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'run_vigente' => $request->run_vigente,
            'seguro_vigente' => $request->seguro_vigente
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehículo creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        // Devolver el vehículo específico
        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehículo obtenido correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // Actualizar el vehículo
        $vehicle->update([
            'id_domiciliario' => $request->id_domiciliario,
            'placa' => $request->placa,
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'run_vigente' => $request->run_vigente,
            'seguro_vigente' => $request->seguro_vigente
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehículo actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Eliminar el vehículo
        $vehicle->delete();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'message' => 'Vehículo eliminado correctamente'
        ], 200);
    }
}
