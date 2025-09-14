<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los pedidos con sus relaciones (cliente, usuario, entrega, domiciliario)
            $orders = Order::with(['customer.user', 'delivery.deliveryPerson.user'])->get();

            // Devolver respuesta exitosa con los datos
            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id_cliente',
            'order_date' => 'required|date',
            'status' => 'required|string|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'total' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order = Order::create([
                'id_cliente' => $request->customer_id,
                'fecha_pedido' => $request->order_date,
                'estado' => $request->status,
                'total' => $request->total
            ]);

            $order->load(['customer.user', 'delivery']);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): JsonResponse
    {
        try {
            $order->load(['customer.user', 'delivery.deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|string|in:pending,confirmed,preparing,ready,delivered,cancelled',
            'total' => 'sometimes|required|numeric|min:0'
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

            if ($request->has('status')) $updateData['estado'] = $request->status;
            if ($request->has('total')) $updateData['total'] = $request->total;

            $order->update($updateData);
            $order->load(['customer.user', 'delivery.deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order): JsonResponse
    {
        try {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders by customer
     */
    public function getByCustomer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id_cliente'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $orders = Order::where('id_cliente', $request->customer_id)
                ->with(['customer.user', 'delivery.deliveryPerson.user'])
                ->orderBy('fecha_pedido', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Customer orders retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving customer orders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders by status
     */
    public function getByStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $orders = Order::where('estado', $request->status)
                ->with(['customer.user', 'delivery.deliveryPerson.user'])
                ->orderBy('fecha_pedido', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders by status retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving orders by status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders by date range
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
            $orders = Order::whereBetween('fecha_pedido', [$request->start_date, $request->end_date])
                ->with(['customer.user', 'delivery.deliveryPerson.user'])
                ->orderBy('fecha_pedido', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders by date range retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving orders by date range: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order->update(['estado' => $request->status]);
            $order->load(['customer.user', 'delivery.deliveryPerson.user']);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            // Contar total de pedidos
            $totalOrders = Order::count();
            // Contar pedidos por estado
            $pendingOrders = Order::where('estado', 'pending')->count();
            $confirmedOrders = Order::where('estado', 'confirmed')->count();
            $preparingOrders = Order::where('estado', 'preparing')->count();
            $readyOrders = Order::where('estado', 'ready')->count();
            $deliveredOrders = Order::where('estado', 'delivered')->count();
            $cancelledOrders = Order::where('estado', 'cancelled')->count();
            // Calcular ingresos totales de pedidos entregados
            $totalRevenue = Order::where('estado', 'delivered')->sum('total');

            // Crear array con todas las estadísticas
            $statistics = [
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'confirmed_orders' => $confirmedOrders,
                'preparing_orders' => $preparingOrders,
                'ready_orders' => $readyOrders,
                'delivered_orders' => $deliveredOrders,
                'cancelled_orders' => $cancelledOrders,
                'total_revenue' => $totalRevenue,
                // Calcular porcentaje de entregas exitosas (evitar división por cero)
                'delivery_rate' => $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0
            ];

            // Devolver estadísticas de pedidos
            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Order statistics retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            // Si hay error, devolver mensaje de error
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving order statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
