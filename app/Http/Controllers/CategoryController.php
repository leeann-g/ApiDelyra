<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las categorías
        $categories = Category::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categorías obtenidas correctamente'
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
        // Crear una nueva categoría
        $category = Category::create([
            'nombre_categoria' => $request->nombre_categoria,
        ]);

        // Respuesta al crear
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Categoría creada correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Devolver una categoría específica
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Categoría obtenida correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Actualizar la categoría
        $category->update([
            'nombre_categoria' => $request->nombre_categoria,
        ]);

        // Respuesta al actualizar
        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Categoría actualizada correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Eliminar la categoría
        $category->delete();

        // Respuesta al eliminar
        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente'
        ], 200);
    }
}
