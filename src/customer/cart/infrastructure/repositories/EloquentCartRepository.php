<?php

namespace Src\customer\cart\infrastructure\repositories;

use App\Models\CartItemModel;
use App\Models\CartModel;
use Src\customer\cart\domain\entities\Cart;
use Src\customer\cart\domain\entities\CartItem;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;
use Src\customer\cart\domain\exceptions\CartNotFoundException;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function save(Cart $cart): int
    {
        $model = CartModel::create([
            'customer_id' => $cart->customerId(),
            'session_id'  => $cart->sessionId(),
            'cart_token'  => $cart->cartToken(),
            'expires_at'  => $cart->expiresAt(),
        ]);

        return $model->id;
    }

    public function findById(int $id): Cart
    {
        $model = CartModel::with('items.product')->find($id);

        if (! $model) {
            throw CartNotFoundException::byId($id);
        }

        return $this->toCart($model);
    }

    public function findByToken(string $token): Cart
    {
        $model = CartModel::with('items.product')->where('cart_token', $token)->first();

        if (! $model) {
            throw CartNotFoundException::byToken($token);
        }

        return $this->toCart($model);
    }

    public function findByCustomerId(int $customerId): ?Cart
    {
        $model = CartModel::with('items.product')->where('customer_id', $customerId)->first();

        return $model ? $this->toCart($model) : null;
    }

    public function findBySessionId(string $sessionId): ?Cart
    {
        $model = CartModel::with('items.product')->where('session_id', $sessionId)->first();

        return $model ? $this->toCart($model) : null;
    }

    public function addItem(int $cartId, CartItem $item): CartItem
    {
        $model = CartItemModel::create([
            'cart_id'    => $cartId,
            'product_id' => $item->productId(),
            'quantity'   => $item->quantity(),
            'price'      => $item->price(),
        ]);

        return CartItem::fromPrimitives(
            id: $model->id,
            cartId: $model->cart_id,
            productId: $model->product_id,
            quantity: $model->quantity,
            price: (float) $model->price,
        );
    }

    public function updateItemQuantity(int $cartItemId, int $quantity): void
    {
        $updated = CartItemModel::whereKey($cartItemId)->update(['quantity' => $quantity]);

        if (! $updated) {
            throw CartItemNotFoundException::byId($cartItemId);
        }
    }

    public function removeItem(int $cartItemId): void
    {
        CartItemModel::destroy($cartItemId);
    }

    public function clearItems(int $cartId): void
    {
        CartItemModel::where('cart_id', $cartId)->delete();
    }

    private function toCart(CartModel $model): Cart
    {
        $items = $model->items->map(fn (CartItemModel $item) => CartItem::fromPrimitives(
            id: $item->id,
            cartId: $item->cart_id,
            productId: $item->product_id,
            quantity: $item->quantity,
            price: (float) $item->price,
            productArtist: $item->product?->artist,
            productAlbumTitle: $item->product?->album_title,
            productCoverImageUrl: $item->product?->cover_image_url,
        ))->all();

        return Cart::fromPrimitives(
            id: $model->id,
            customerId: $model->customer_id,
            sessionId: $model->session_id,
            cartToken: $model->cart_token,
            expiresAt: $model->expires_at,
            items: $items,
        );
    }
}