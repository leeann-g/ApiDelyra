<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(): JsonResponse
    {
        // Obtener todos los clientes con sus relaciones
        $customers = Customer::with(['user', 'orders'])->get();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $customers,
            'message' => 'Clientes obtenidos correctamente'
        ], 200);
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request): JsonResponse
    {
        // Crear nuevo cliente (simple)
        $customer = Customer::create([
            'id_usuario' => $request->user_id,
            'direccion_envio' => $request->shipping_address
        ]);

        $customer->load(['user', 'orders']);

        return response()->json([
            'success' => true,
            'data' => $customer,
            'message' => 'Cliente creado correctamente'
        ], 201);
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer): JsonResponse
    {
        // Cargar relaciones básicas
        $customer->load(['user', 'orders.delivery']);

        return response()->json([
            'success' => true,
            'data' => $customer,
            'message' => 'Cliente obtenido correctamente'
        ], 200);
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        // Actualizar cliente
        $customer->update([
            'direccion_envio' => $request->shipping_address ?? $customer->direccion_envio
        ]);

        $customer->load(['user', 'orders']);

        return response()->json([
            'success' => true,
            'data' => $customer,
            'message' => 'Cliente actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer): JsonResponse
    {
        // Eliminar cliente
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente'
        ], 200);
    }

    // Métodos especiales se omiten por ahora para mantenerlo simple
}
