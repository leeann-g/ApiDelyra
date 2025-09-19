<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los productos
        $products = Product::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Productos obtenidos correctamente'
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
        // Crear un nuevo producto (por ahora sin validaciones avanzadas)
        $product = Product::create([
            'id_categoria' => $request->id_categoria,
            'id_comerciante' => $request->id_comerciante,
            'nombre_producto' => $request->nombre_producto,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
        ]);

        // Respuesta al crear
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Producto creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Devolver un producto específico
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Producto obtenido correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Actualizar el producto
        $product->update([
            'id_categoria' => $request->id_categoria,
            'id_comerciante' => $request->id_comerciante,
            'nombre_producto' => $request->nombre_producto,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
        ]);

        // Respuesta al actualizar
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Producto actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Eliminar el producto
        $product->delete();

        // Respuesta al eliminar
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
