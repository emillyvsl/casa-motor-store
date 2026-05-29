<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\Coupons;
use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Recupera ou cria o carrinho atual (sessão ou cliente logado) e faz merge se necessário.
     */
    protected function cart(Request $request): Carts
    {
        $sessionId = $request->session()->getId();
        $customer = auth()->guard('customer')->user();

        $sessionCart = Carts::with('items.product')
            ->where('session_id', $sessionId)
            ->whereNull('customer_id')
            ->first();

        if ($customer) {
            $customerCart = Carts::firstOrCreate(
                ['customer_id' => $customer->id],
                ['session_id' => $sessionId, 'total' => 0]
            );

            // Merge carrinho de sessão (visitante) quando cliente logar
            if ($sessionCart && $sessionCart->id !== $customerCart->id) {
                $cartChanged = false;

                foreach ($sessionCart->items as $item) {
                    if (! $item->product) {
                        continue;
                    }

                    $existingQuantity = (int) $customerCart->items()
                        ->where('product_id', $item->product_id)
                        ->value('quantity');

                    $desiredQuantity = $existingQuantity + $item->quantity;
                    $availableQuantity = $item->product->availableQuantity();

                    if ($availableQuantity !== null && $desiredQuantity > $availableQuantity) {
                        $quantityToAdd = max(0, $availableQuantity - $existingQuantity);
                    } else {
                        $quantityToAdd = $item->quantity;
                    }

                    if ($quantityToAdd > 0) {
                        $customerCart->addProduct($item->product, $quantityToAdd);
                        $cartChanged = true;
                    }
                }

                if ($cartChanged) {
                    $customerCart->clearShippingSelection();
                }

                $sessionCart->delete();
            }

            return $customerCart->fresh(['items.product.images']);
        }

        if ($sessionCart) {
            return $sessionCart->fresh(['items.product.images']);
        }

        return Carts::create(['session_id' => $sessionId, 'total' => 0])
            ->fresh(['items.product.images']);
    }

    public function show(Request $request)
    {
        $cart = $this->cart($request);

        return view('site.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Products::where('is_active', true)->findOrFail($data['product_id']);
        $quantityToAdd = $data['quantity'] ?? 1;
        $cart = $this->cart($request);
        $existingQuantity = (int) $cart->items()
            ->where('product_id', $product->id)
            ->value('quantity');
        $requestedQuantity = $existingQuantity + $quantityToAdd;

        if (! $product->canFulfillQuantity($requestedQuantity)) {
            return back()->with('error', $product->availabilityMessageFor($requestedQuantity));
        }

        $cart->addProduct($product, $quantityToAdd);
        $cart->clearShippingSelection();

        $message = $product->backorderedQuantityFor($requestedQuantity) > 0
            ? 'Produto adicionado ao carrinho. O pedido inclui quantidade sob encomenda com prazo maior de entrega.'
            : 'Produto adicionado ao carrinho!';

        return back()->with('success', $message);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->cart($request);
        $item = $cart->items()->where('id', $data['item_id'])->with('product')->firstOrFail();

        if (! $item->product) {
            return back()->with('error', 'Este produto não está mais disponível.');
        }

        if (! $item->product->canFulfillQuantity($data['quantity'])) {
            return back()->with('error', $item->product->availabilityMessageFor($data['quantity']));
        }

        $price = $item->product->effective_price;
        $item->quantity = $data['quantity'];
        $item->unit_price = $price;
        $item->subtotal = $price * $data['quantity'];
        $item->save();

        $cart->updateTotal();
        $cart->clearShippingSelection();

        return back()->with('success', 'Quantidade atualizada.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
        ]);

        $cart = $this->cart($request);
        $cart->items()->where('id', $data['item_id'])->delete();
        $cart->updateTotal();
        $cart->clearShippingSelection();

        return back()->with('success', 'Item removido do carrinho.');
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $coupon = Coupons::where('code', strtoupper(trim($data['code'])))
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $coupon) {
            return back()->with('error', 'Cupom invalido ou expirado.');
        }

        session([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
        ]);

        return back()->with('success', 'Cupom "'.$coupon->code.'" aplicado com sucesso!');
    }

    public function removeCoupon(Request $request)
    {
        session()->forget(['coupon_id', 'coupon_code']);

        return back()->with('success', 'Cupom removido.');
    }

    /**
     * Persiste o frete escolhido na sessao do carrinho.
     * Chamado via AJAX quando o cliente seleciona uma opcao de frete.
     */
    public function saveShipping(Request $request)
    {
        $data = $request->validate([
            'service' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'delivery_days' => ['nullable', 'integer', 'min:0'],
            'cep' => ['required', 'string'],
        ]);

        $cart = $this->cart($request);
        $cart->update([
            'shipping_service' => $data['service'],
            'shipping_cost' => $data['cost'],
            'shipping_delivery_days' => $data['delivery_days'] ?? null,
            'shipping_cep_destino' => preg_replace('/\D/', '', $data['cep']),
        ]);

        $cart->updateTotal();

        return response()->json([
            'success' => true,
            'total' => number_format($cart->total + $cart->shipping_cost, 2, ',', '.'),
        ]);
    }
}
