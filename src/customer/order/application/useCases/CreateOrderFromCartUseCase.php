<?php

namespace Src\customer\order\application\useCases;

use Src\customer\cart\domain\repositories\CartRepositoryInterface;
use Src\customer\order\application\dto\OrderResult;
use Src\customer\order\domain\entities\Order;
use Src\customer\order\domain\entities\OrderItem;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\shared\domain\repositories\EventPublisher;

class CreateOrderFromCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private OrderRepositoryInterface $orderRepository,
        private EventPublisher $eventPublisher,
    ) {}

    public function execute(
        string $cartToken,
        int $customerId,
        ShippingAddress $shippingAddress,
        ?string $customerNotes = null,
        bool $isVip = false,
    ): OrderResult {
        $cart = $this->cartRepository->findByToken($cartToken);

        if (empty($cart->items())) {
            throw new \DomainException('Cannot create an order from an empty cart.');
        }

        $products = [];
        foreach ($cart->items() as $cartItem) {
            $product = $this->productRepository->findById($cartItem->productId());

            if ($product->stockQuantity() < $cartItem->quantity()) {
                throw new \DomainException(
                    "'{$product->artist()} — {$product->albumTitle()}' no tiene suficiente stock disponible."
                );
            }

            $products[$cartItem->productId()] = $product;
        }

        $orderItems = array_map(function ($cartItem) use ($products) {
            $product = $products[$cartItem->productId()];

            return OrderItem::create(
                productId: $cartItem->productId(),
                productSnapshot: [
                    'artist' => $product->artist(),
                    'album_title' => $product->albumTitle(),
                    'slug' => $product->slug(),
                    'genre' => $product->genre(),
                    'cover_image_url' => $product->coverImageUrl(),
                ],
                quantity: $cartItem->quantity(),
                pricePerUnit: $cartItem->price(),
            );
        }, $cart->items());

        $subtotal = array_sum(array_map(fn ($item) => $item->subtotal(), $orderItems));

        $discountAmount = $isVip ? round($subtotal * 0.15, 2) : 0.0;
        $shippingCost = ($subtotal - $discountAmount) >= 60.0 ? 0.0 : 6.99;
        $taxRate = $this->taxRate($shippingAddress);
        $taxAmount = round(($subtotal - $discountAmount) * $taxRate, 2);

        $order = Order::create(
            customerId: $customerId,
            items: $orderItems,
            shippingCost: $shippingCost,
            taxAmount: $taxAmount,
            discountAmount: $discountAmount,
            customerNotes: $customerNotes,
        );

        $this->orderRepository->save($order);

        foreach ($order->releaseEvents() as $event) {
            $this->eventPublisher->publish($event);
        }

        foreach ($cart->items() as $cartItem) {
            $this->productRepository->decrementStock($cartItem->productId(), $cartItem->quantity());
        }

        $this->cartRepository->clearItems($cart->id());

        return OrderResult::fromOrder($this->orderRepository->findByOrderNumber($order->orderNumber()));
    }

    private function taxRate(ShippingAddress $address): float
    {
        return 0.21;
    }
}
