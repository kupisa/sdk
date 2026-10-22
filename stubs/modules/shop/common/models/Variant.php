<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TermsBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use common\models\Media;
use common\models\SiteRecord;
use modules\shop\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * What a product sells: its price, the price it is compared with (the old price, shown struck through), its
 * SKU, its stock and its availability. Every product has at least one variant (see {@see Product::getDefaultVariant()});
 * a product that comes in sizes or colours has one per combination of the values of its options (see
 * `Product::$options`), each value a term of the attributes (see {@see Attribute}) the variant is put in with
 * `setTerms()`, and whatever is put in a cart or ordered is a variant, never the product itself. A variant may
 * have a picture of its own from the media library. What a module keeps about a variant lives in its meta under
 * the prefix of the module (see `common\behaviors\MetaBehavior`), edited with a section of the form of the
 * variant (the `variant.form` hook).
 *
 * @property int $id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property int $page_id The product, a page of the `product` kind.
 * @property string|null $sku The stock keeping unit, unique within the site; null without one.
 * @property string $price A decimal with two places, as the database gives it.
 * @property string|null $compare_price The price the product is compared with, higher than the price when on sale.
 * @property int|null $stock How many are in stock; null when the stock is not tracked.
 * @property string $availability One of the `AVAILABILITY_*` constants.
 * @property int|null $image_id The picture of the variant in the media library; null for the main one of the product.
 * @property int $position The place among the variants of the product.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @property-read Product|null $product
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 * @method Attribute[] getTerms(string $class) See `TermsBehavior::getTerms()`.
 * @method void setTerms(string $class, array $ids) See `TermsBehavior::setTerms()`.
 */
class Variant extends SiteRecord
{
    /**
     * Sold while in stock, or always when the stock is not tracked.
     */
    public const AVAILABILITY_IN_STOCK = 'in_stock';

    /**
     * Sold even without stock, delivered later: a pre-order, or goods ordered from the supplier on demand.
     */
    public const AVAILABILITY_ON_ORDER = 'on_order';

    /**
     * Shown, but not sold: there is none, whatever the count says.
     */
    public const AVAILABILITY_OUT_OF_STOCK = 'out_of_stock';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
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
     * The kinds of availability with their labels, by value.
     *
     * @return array<string, string>
     */
    public static function availabilityLabels(): array
    {
    }

    /**
     * @return ActiveQuery<Product>
     */
    public function getProduct(): ActiveQuery
    {
    }

    /**
     * The values of the variant, one per option of its product, in the order of the options: "Red" and "M" for a
     * shirt that comes in colours and sizes. None for the variant of a product without options.
     *
     * @return list<Attribute>
     */
    public function getValues(): array
    {
    }

    /**
     * What the variant is called after its values: "Red / M"; empty for the variant of a product without options.
     */
    public function getTitle(): string
    {
    }

    /**
     * The address of the variant on the site: the page of its product, opened on the variant while the product
     * has options (`?variant=<id>`, see `Product::getVariantToShow()`).
     */
    public function getUrl(): string
    {
    }

    /**
     * The picture of the variant from the media library, or null when it has none of its own (or it was deleted).
     */
    public function getImageMedia(): Media|null
    {
    }

    /**
     * The picture of the variant, or the main one of its product while it has none of its own, or the stand-in
     * of the platform: what a line of the cart shows.
     */
    public function getMainImage(): Media
    {
    }

    /**
     * Whether a visitor may buy the variant now: it is on order, or in stock by its status and its count (or not
     * counted), with at least the minimum its product is ordered in (see `Product::minQuantity()`).
     * The catalogue and the cart ask this, never the availability or the stock themselves.
     */
    public function isAvailable(): bool
    {
    }

    /**
     * The variants that can be bought, as a condition for a query: what {@see isAvailable()} answers, in SQL,
     * for the filters of the catalogue. The minimum of the product is read from its meta.
     *
     * @return array The condition, in the array format of `yii\db\Query::where()`.
     */
    public static function availableCondition(): array
    {
    }

    /**
     * What a visitor reads about the availability: sold out when the variant cannot be bought, whatever the
     * reason, on order when it is sold on order, in stock otherwise.
     */
    public function availabilityLabel(): string
    {
    }

    /**
     * The availability as a visitor sees it, as a word for a class name: `in-stock`, `on-order` or `sold-out`
     * (see {@see availabilityLabel()}).
     */
    public function availabilityState(): string
    {
    }

    /**
     * How many of the variant a visitor may buy at most: what is in stock while it is counted and the variant is
     * sold in stock only, as much of it as the product is ordered in (see `Product::allowedQuantityUpTo()`), none
     * when it cannot be bought, or null when there is no limit (the stock is not counted, or the variant is sold
     * on order).
     */
    public function maxQuantity(): int|null
    {
    }

    /**
     * The price as the site shows it: with or without VAT, as the settings of the shop say, at the rate of its
     * product (see `Tax::shown()`).
     */
    public function getShownPrice(): float
    {
    }

    /**
     * The price the variant is compared with as the site shows it, like {@see getShownPrice()}; null without one.
     */
    public function getShownComparePrice(): float|null
    {
    }

    /**
     * Whether the variant is sold cheaper than the price it is compared with.
     */
    public function isOnSale(): bool
    {
    }
}
