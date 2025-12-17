<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563EB; padding-bottom: 20px; }
        .header h1 { color: #2563EB; margin: 0; font-size: 32px; }
        .info { margin: 20px 0; }
        .info-box { width: 48%; display: inline-block; vertical-align: top; }
        .info-box h3 { font-size: 14px; color: #2563EB; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #2563EB; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .totals { text-align: right; margin-top: 20px; }
        .totals table { width: 300px; margin-left: auto; }
        .total-row { font-size: 16px; font-weight: bold; background: #EFF6FF; }
        .footer { margin-top: 50px; text-align: center; color: #666; font-size: 10px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA</h1>
        <p><strong>Librerías Gonvill</strong></p>
        <p>RFC: GON123456ABC</p>
        <p style="color: #2563EB; font-size: 16px; margin-top: 10px;">{{ $invoice->invoice_number }}</p>
        <p>Fecha: {{ $invoice->issue_date->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <div class="info-box">
            <h3>FACTURADO A:</h3>
            <p><strong>{{ $invoice->order->user->full_name }}</strong></p>
            <p>{{ $invoice->order->user->email }}</p>
            @if($invoice->order->user->phone)
                <p>Tel: {{ $invoice->order->user->phone }}</p>
            @endif
        </div>

        <div class="info-box" style="float: right;">
            <h3>DIRECCIÓN DE ENVÍO:</h3>
            @if($invoice->order->address)
                <p><strong>{{ $invoice->order->address->recipient_name }}</strong></p>
                <p>{{ $invoice->order->address->phone }}</p>
                <p>{{ $invoice->order->address->street_address }}</p>
                @if($invoice->order->address->apartment)
                    <p>{{ $invoice->order->address->apartment }}</p>
                @endif
                <p>{{ $invoice->order->address->city }}, {{ $invoice->order->address->state }} {{ $invoice->order->address->postal_code }}</p>
            @endif
        </div>
    </div>

    <div style="clear: both; margin: 20px 0; padding: 10px; background: #f8f9fa; border-radius: 5px;">
        <strong>Orden:</strong> {{ $invoice->order->order_number }} | 
        <strong>Fecha de compra:</strong> {{ $invoice->order->created_at->format('d/m/Y') }} |
        <strong>Método de pago:</strong> {{ ucfirst($invoice->order->payment_method ?? 'N/A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->book->title }}</strong><br>
                        <small>{{ $item->book->authors_list }}</small>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td><strong>${{ number_format($invoice->subtotal, 2) }}</strong></td>
            </tr>
            @if($invoice->order->discount_amount > 0)
            <tr style="color: green;">
                <td>Descuento:</td>
                <td><strong>-${{ number_format($invoice->order->discount_amount, 2) }}</strong></td>
            </tr>
            @endif
            @if($invoice->order->shipping_cost > 0)
            <tr>
                <td>Envío:</td>
                <td><strong>${{ number_format($invoice->order->shipping_cost, 2) }}</strong></td>
            </tr>
            @endif
            <tr>
                <td>IVA (16%):</td>
                <td><strong>${{ number_format($invoice->tax, 2) }}</strong></td>
            </tr>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>Gracias por su compra</strong></p>
        <p>Librerías Gonvill | www.gonvill.com | contacto@gonvill.com</p>
        <p>Este documento es una representación impresa de un CFDI</p>
    </div>
</body>
</html>