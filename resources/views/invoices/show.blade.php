@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header con botones de acción -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:underline">
                ← Volver a mis facturas
            </a>
            <div class="flex gap-2">
                <a 
                    href="{{ route('invoices.download', $invoice->invoice_id) }}" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    📥 Descargar PDF
                </a>
                <button 
                    onclick="window.print()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    🖨️ Imprimir
                </button>
            </div>
        </div>

        <!-- Factura -->
        <div class="bg-white rounded-lg shadow-lg p-8 print:shadow-none">
            <!-- Logo y título -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">FACTURA</h1>
                    <p class="text-gray-600">Librerías Gonvill</p>
                    <p class="text-sm text-gray-500">RFC: GON123456ABC</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-blue-600">{{ $invoice->invoice_number }}</p>
                    <p class="text-sm text-gray-600">Fecha: {{ $invoice->issue_date->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Información del cliente y orden -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-2">FACTURADO A:</h3>
                    <p class="font-semibold">{{ $invoice->order->user->full_name }}</p>
                    <p class="text-sm text-gray-600">{{ $invoice->order->user->email }}</p>
                    @if($invoice->order->user->phone)
                        <p class="text-sm text-gray-600">Tel: {{ $invoice->order->user->phone }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-2">DIRECCIÓN DE ENVÍO:</h3>
                    @if($invoice->order->address)
                        <p class="text-sm text-gray-600">{{ $invoice->order->address->recipient_name }}</p>
                        <p class="text-sm text-gray-600">{{ $invoice->order->address->street_address }}</p>
                        @if($invoice->order->address->apartment)
                            <p class="text-sm text-gray-600">{{ $invoice->order->address->apartment }}</p>
                        @endif
                        <p class="text-sm text-gray-600">
                            {{ $invoice->order->address->city }}, {{ $invoice->order->address->state }} {{ $invoice->order->address->postal_code }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Información de la orden -->
            <div class="bg-gray-50 rounded p-4 mb-8">
                <p class="text-sm">
                    <span class="font-semibold">Orden:</span> {{ $invoice->order->order_number }} | 
                    <span class="font-semibold">Fecha de compra:</span> {{ $invoice->order->created_at->format('d/m/Y') }} |
                    <span class="font-semibold">Método de pago:</span> {{ ucfirst($invoice->order->payment_method) }}
                </p>
            </div>

            <!-- Tabla de productos -->
            <table class="w-full mb-8">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left py-3 px-2">Producto</th>
                        <th class="text-center py-3 px-2">Cantidad</th>
                        <th class="text-right py-3 px-2">Precio Unit.</th>
                        <th class="text-right py-3 px-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->order->items as $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-2">
                                <p class="font-semibold">{{ $item->book->title }}</p>
                                <p class="text-sm text-gray-600">{{ $item->book->authors_list }}</p>
                            </td>
                            <td class="text-center py-3 px-2">{{ $item->quantity }}</td>
                            <td class="text-right py-3 px-2">${{ number_format($item->price, 2) }}</td>
                            <td class="text-right py-3 px-2 font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totales -->
            <div class="flex justify-end">
                <div class="w-64">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    
                    @if($invoice->order->discount_amount > 0)
                        <div class="flex justify-between py-2 border-b border-gray-200 text-green-600">
                            <span>Descuento:</span>
                            <span class="font-semibold">-${{ number_format($invoice->order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($invoice->order->shipping_cost > 0)
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Envío:</span>
                            <span class="font-semibold">${{ number_format($invoice->order->shipping_cost, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-gray-600">IVA (16%):</span>
                        <span class="font-semibold">${{ number_format($invoice->tax, 2) }}</span>
                    </div>

                    <div class="flex justify-between py-3 mt-2 bg-blue-50 px-4 rounded">
                        <span class="text-lg font-bold">TOTAL:</span>
                        <span class="text-lg font-bold text-blue-600">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Gracias por su compra</p>
                <p class="mt-2">Librerías Gonvill | www.gonvill.com | contacto@gonvill.com</p>
                <p class="mt-1">Este documento es una representación impresa de un CFDI</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print\:shadow-none, .print\:shadow-none * {
            visibility: visible;
        }
        .print\:shadow-none {
            position: absolute;
            left: 0;
            top: 0;
        }
        button, a {
            display: none !important;
        }
    }
</style>
@endsection