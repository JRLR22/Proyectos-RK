<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Listar todas las facturas del usuario
     * GET /invoices
     */
    public function index()
    {
        $user = Auth::user();
        
        $invoices = Invoice::with('order')
            ->whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Ver detalle de una factura
     * GET /invoices/{id}
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $invoice = Invoice::with(['order.items.book', 'order.user', 'order.address'])
            ->findOrFail($id);

        // Verificar que la factura pertenece al usuario
        if ($invoice->order->user_id !== $user->user_id) {
            abort(403, 'No tienes acceso a esta factura');
        }

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Descargar factura en PDF
     * GET /invoices/{id}/download
     */
    public function download($id)
    {
        $user = Auth::user();
        
        $invoice = Invoice::with(['order.items.book', 'order.user', 'order.address'])
            ->findOrFail($id);

        // Verificar que la factura pertenece al usuario
        if ($invoice->order->user_id !== $user->user_id) {
            abort(403, 'No tienes acceso a esta factura');
        }

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('factura-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Vista previa de factura (HTML)
     * GET /invoices/{id}/preview
     */
    public function preview($id)
    {
        $user = Auth::user();
        
        $invoice = Invoice::with(['order.items.book', 'order.user', 'order.address'])
            ->findOrFail($id);

        // Verificar que la factura pertenece al usuario
        if ($invoice->order->user_id !== $user->user_id) {
            abort(403, 'No tienes acceso a esta factura');
        }

        return view('invoices.pdf', compact('invoice'));
    }

    /**
     * Solicitar factura para una orden
     * POST /orders/{order_id}/request-invoice
     */
    public function requestInvoice(Request $request, $orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('order_id', $orderId)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        // Verificar que la orden esté pagada
        if ($order->payment_status !== 'completado') {
            return back()->with('error', 'Solo puedes solicitar factura para órdenes pagadas');
        }

        // Verificar si ya tiene factura
        if ($order->invoice) {
            return back()->with('error', 'Esta orden ya tiene una factura generada');
        }

        // Generar factura
        $invoiceNumber = 'INV-' . strtoupper(substr(uniqid(), -8));
        
        $invoice = Invoice::create([
            'order_id' => $order->order_id,
            'invoice_number' => $invoiceNumber,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax_amount,
            'total' => $order->total,
            'issue_date' => now(),
        ]);

        return redirect()->route('invoices.show', $invoice->invoice_id)
            ->with('success', 'Factura generada exitosamente');
    }

    // ==================== API ENDPOINTS ====================

    /**
     * API: Listar facturas del usuario
     * GET /api/invoices
     */
    public function apiIndex()
    {
        $user = Auth::user();
        
        $invoices = Invoice::with('order')
            ->whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invoices' => $invoices->map(function($invoice) {
                return [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'order_number' => $invoice->order->order_number,
                    'total' => $invoice->total,
                    'issue_date' => $invoice->issue_date->format('d/m/Y'),
                    'created_at' => $invoice->created_at->format('d/m/Y H:i'),
                ];
            }),
        ]);
    }

    /**
     * API: Ver detalle de factura
     * GET /api/invoices/{id}
     */
    public function apiShow($id)
    {
        $user = Auth::user();
        
        $invoice = Invoice::with(['order.items.book', 'order.user', 'order.address'])
            ->findOrFail($id);

        // Verificar que la factura pertenece al usuario
        if ($invoice->order->user_id !== $user->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes acceso a esta factura',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'invoice' => [
                'invoice_id' => $invoice->invoice_id,
                'invoice_number' => $invoice->invoice_number,
                'order_number' => $invoice->order->order_number,
                'issue_date' => $invoice->issue_date->format('d/m/Y'),
                
                'customer' => [
                    'name' => $invoice->order->user->full_name,
                    'email' => $invoice->order->user->email,
                ],
                
                'items' => $invoice->order->items->map(function($item) {
                    return [
                        'title' => $item->book->title,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                
                'subtotal' => $invoice->subtotal,
                'tax' => $invoice->tax,
                'total' => $invoice->total,
            ],
        ]);
    }
        /**
     * Obtener facturas del usuario en JSON (para AJAX)
     * GET /api/invoices
     */
    public function getUserInvoices()
    {
        $user = Auth::user();
        
        $invoices = Invoice::with('order')
            ->whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invoices' => $invoices->map(function ($invoice) {
                return [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'order_number' => $invoice->order->order_number,
                    'total' => $invoice->total,
                    'issue_date' => $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') : null,
                ];
            })
        ]);
    }
}