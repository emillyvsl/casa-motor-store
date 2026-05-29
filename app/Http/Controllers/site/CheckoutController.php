<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use App\Models\Carts;
use App\Models\Coupons;
use App\Models\Orders;
use App\Models\OrdersItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $customer = auth()->guard('customer')->user();
        $cart = $this->getCart($request);

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('site.cart')->with('error', 'Seu carrinho está vazio.');
        }

        $addresses = $customer->addresses()->orderByDesc('is_default')->get();
        $coupon = session('coupon_id') ? Coupons::find(session('coupon_id')) : null;

        $subtotal = $cart->items->sum('subtotal');
        $shipping = (float) ($cart->shipping_cost ?? 0);
        $discount = $this->calcularDesconto($subtotal, $coupon);
        $total = max(0, $subtotal + $shipping - $discount);
        $estimatedDeliveryDays = $this->estimatedDeliveryDays($cart);

        return view('site.checkout.index', compact(
            'cart', 'addresses', 'coupon',
            'subtotal', 'shipping', 'discount', 'total', 'estimatedDeliveryDays'
        ));
    }

    public function store(Request $request)
    {
        $customer = auth()->guard('customer')->user();
        $cart = $this->getCart($request);

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('site.cart')->with('error', 'Seu carrinho está vazio.');
        }

        if ($inventoryMessage = $this->validateCartInventory($cart)) {
            return redirect()->route('site.cart')->with('error', $inventoryMessage);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:pix,cartao,boleto'],
            // Endereço existente OU novo
            'address_id' => ['nullable', 'exists:addresses,id'],
            // Campos de novo endereço (obrigatórios se address_id não fornecido)
            'street' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:100'],
            'neighborhood' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'city' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'state' => ['required_without:address_id', 'nullable', 'string', 'size:2'],
            'cep' => ['required_without:address_id', 'nullable', 'string'],
            'save_address' => ['nullable', 'boolean'],
        ], [
            'payment_method.required' => 'Selecione um método de pagamento.',
            'payment_method.in' => 'Método de pagamento inválido.',
            'street.required_without' => 'O endereço é obrigatório.',
            'neighborhood.required_without' => 'O bairro é obrigatório.',
            'city.required_without' => 'A cidade é obrigatória.',
            'state.required_without' => 'O estado é obrigatório.',
            'cep.required_without' => 'O CEP é obrigatório.',
        ]);

        // Resolve endereço de entrega
        if (! empty($validated['address_id'])) {
            $address = Addresses::where('id', $validated['address_id'])
                ->where('customer_id', $customer->id)
                ->firstOrFail();
        } else {
            $cepLimpo = preg_replace('/\D/', '', $validated['cep']);
            $address = Addresses::create([
                'customer_id' => $customer->id,
                'street' => $validated['street'],
                'number' => $validated['number'] ?? '',
                'complement' => $validated['complement'] ?? null,
                'neighborhood' => $validated['neighborhood'],
                'city' => $validated['city'],
                'state' => strtoupper($validated['state']),
                'cep' => $cepLimpo,
                'is_default' => $customer->addresses()->count() === 0,
            ]);
        }

        $coupon = session('coupon_id') ? Coupons::find(session('coupon_id')) : null;
        $subtotal = $cart->items->sum('subtotal');
        $shipping = (float) ($cart->shipping_cost ?? 0);
        $discount = $this->calcularDesconto($subtotal, $coupon);
        $total = max(0, $subtotal + $shipping - $discount);
        $shippingDays = max((int) ($cart->shipping_delivery_days ?? 0), 0);

        $order = DB::transaction(function () use ($customer, $cart, $address, $coupon, $validated, $shipping, $discount, $total, $shippingDays) {
            $estimatedDeliveryDays = $shippingDays;

            $order = Orders::create([
                'order_number' => 'PED-'.strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'coupon_id' => $coupon?->id,
                'shipping_cost' => $shipping,
                'shipping_service' => $cart->shipping_service,
                'shipping_delivery_days' => $shippingDays,
                'discount_total' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product;

                if (! $product) {
                    continue;
                }

                $stockQuantity = $product->stockQuantityFor($item->quantity);
                $backorderedQuantity = $product->backorderedQuantityFor($item->quantity);
                $leadTimeDays = $product->leadTimeDaysFor($item->quantity);
                $itemEstimatedDeliveryDays = $shippingDays + $leadTimeDays;
                $estimatedDeliveryDays = max($estimatedDeliveryDays, $itemEstimatedDeliveryDays);

                OrdersItems::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'fulfillment_mode' => $product->fulfillmentModeFor($item->quantity),
                    'stock_quantity' => $stockQuantity,
                    'backordered_quantity' => $backorderedQuantity,
                    'lead_time_days' => $leadTimeDays,
                    'estimated_delivery_days' => $itemEstimatedDeliveryDays,
                ]);

                // Decrementa estoque (respeita backorder)
                if ($stockQuantity > 0) {
                    $product->decrement('stock', $stockQuantity);
                }
            }

            $order->update([
                'estimated_delivery_days' => $estimatedDeliveryDays,
            ]);

            // Incrementa contador de uso do cupom
            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Limpa o carrinho
            $cart->items()->delete();
            $cart->update([
                'total' => 0,
                'shipping_cost' => 0,
                'shipping_service' => null,
                'shipping_cep_destino' => null,
                'shipping_delivery_days' => null,
            ]);

            session()->forget(['coupon_id', 'coupon_code']);

            return $order;
        });

        return redirect()
            ->route('customer.checkout.success', $order->order_number)
            ->with('success', 'Pedido realizado com sucesso!');
    }

    public function success($orderNumber)
    {
        $customer = auth()->guard('customer')->user();

        $order = Orders::with(['items.product.images', 'address', 'payment'])
            ->where('order_number', $orderNumber)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return view('site.checkout.success', compact('order'));
    }

    // ──────────────────── Helpers ────────────────────

    protected function getCart(Request $request): ?Carts
    {
        $customer = auth()->guard('customer')->user();

        $cart = Carts::with('items.product')
            ->where('customer_id', $customer->id)
            ->first();

        return $cart;
    }

    protected function calcularDesconto(float $subtotal, ?Coupons $coupon): float
    {
        if (! $coupon) {
            return 0;
        }

        if ($coupon->type === 'percent' && $coupon->discount_percent) {
            return round($subtotal * ($coupon->discount_percent / 100), 2);
        }

        return (float) ($coupon->discount_value ?? 0);
    }

    protected function validateCartInventory(Carts $cart): ?string
    {
        foreach ($cart->items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                return 'Um dos produtos do carrinho não está mais disponível.';
            }

            if (! $item->product->canFulfillQuantity($item->quantity)) {
                return $item->product->availabilityMessageFor($item->quantity);
            }
        }

        return null;
    }

    protected function estimatedDeliveryDays(Carts $cart): int
    {
        $shippingDays = max((int) ($cart->shipping_delivery_days ?? 0), 0);

        return (int) $cart->items->reduce(function (int $carry, $item) use ($shippingDays) {
            $product = $item->product;

            if (! $product) {
                return $carry;
            }

            return max($carry, $shippingDays + $product->leadTimeDaysFor($item->quantity));
        }, $shippingDays);
    }
}
