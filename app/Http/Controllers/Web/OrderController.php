<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.book', 'address', 'shippingMethod', 'invoice'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('profile.order-show', compact('order'));
    }

    public function cancel($orderId)
{
    $order = Order::where('order_id', $orderId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if (!$order->can_be_cancelled) {
        return back()->with('error', 'Esta orden no puede cancelarse');
    }

    $order->cancel();

    return back()->with('success', 'Orden cancelada correctamente');
}

public function updateAddress(Request $request, $orderId)
{
    $request->validate([
        'address_id' => 'required|exists:addresses,address_id'
    ]);
    
    $order = Order::where('order_id', $orderId)
        ->where('user_id', Auth::id())
        ->where('status', 'pendiente')
        ->firstOrFail();
    
    $order->update(['address_id' => $request->address_id]);
    
    return response()->json(['success' => true]);
}


    /**
     * Obtener pedidos del usuario en JSON
     * GET /api/orders
     */
    public function getUserOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'status_color' => $order->status_color,
                    'total_items' => $order->total_items,
                    'can_be_cancelled' => $order->can_be_cancelled,
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                ];
            })
        ]);
    }
}
