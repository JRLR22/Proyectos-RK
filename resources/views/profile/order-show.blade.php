@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Orden #{{ $order->order_number }}
        </h1>
        <a href="{{ route('profile') }}" class="text-blue-600 hover:underline">
            ← Volver a mis pedidos
        </a>
    </div>

    <!-- Estado y fecha -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                    {{ $order->status_label }}
                </span>
                <p class="text-sm text-gray-600 mt-2">
                    Pedido realizado el {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            
            @if($order->invoice)
                <a href="{{ route('invoices.show', $order->invoice->invoice_id) }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    📄 Ver Factura
                </a>
            @endif
        </div>
    </div>

    <!-- Artículos -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Artículos</h2>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex gap-4 border-b pb-4 last:border-b-0">
                    @if($item->book->cover_url)
                        <img src="{{ $item->book->cover_url }}" 
                             alt="{{ $item->book->title }}" 
                             class="w-16 h-24 object-cover rounded">
                    @endif
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item->book->title }}</p>
                        <p class="text-sm text-gray-600">{{ $item->book->authors_list ?? 'Sin autor' }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Cantidad: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                        </p>
                    </div>
                    <p class="font-semibold text-gray-900">
                        ${{ number_format($item->price * $item->quantity, 2) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información de envío -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Dirección de Envío</h2>
            
            @if($order->address)
                <div class="text-gray-700">
                    <p class="font-medium">{{ $order->address->recipient_name }}</p>
                    <p class="text-sm">{{ $order->address->phone }}</p>
                    <p class="text-sm mt-2">{{ $order->address->street_address }}</p>
                    @if($order->address->apartment)
                        <p class="text-sm">{{ $order->address->apartment }}</p>
                    @endif
                    <p class="text-sm">
                        {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}
                    </p>
                    <p class="text-sm">{{ $order->address->country }}</p>
                    
                    @if($order->address->references)
                        <p class="text-sm text-gray-500 mt-2">
                            <span class="font-medium">Referencias:</span> {{ $order->address->references }}
                        </p>
                    @endif
                </div>
            @else
                <p class="text-gray-500">No se especificó dirección de envío</p>
            @endif

            @if($order->tracking_number)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm font-medium text-gray-700">Número de rastreo:</p>
                    <p class="text-sm text-blue-600 font-mono">{{ $order->tracking_number }}</p>
                </div>
            @endif
        </div>

        <!-- Resumen de pago -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Resumen</h2>

            <div class="space-y-2 text-gray-700">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Descuento:</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between">
                    <span>Envío:</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>

                <div class="flex justify-between">
                    <span>IVA (16%):</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>

                <div class="flex justify-between pt-3 border-t font-bold text-lg">
                    <span>Total:</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Método de pago -->
            <div class="mt-6 pt-6 border-t">
                <h3 class="font-semibold text-gray-900 mb-2">Método de pago</h3>
                @if($order->payment_method)
                    <div class="flex items-center gap-2">
                        @if($order->payment_method === 'stripe')
                            <i class="fas fa-credit-card text-blue-600"></i>
                            <span class="text-gray-700">Tarjeta de crédito/débito (Stripe)</span>
                        @elseif($order->payment_method === 'paypal')
                            <i class="fab fa-paypal text-blue-700"></i>
                            <span class="text-gray-700">PayPal</span>
                        @else
                            <span class="text-gray-700">{{ ucfirst($order->payment_method) }}</span>
                        @endif
                    </div>
                    
                    <div class="mt-2">
                        <span class="text-sm px-3 py-1 rounded-full 
                            {{ $order->payment_status === 'completado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No especificado</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancelación -->
    @if($order->can_be_cancelled)
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('orders.cancel', $order->order_id) }}" 
                  onsubmit="return confirm('¿Estás seguro de que deseas cancelar este pedido?')">
                @csrf
                @method('PUT')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">
                    Cancelar Pedido
                </button>
            </form>
        </div>
    @endif

    @if($order->cancelled_at)
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="font-semibold text-red-800 mb-2">Pedido Cancelado</h3>
            <p class="text-sm text-red-700">
                Fecha: {{ $order->cancelled_at->format('d/m/Y H:i') }}
            </p>
            @if($order->cancellation_reason)
                <p class="text-sm text-red-700 mt-1">
                    Motivo: {{ $order->cancellation_reason }}
                </p>
            @endif
        </div>
    @endif
</div>
@endsection