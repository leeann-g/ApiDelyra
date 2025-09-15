<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los carritos
        $carts = Cart::all();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $carts,
            'message' => 'Carritos obtenidos correctamente'
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
        // Crear nuevo carrito
        $cart = Cart::create([
            'id_cliente' => $request->id_cliente,
            'direccion_envio' => $request->direccion_envio,
            'cantidad_items' => $request->cantidad_items,
            'envio_estimado' => $request->envio_estimado,
            'total' => $request->total,
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $cart,
            'message' => 'Carrito creado correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        // Devolver el carrito específico
        return response()->json([
            'success' => true,
            'data' => $cart,
            'message' => 'Carrito obtenido correctamente'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        // Actualizar el carrito
        $cart->update([
            'id_cliente' => $request->id_cliente,
            'direccion_envio' => $request->direccion_envio,
            'cantidad_items' => $request->cantidad_items,
            'envio_estimado' => $request->envio_estimado,
            'total' => $request->total,
        ]);

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $cart,
            'message' => 'Carrito actualizado correctamente'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        // Eliminar el carrito
        $cart->delete();

        // Devolver respuesta en JSON
        return response()->json([
            'success' => true,
            'message' => 'Carrito eliminado correctamente'
        ], 200);
    }
}
