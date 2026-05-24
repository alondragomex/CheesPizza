<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Ordenar por tiempo (FIFO): el primer pedido en entrar es el primero en ser atendido
        $orders = Order::with('items.product')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Métricas básicas para lucirse en la presentación
        $pendingCount = Order::where('status', 'pending')->count();
        $preparingCount = Order::where('status', 'preparing')->count();
        $deliveredCount = Order::where('status', 'delivered')->count();
        $totalEarnings = Order::where('status', 'delivered')->sum('total'); // Ganancias de entregas completadas

        // Obtener clientes registrados
        $customers = \App\Models\Customer::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('orders', 'pendingCount', 'preparingCount', 'deliveredCount', 'totalEarnings', 'customers'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.dashboard')->with('success', '¡El estado del pedido #' . $order->id . ' ha sido actualizado a "' . $request->status . '"!');
    }

    public function corteCaja()
    {
        \Illuminate\Support\Facades\DB::transaction(function() {
            // Eliminar todas las órdenes actuales (los ítems de la orden se eliminan en cascada)
            Order::query()->delete();
            
            // Reiniciar el contador autoincremental de la tabla de órdenes
            $driver = \Illuminate\Support\Facades\DB::getDriverName();
            if ($driver === 'sqlite') {
                \Illuminate\Support\Facades\DB::statement("DELETE FROM sqlite_sequence WHERE name='orders'");
            } elseif ($driver === 'mysql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders AUTO_INCREMENT = 1");
            } elseif ($driver === 'pgsql') {
                \Illuminate\Support\Facades\DB::statement("ALTER SEQUENCE orders_id_seq RESTART WITH 1");
            }
        });

        return redirect()->route('admin.dashboard')->with('success', '¡Corte de Caja realizado con éxito! Se han limpiado las órdenes activas, el número de pedido se ha reiniciado a 1 y las métricas han vuelto a $0.00 para iniciar el nuevo turno.');
    }

    public function destroy(Order $order)
    {
        // Cancelación de órdenes (solo si está pendiente)
        if ($order->status !== 'pending') {
            return back()->withErrors(['error' => 'Solo se pueden cancelar pedidos que estén en estado pendiente.']);
        }

        $order->delete();
        return redirect()->route('admin.dashboard')->with('success', '¡El pedido #' . $order->id . ' ha sido cancelado con éxito!');
    }
}
