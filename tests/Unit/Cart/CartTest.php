<?php

namespace Tests\Unit\Cart;

use PHPUnit\Framework\TestCase;
use Src\customer\cart\domain\entities\Cart;
use Src\customer\cart\domain\entities\CartItem;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;

class CartTest extends TestCase
{
    private function makeCart(): Cart
    {
        return Cart::create(customerId: null, sessionId: 'test-session');
    }

    public function test_add_item_adds_new_item(): void
    {
        $cart = $this->makeCart();
        $cart->addItem(productId: 1, price: 29.99, quantity: 2);

        $this->assertCount(1, $cart->items());
        $this->assertEquals(1, $cart->items()[0]->productId());
        $this->assertEquals(2, $cart->items()[0]->quantity());
    }

    public function test_add_item_merges_quantity_for_same_product(): void
    {
        $cart = $this->makeCart();
        $cart->addItem(productId: 1, price: 29.99, quantity: 2);
        $cart->addItem(productId: 1, price: 29.99, quantity: 3);

        $this->assertCount(1, $cart->items());
        $this->assertEquals(5, $cart->items()[0]->quantity());
    }

    public function test_add_item_keeps_separate_items_for_different_products(): void
    {
        $cart = $this->makeCart();
        $cart->addItem(productId: 1, price: 29.99, quantity: 1);
        $cart->addItem(productId: 2, price: 19.99, quantity: 1);

        $this->assertCount(2, $cart->items());
    }

    public function test_total_amount_calculates_correctly(): void
    {
        $cart = $this->makeCart();
        $cart->addItem(productId: 1, price: 10.00, quantity: 2);
        $cart->addItem(productId: 2, price: 5.50, quantity: 3);

        $this->assertEquals(36.50, $cart->totalAmount());
    }

    public function test_is_expired_returns_false_when_not_expired(): void
    {
        $cart = Cart::fromPrimitives(
            id: 1,
            customerId: null,
            sessionId: null,
            cartToken: 'test-token',
            expiresAt: new \DateTimeImmutable('+1 day'),
            items: [],
        );

        $this->assertFalse($cart->isExpired());
    }

    public function test_is_expired_returns_true_when_expired(): void
    {
        $cart = Cart::fromPrimitives(
            id: 1,
            customerId: null,
            sessionId: null,
            cartToken: 'test-token',
            expiresAt: new \DateTimeImmutable('-1 day'),
            items: [],
        );

        $this->assertTrue($cart->isExpired());
    }

    public function test_remove_item_removes_item_by_id(): void
    {
        $item = CartItem::fromPrimitives(id: 42, cartId: 1, productId: 5, quantity: 1, price: 29.99);
        $cart = Cart::fromPrimitives(
            id: 1,
            customerId: null,
            sessionId: null,
            cartToken: 'test-token',
            expiresAt: null,
            items: [$item],
        );

        $cart->removeItem(42);

        $this->assertEmpty($cart->items());
    }

    public function test_remove_item_throws_for_unknown_item(): void
    {
        $cart = Cart::fromPrimitives(
            id: 1,
            customerId: null,
            sessionId: null,
            cartToken: 'test-token',
            expiresAt: null,
            items: [],
        );

        $this->expectException(CartItemNotFoundException::class);
        $cart->removeItem(99);
    }

    public function test_update_item_quantity_updates_quantity(): void
    {
        $item = CartItem::fromPrimitives(id: 10, cartId: 1, productId: 3, quantity: 2, price: 15.00);
        $cart = Cart::fromPrimitives(
            id: 1,
            customerId: null,
            sessionId: null,
            cartToken: 'test-token',
            expiresAt: null,
            items: [$item],
        );

        $cart->updateItemQuantity(10, 5);

        $this->assertEquals(5, $cart->items()[0]->quantity());
    }
}
