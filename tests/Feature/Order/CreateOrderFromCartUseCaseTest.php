<?php

namespace Tests\Feature\Order;

use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Tests\TestCase;

class CreateOrderFromCartUseCaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    private function makeCustomer(): CustomerModel
    {
        $user = UserModel::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        return CustomerModel::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'street' => 'Calle Mayor',
            'street_number' => '1',
            'city' => 'Madrid',
            'postal_code' => '28001',
            'state_province' => 'Madrid',
            'country' => 'España',
            'iso_country_code' => 'ES',
        ]);
    }

    private function makeShippingAddress(): ShippingAddress
    {
        return new ShippingAddress(
            street: 'Calle Mayor',
            streetNumber: '1',
            apartment: null,
            city: 'Madrid',
            postalCode: '28001',
            stateProvince: 'Madrid',
            country: 'España',
            isoCountryCode: 'ES',
        );
    }

    private function makeProduct(int $stock = 10, float $price = 29.99): ProductModel
    {
        return ProductModel::create([
            'artist' => 'Test Artist',
            'album_title' => 'Test Album',
            'slug' => 'test-artist-test-album-'.uniqid(),
            'genre' => 'Rock',
            'release_year' => 2000,
            'country' => 'UK',
            'label' => 'Test Label',
            'price' => $price,
            'stock_quantity' => $stock,
            'is_active' => true,
        ]);
    }

    private function makeCartWith(CustomerModel $customer, ProductModel $product, int $quantity = 1): CartModel
    {
        $cart = CartModel::create([
            'customer_id' => $customer->id,
            'cart_token' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        CartItemModel::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        return $cart;
    }

    public function test_creates_order_and_decrements_stock(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct(stock: 10, price: 29.99);
        $cart = $this->makeCartWith($customer, $product, quantity: 2);

        /** @var CreateOrderFromCartUseCase $useCase */
        $useCase = app(CreateOrderFromCartUseCase::class);
        $result = $useCase->execute(
            cartToken: $cart->cart_token,
            customerId: $customer->id,
            shippingAddress: $this->makeShippingAddress(),
        );

        $this->assertNotEmpty($result->orderNumber);
        $this->assertEquals('pending', $result->status);
        $this->assertEquals(59.98, $result->subtotal);
        $this->assertEquals(79.57, $result->totalAmount);

        $this->assertDatabaseHas('orders', ['order_number' => $result->orderNumber]);

        $product->refresh();
        $this->assertEquals(8, $product->stock_quantity);
    }

    public function test_clears_cart_items_after_checkout(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $cart = $this->makeCartWith($customer, $product, quantity: 1);

        $useCase = app(CreateOrderFromCartUseCase::class);
        $useCase->execute(cartToken: $cart->cart_token, customerId: $customer->id, shippingAddress: $this->makeShippingAddress());

        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }

    public function test_throws_on_empty_cart(): void
    {
        $customer = $this->makeCustomer();
        $cart = CartModel::create([
            'customer_id' => $customer->id,
            'cart_token' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        $useCase = app(CreateOrderFromCartUseCase::class);

        $this->expectException(\DomainException::class);
        $useCase->execute(cartToken: $cart->cart_token, customerId: $customer->id, shippingAddress: $this->makeShippingAddress());
    }

    public function test_throws_when_stock_is_insufficient(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct(stock: 1);
        $cart = $this->makeCartWith($customer, $product, quantity: 5);

        $useCase = app(CreateOrderFromCartUseCase::class);

        $this->expectException(\DomainException::class);
        $useCase->execute(cartToken: $cart->cart_token, customerId: $customer->id, shippingAddress: $this->makeShippingAddress());
    }

    public function test_stock_is_not_decremented_when_order_fails(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct(stock: 1);
        $cart = $this->makeCartWith($customer, $product, quantity: 5);

        $useCase = app(CreateOrderFromCartUseCase::class);

        try {
            $useCase->execute(cartToken: $cart->cart_token, customerId: $customer->id, shippingAddress: $this->makeShippingAddress());
        } catch (\DomainException) {
        }

        $product->refresh();
        $this->assertEquals(1, $product->stock_quantity);
    }
}
