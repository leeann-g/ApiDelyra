<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los stocks de productos
        $productStocks = ProductStock::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $productStocks,
            'message' => 'Stocks de producto obtenidos correctamente'
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
        // Crear nuevo stock de producto
        $productStock = ProductStock::create([
            'id_producto' => $request->id_producto,
            'id_sucursal' => $request->id_sucursal,
            'cantidad_disponible' => $request->cantidad_disponible,
            'cantidad_minima' => $request->cantidad_minima,
            'fecha_actualizacion' => $request->fecha_actualizacion
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $productStock,
            'message' => 'Stock de producto creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStock $productStock)
    {
        // Devolver el stock de producto específico
        return response()->json([
            'success' => true,
            'data' => $productStock,
            'message' => 'Stock de producto obtenido correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductStock $productStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductStock $productStock)
    {
        // Actualizar el stock de producto
        $productStock->update([
            'id_producto' => $request->id_producto,
            'id_sucursal' => $request->id_sucursal,
            'cantidad_disponible' => $request->cantidad_disponible,
            'cantidad_minima' => $request->cantidad_minima,
            'fecha_actualizacion' => $request->fecha_actualizacion
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $productStock,
            'message' => 'Stock de producto actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductStock $productStock)
    {
        // Eliminar el stock de producto
        $productStock->delete();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'message' => 'Stock de producto eliminado correctamente'
        ], 200);
    }
}
