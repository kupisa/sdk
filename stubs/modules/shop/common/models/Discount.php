<?php

declare(strict_types=1);

namespace modules\shop\common\models;

/**
 * A discount a module gives on what a cart holds (a coupon, the discount of a customer), through the
 * `cart.discounts` hook (see `Cart::getDiscounts()`): its label, as the customer reads it ("Coupon SUMMER10"),
 * and the amount taken off, as the shop writes its prices (with or without VAT, see `Tax`). It is taken off the
 * products that may be discounted only (see `Product::isDiscountable()`), never off the delivery or the payment
 * fee, and the order keeps it as it was (see `Order::$discounts`).
 */
final class Discount
{
    /**
     * @param string $source Who gives it: the name of the module.
     * @param string $label What the customer reads next to the amount.
     * @param float $amount What is taken off, as the shop writes its prices.
     * @param array<string, mixed> $data What the module keeps with it on the order (the code of a coupon).
     */
    public function __construct(
        public readonly string $source,
        public readonly string $label,
        public readonly float $amount,
        public readonly array $data = [],
    ) {
    }
}
