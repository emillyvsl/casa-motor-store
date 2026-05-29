<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $customer = auth()->guard('customer')->user();

        $orders = Orders::with(['items.product.images', 'payment'])
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('site.orders.index', compact('orders'));
    }

    public function show(Orders $order)
    {
        $customer = auth()->guard('customer')->user();

        // Garante que o cliente só vê seus próprios pedidos
        abort_if($order->customer_id !== $customer->id, 403, 'Acesso negado.');

        $order->load(['items.product.images', 'address', 'payment', 'coupon']);

        return view('site.orders.show', compact('order'));
    }
}
