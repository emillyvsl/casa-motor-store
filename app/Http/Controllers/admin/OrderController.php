<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Orders::with(['customer', 'payment'])
            ->orderByDesc('created_at');

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por busca (número do pedido ou nome do cliente)
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%"));
            });
        }

        // Filtro por data
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Métricas do topo
        $stats = [
            'total'     => Orders::count(),
            'pending'   => Orders::where('status', 'pending')->count(),
            'paid'      => Orders::where('status', 'paid')->count(),
            'shipped'   => Orders::where('status', 'shipped')->count(),
            'revenue'   => Orders::whereIn('status', ['paid', 'shipped', 'delivered'])->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Orders $order)
    {
        $order->load(['customer', 'items.product.images', 'address', 'coupon', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,shipped,delivered,canceled'],
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Timestamps automáticos de envio e entrega
        if ($validated['status'] === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }
        if ($validated['status'] === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }

        return back()->with('success', "Status atualizado de \"{$oldStatus}\" para \"{$validated['status']}\".");
    }

    public function addTracking(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'tracking_code' => ['required', 'string', 'max:100'],
        ], [
            'tracking_code.required' => 'Informe o código de rastreio.',
        ]);

        $order->update([
            'tracking_code' => $validated['tracking_code'],
            'status'        => $order->status === 'paid' ? 'shipped' : $order->status,
            'shipped_at'    => $order->shipped_at ?? now(),
        ]);

        return back()->with('success', 'Código de rastreio salvo com sucesso!');
    }
}
