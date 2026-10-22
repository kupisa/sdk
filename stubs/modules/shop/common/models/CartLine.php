<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use modules\shop\Module;

/**
 * One line of a cart as the site shows it: a variant with its product, and how many of it the visitor wants.
 * Built by {@see Cart::getLines()} from what the cart keeps, with the price read from the variant as it is now.
 */
final class CartLine
{
    public function __construct(
        public readonly Variant $variant,
        public readonly Product $product,
        public readonly int $quantity,
    ) {
    }

    /**
     * What the line costs as the shop wrote it: the price of the variant times the quantity. What an order is
     * counted from.
     */
    public function getTotal(): float
    {
    }

    /**
     * What the line costs as the site shows it: with or without VAT, as the settings of the shop say (see
     * `Tax::shown()`).
     */
    public function getShownTotal(): float
    {
    }
}
