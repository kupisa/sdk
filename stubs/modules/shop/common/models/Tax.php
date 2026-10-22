<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use modules\shop\Module;
use Yii;

/**
 * VAT as the shop charges it: the settings of the shop and its rates, read once (see `Module::tax()`), and
 * everything counted from them. The shop charges VAT while it is turned on in the settings and there is a default
 * rate; otherwise every price is final and no VAT is counted or shown.
 *
 * A price is written in the administration with or without VAT, as the settings say ({@see $pricesIncludeTax});
 * {@see amount()} splits it into its VAT, counted from the side it was written on, so what the shop wrote is never
 * changed by rounding. The site shows a price with or without VAT, as the settings say
 * ({@see $showPricesWithTax}; {@see shown()}). A product is charged its own rate or the default one
 * ({@see rateFor()}); the delivery and the fee of the way of payment are charged the default rate.
 */
final class Tax
{
    /**
     * @param bool $enabled Whether the shop charges VAT: turned on, with a default rate.
     * @param bool $pricesIncludeTax Whether the prices written in the administration include VAT.
     * @param bool $showPricesWithTax Whether the site shows the prices with VAT.
     * @param array<string, TaxRate> $rates The rates of the shop by their key.
     * @param TaxRate|null $defaultRate The default rate; null while the shop charges no VAT.
     */
    public function __construct(
        public readonly bool $enabled,
        public readonly bool $pricesIncludeTax,
        public readonly bool $showPricesWithTax,
        private readonly array $rates,
        public readonly TaxRate|null $defaultRate,
    ) {
    }

    /**
     * VAT as the settings and the rates of the shop say now.
     */
    public static function load(): self
    {
    }

    /**
     * The rate a product is charged: its own, or the default one while it names none (or its rate was deleted);
     * null while the shop charges no VAT.
     */
    public function rateFor(Product $product): TaxRate|null
    {
    }

    /**
     * An amount as the shop wrote it, split into its VAT at the given rate: counted out of it when the prices
     * include VAT, added to it when they do not. No VAT at no rate.
     */
    public function amount(float $written, TaxRate|null $rate): TaxAmount
    {
    }

    /**
     * An amount with VAT split into its VAT at the given rate, in percent: what an order keeps, whose amounts are
     * all with VAT (a line the shop changed, the delivery). No VAT at no rate.
     */
    public static function fromGross(float $gross, float|null $rate): TaxAmount
    {
    }

    /**
     * An amount as the shop wrote it, as the site shows it: with or without VAT, as the settings say.
     */
    public function shown(float $written, TaxRate|null $rate): float
    {
    }

    /**
     * A discount as the shop wrote it, split into its VAT: it is taken off the given lines in proportion to what
     * each costs, so the part taken off a line is charged the rate of its product, and comes back as an amount
     * per rate. The parts are rounded to cents and the last takes what rounding left, so they add up to the
     * discount. Nothing for no discount or no lines.
     *
     * @param list<CartLine> $lines The lines the discount is taken off.
     * @return list<TaxAmount>
     */
    public function discount(float $written, array $lines): array
    {
    }

    /**
     * The few words next to a price on the site that say it has no VAT in it ("excl. VAT"), while the shop shows
     * its prices without VAT; empty otherwise, since a price is taken to include VAT unless it says so.
     */
    public function note(): string
    {
    }

    /**
     * The VAT of the given amounts added up by rate, the highest rate first: what an order says it holds. An
     * amount without VAT is left out.
     *
     * @param list<TaxAmount> $amounts
     * @return list<array{rate: string, amount: string}> The rate and the VAT as decimals with two places.
     */
    public static function summarize(array $amounts): array
    {
    }
}
