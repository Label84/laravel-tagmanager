<?php

namespace Label84\TagManager;

use Illuminate\Support\Collection;

class TagManager
{
    private Collection $data;

    public function __construct()
    {
        $this->data = new Collection();
    }

    public function push(array $variables): self
    {
        $this->data->push($variables);

        return $this;
    }

    public function event(string $name, array $variables = []): self
    {
        $this->data->push(['event' => $name] + $variables);

        return $this;
    }

    public function login(array $variables = []): self
    {
        $this->data->push(['event' => 'login'] + $variables);

        return $this;
    }

    public function register(array $variables = []): self
    {
        $this->data->push(['event' => 'sign_up'] + $variables);

        return $this;
    }

    public function setUserId(string $userId): self
    {
        $this->data->push(['user_id' => $userId]);

        return $this;
    }

    public function viewItemList(array $items, array $variables = []): self
    {
        $this->event('view_item_list', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function viewItem(array $items, array $variables = []): self
    {
        $this->event('view_item', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function selectItem(array $items, array $variables = []): self
    {
        $this->event('select_item', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function viewPromotion(array $items, array $variables = []): self
    {
        $this->event('view_promotion', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function selectPromotion(array $items, array $variables = []): self
    {
        $this->event('select_promotion', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function addToWishlist(string $currency, float $value, array $items, array $variables = []): self
    {
        $this->event('add_to_wishlist', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function addToCart(array $items, array $variables = []): self
    {
        $this->event('add_to_cart', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function removeFromCart(array $items, array $variables = []): self
    {
        $this->event('remove_from_cart', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function viewCart(string $currency, float $value, array $items, array $variables = []): self
    {
        $this->event('view_cart', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function beginCheckout(array $items, array $variables = []): self
    {
        $this->event('begin_checkout', array_merge(['ecommerce' => [
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function addPaymentInfo(string $currency, float $value, string $coupon, string $paymentType, array $items, array $variables = []): self
    {
        $this->event('add_payment_info', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'coupon' => $coupon,
            'payment_type' => $paymentType,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function addShippingInfo(string $currency, float $value, string $coupon, string $shippingTier, array $items, array $variables = []): self
    {
        $this->event('add_shipping_info', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'coupon' => $coupon,
            'shipping_tier' => $shippingTier,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function purchase(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, string $coupon, array $items, array $variables = []): self
    {
        $this->event('purchase', array_merge(['ecommerce' => [
            'transaction_id' => $transactionId,
            'affiliation' => $affiliation,
            'currency' => $currency,
            'value' => $value,
            'tax' => $tax,
            'shipping' => $shipping,
            'coupon' => $coupon,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function refund(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, string $coupon, array $items, array $variables = []): self
    {
        $this->event('refund', array_merge(['ecommerce' => [
            'transaction_id' => $transactionId,
            'affiliation' => $affiliation,
            'currency' => $currency,
            'value' => $value,
            'tax' => $tax,
            'shipping' => $shipping,
            'coupon' => $coupon,
            'items' => $items,
        ]], $variables));

        return $this;
    }

    public function get(): Collection
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = new Collection();
    }
}
