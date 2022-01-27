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

    public function get(): Collection
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = new Collection();
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

    /** @param TagManagerItem|array $items */
    public function viewItemList($items, array $variables = []): self
    {
        $this->event('view_item_list', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function viewItem($items, array $variables = []): self
    {
        $this->event('view_item', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function selectItem($items, array $variables = []): self
    {
        $this->event('select_item', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function viewPromotion($items, array $variables = []): self
    {
        $this->event('view_promotion', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function selectPromotion($items, array $variables = []): self
    {
        $this->event('select_promotion', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function addToWishlist(string $currency, float $value, $items, array $variables = []): self
    {
        $this->event('add_to_wishlist', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function addToCart($items, array $variables = []): self
    {
        $this->event('add_to_cart', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function removeFromCart($items, array $variables = []): self
    {
        $this->event('remove_from_cart', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function viewCart(string $currency, float $value, $items, array $variables = []): self
    {
        $this->event('view_cart', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function beginCheckout($items, array $variables = []): self
    {
        $this->event('begin_checkout', array_merge(['ecommerce' => [
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function addPaymentInfo(string $currency, float $value, string $paymentType, $items, string $coupon = '', array $variables = []): self
    {
        $this->event('add_payment_info', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'coupon' => $coupon,
            'payment_type' => $paymentType,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function addShippingInfo(string $currency, float $value, string $shippingTier, $items, string $coupon = '', array $variables = []): self
    {
        $this->event('add_shipping_info', array_merge(['ecommerce' => [
            'currency' => $currency,
            'value' => $value,
            'coupon' => $coupon,
            'shipping_tier' => $shippingTier,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function purchase(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, $items, string $coupon = '', array $variables = []): self
    {
        $this->event('purchase', array_merge(['ecommerce' => [
            'transaction_id' => $transactionId,
            'affiliation' => $affiliation,
            'currency' => $currency,
            'value' => $value,
            'tax' => $tax,
            'shipping' => $shipping,
            'coupon' => $coupon,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    public function refund(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, $items, string $coupon = '', array $variables = []): self
    {
        $this->event('refund', array_merge(['ecommerce' => [
            'transaction_id' => $transactionId,
            'affiliation' => $affiliation,
            'currency' => $currency,
            'value' => $value,
            'tax' => $tax,
            'shipping' => $shipping,
            'coupon' => $coupon,
            'items' => $this->formatItems($items),
        ]], $variables));

        return $this;
    }

    /** @param TagManagerItem|array $items */
    private function formatItems($items): array
    {
        if ($items instanceof TagManagerItem) {
            return [$items->toArray()];
        }

        if (isset($items['item_id']) || isset($items['item_name'])) {
            return [$items];
        }

        $array = [];

        foreach ($items as $item) {
            if ($item instanceof TagManagerItem) {
                array_push($array, $item->toArray());
            } else {
                array_push($array, $item);
            }
        }

        return $array;
    }
}
