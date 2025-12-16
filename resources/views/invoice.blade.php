<!DOCTYPE html>
<html>
<head>
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .total { font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <h1>FACTURA {{ $invoice->invoice_number }}</h1>
    <p><strong>Fecha:</strong> {{ $invoice->issue_date->format('d/m/Y') }}</p>
    <p><strong>Cliente:</strong> {{ $invoice->order->user->full_name }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
            <tr>
                <td>{{ $item->book->title }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Subtotal:</td>
                <td>${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">IVA:</td>
                <td>${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="3" style="text-align: right;">TOTAL:</td>
                <td>${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>