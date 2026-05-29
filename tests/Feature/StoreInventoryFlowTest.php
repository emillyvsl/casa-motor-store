<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Carts;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreInventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_the_discount_price_when_adding_a_product_to_the_cart(): void
    {
        $product = $this->createProduct([
            'price' => 150,
            'discount_price' => 99.9,
            'stock' => 3,
        ]);

        $response = $this
            ->from('/produtos')
            ->post(route('site.cart.add'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertRedirect('/produtos');
        $response->assertSessionHas('success');

        $item = CartItem::first();

        $this->assertNotNull($item);
        $this->assertSame(2, $item->quantity);
        $this->assertSame('99.90', $item->unit_price);
        $this->assertSame('199.80', $item->subtotal);
    }

    public function test_it_blocks_quantities_above_stock_and_backorder_limit(): void
    {
        $product = $this->createProduct([
            'stock' => 1,
            'allow_out_of_stock_sales' => true,
            'max_backorder' => 1,
            'backorder_delivery_days' => 15,
        ]);

        $response = $this
            ->from('/produtos')
            ->post(route('site.cart.add'), [
                'product_id' => $product->id,
                'quantity' => 3,
            ]);

        $response->assertRedirect('/produtos');
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_checkout_requires_selected_shipping_before_creating_the_order(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct([
            'stock' => 2,
            'price' => 200,
        ]);

        $cart = Carts::create([
            'customer_id' => $customer->id,
            'session_id' => 'checkout-no-shipping',
            'total' => 200,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 200,
            'subtotal' => 200,
        ]);

        $response = $this
            ->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), [
                'payment_method' => 'pix',
                'street' => 'Rua Teste',
                'number' => '100',
                'neighborhood' => 'Centro',
                'city' => 'Rio Branco',
                'state' => 'AC',
                'cep' => '69900000',
            ]);

        $response->assertRedirect(route('site.cart'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_it_records_backorder_metadata_when_creating_an_order(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct([
            'stock' => 1,
            'price' => 250,
            'allow_out_of_stock_sales' => true,
            'max_backorder' => 10,
            'backorder_delivery_days' => 12,
        ]);

        $cart = Carts::create([
            'customer_id' => $customer->id,
            'session_id' => 'checkout-with-backorder',
            'total' => 500,
            'shipping_service' => 'SEDEX',
            'shipping_cost' => 40,
            'shipping_cep_destino' => '69900000',
            'shipping_delivery_days' => 5,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 250,
            'subtotal' => 500,
        ]);

        $response = $this
            ->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), [
                'payment_method' => 'pix',
                'street' => 'Rua Teste',
                'number' => '100',
                'neighborhood' => 'Centro',
                'city' => 'Rio Branco',
                'state' => 'AC',
                'cep' => '69900000',
            ]);

        $order = Orders::first();
        $orderItem = OrdersItems::first();

        $response->assertRedirect(route('customer.checkout.success', $order->order_number));
        $this->assertNotNull($order);
        $this->assertNotNull($orderItem);
        $this->assertSame(540.0, (float) $order->total);
        $this->assertSame('SEDEX', $order->shipping_service);
        $this->assertSame(17, $order->estimated_delivery_days);
        $this->assertSame('mixed', $orderItem->fulfillment_mode);
        $this->assertSame(1, $orderItem->stock_quantity);
        $this->assertSame(1, $orderItem->backordered_quantity);
        $this->assertSame(12, $orderItem->lead_time_days);
        $this->assertSame(17, $orderItem->estimated_delivery_days);

        $product->refresh();

        $this->assertSame(0, $product->stock);
        $this->assertDatabaseCount('cart_items', 0);
    }

    protected function createCustomer(): Customer
    {
        return Customer::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente+'.uniqid().'@example.com',
            'password' => Hash::make('password'),
            'status' => 'ativo',
        ]);
    }

    protected function createProduct(array $overrides = []): Products
    {
        $category = Category::create([
            'name' => 'Motores',
            'slug' => 'motores-'.uniqid(),
            'is_active' => true,
        ]);

        return Products::create(array_merge([
            'name' => 'Produto Teste '.uniqid(),
            'slug' => 'produto-teste-'.uniqid(),
            'sku' => 'SKU-'.uniqid(),
            'category_id' => $category->id,
            'price' => 100,
            'stock' => 1,
            'stock_alert_threshold' => 5,
            'is_active' => true,
            'allow_out_of_stock_sales' => false,
            'max_backorder' => null,
            'backorder_delivery_days' => 0,
        ], $overrides));
    }
}
