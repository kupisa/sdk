<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\models\Record;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * A rate of VAT the shop charges: "Standard rate" 20%, "Reduced rate" 10%. A kind of record named by the shop,
 * its key made from the name once and kept (a product names its rate by it, see `Product::$tax_rate`), with the
 * rate in percent and whether it is the default one in its meta. A product without a rate of its own, the
 * delivery and the fee of a way of payment are charged the default rate (see {@see findDefault()}); one rate is
 * the default at a time, and it cannot be deleted.
 *
 * The rates are named, not typed on every product, so a rate that changes by law changes in one place.
 */
class TaxRate extends Record
{
    /**
     * The rate in percent, e.g. `20`. Untyped, so a form may set it as a string; validation checks it as a number.
     *
     * @var string|float|int
     */
    public $rate = '0';

    /**
     * Whether the rate is the default one. Untyped, so a form may set it as "0" or "1"; validation turns it into
     * a boolean.
     *
     * @var bool|string|int
     */
    public $is_default = false;

    /**
     * {@inheritdoc}
     */
    public static function type(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function labelPlural(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The first rate of the shop is the default one, whatever the form said.
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * {@inheritdoc}
     *
     * A rate made the default one takes it from the rate that was.
     */
    public function afterSave($insert, $changedAttributes): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * The default rate stays: another rate is made the default one first.
     */
    public function beforeDelete(): bool
    {
    }

    /**
     * Whether the rate is the default one.
     */
    public function isDefault(): bool
    {
    }

    /**
     * The rate as a percent in the language of the site: "20%", "9.5%" ("20 %" on a Slovak site).
     */
    public function getRateLabel(): string
    {
    }

    /**
     * A rate in percent as it is read in the language of the site: "20%", "9.5%" ("20 %" on a Slovak site).
     */
    public static function formatRate(float $rate): string
    {
    }

    /**
     * What the rate is called with its percent: "Standard rate (20%)".
     */
    public function getLabel(): string
    {
    }

    /**
     * Every rate of the shop, the default one first, then the highest rate: what the administration lists. A shop
     * has a handful, so they are sorted here rather than in the database, where the rate lives in the meta.
     *
     * @return list<static>
     */
    public static function findAllOrdered(): array
    {
    }

    /**
     * The default rate of the shop, or null while it has no rates.
     */
    public static function findDefault(): static|null
    {
    }

    /**
     * The rates by their key with their label, for a choice of a rate.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
    }
}
