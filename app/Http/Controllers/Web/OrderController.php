<?php

namespace App\Http\Controllers\Web;

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

}
