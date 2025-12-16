@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6">
        Orden #{{ $order->order_number }}
    </h1>

    <!-- Estado -->
    <span class="px-3 py-1 rounded bg-{{ $order->status_color }}-100">
        {{ $order->status_label }}
    </span>

    <!-- Artículos -->
    <div class="mt-8 bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Artículos</h2>

        @foreach($order->items as $item)
            <div class="flex justify-between border-b py-3">
                <div>
                    <p class="font-medium">{{ $item->book->title }}</p>
                    <p class="text-sm text-gray-600">
                        Cantidad: {{ $item->quantity }}
                    </p>
                </div>
                <p>${{ number_format($item->price * $item->quantity, 2) }}</p>
            </div>
        @endforeach
    </div>

    <!-- Envío -->
@if($order->shippingMethod && $order->address)
    <div class="mt-6 bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-2">Envío</h2>

        <p>{{ $order->shippingMethod->name }}</p>
        <p>{{ $order->address->formatted_address }}</p>

        <p class="font-medium mt-2">
            ${{ number_format($order->shipping_cost, 2) }}
        </p>
    </div>
@else
    <div class="mt-6 bg-white rounded shadow p-6 text-gray-500">
        <h2 class="text-xl font-semibold mb-2">Envío</h2>
        <p>No aplica envío para esta orden</p>
    </div>
@endif


    <!-- Totales -->
    <div class="mt-6 bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Resumen</h2>

        <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
        <p>Impuestos: ${{ number_format($order->tax_amount, 2) }}</p>
        <p>Envío: ${{ number_format($order->shipping_cost, 2) }}</p>

        <p class="font-bold text-lg mt-2">
            Total: ${{ number_format($order->total, 2) }}
        </p>
    </div>

    <!-- Factura -->
    @if($order->invoice)
        <div class="mt-6">
            <a href="{{ route('invoices.download', $order->invoice->invoice_id) }}"
               class="text-blue-600 underline">
                Descargar factura
            </a>
        </div>
    @endif
</div>
@endsection
