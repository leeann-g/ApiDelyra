<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las sucursales
        $branches = Branch::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $branches,
            'message' => 'Sucursales obtenidas correctamente'
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
        // Crear una nueva sucursal
        $branch = Branch::create([
            'id_comerciante' => $request->id_comerciante,
            'nombre_sucursal' => $request->nombre_sucursal,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        // Respuesta al crear
        return response()->json([
            'success' => true,
            'data' => $branch,
            'message' => 'Sucursal creada correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        // Devolver una sucursal específica
        return response()->json([
            'success' => true,
            'data' => $branch,
            'message' => 'Sucursal obtenida correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        // Actualizar la sucursal
        $branch->update([
            'id_comerciante' => $request->id_comerciante,
            'nombre_sucursal' => $request->nombre_sucursal,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        // Respuesta al actualizar
        return response()->json([
            'success' => true,
            'data' => $branch,
            'message' => 'Sucursal actualizada correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        // Eliminar la sucursal
        $branch->delete();

        // Respuesta al eliminar
        return response()->json([
            'success' => true,
            'message' => 'Sucursal eliminada correctamente'
        ], 200);
    }
}
