<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Illuminate\Http\Request;

class TraderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los comerciantes
        $traders = Trader::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $traders,
            'message' => 'Comerciantes obtenidos correctamente'
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
        // Crear nuevo comerciante
        $trader = Trader::create([
            'id_usuario' => $request->id_usuario,
            'id_rol' => $request->id_rol,
            'nombre_local' => $request->nombre_local,
            'cuenta_bancaria' => $request->cuenta_bancaria,
            'nit' => $request->nit,
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $trader,
            'message' => 'Comerciante creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trader $trader)
    {
        // Devolver el comerciante específico
        return response()->json([
            'success' => true,
            'data' => $trader,
            'message' => 'Comerciante obtenido correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trader $trader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trader $trader)
    {
        // Actualizar el comerciante
        $trader->update([
            'id_usuario' => $request->id_usuario,
            'id_rol' => $request->id_rol,
            'nombre_local' => $request->nombre_local,
            'cuenta_bancaria' => $request->cuenta_bancaria,
            'nit' => $request->nit,
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $trader,
            'message' => 'Comerciante actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trader $trader)
    {
        // Eliminar el comerciante
        $trader->delete();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'message' => 'Comerciante eliminado correctamente'
        ], 200);
    }
}
