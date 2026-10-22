<?php

declare(strict_types=1);

namespace modules\shop\common\models;

/**
 * An amount of the shop split into its VAT: what it is without VAT, the VAT in it and what it is with VAT, and
 * the rate it was charged, in percent (null when no VAT was charged, so the three are the same). Made by
 * {@see Tax::amount()}.
 */
final class TaxAmount
{
    public function __construct(
        public readonly float $net,
        public readonly float $tax,
        public readonly float $gross,
        public readonly float|null $rate,
    ) {
    }
}
