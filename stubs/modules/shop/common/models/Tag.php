<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\models\Hierarchy;
use common\models\Term;
use Yii;

/**
 * A tag of the shop ("New", "Sale", "Summer 2026"): a taxonomy without a hierarchy (see `common\models\Term`) a
 * product is put in with `setTerms()`. The products with a tag are listed on the site under `/shop/tag/<slug>`,
 * and a visitor filters the catalogue by the tags. A tag {@see $badge shown as a badge} stands on the picture of
 * every product it has in the catalogue and above the title of the product, in its own colours.
 */
class Tag extends Term
{
    /**
     * Whether the tag stands as a badge on the products it has. Untyped, so a form may send it as "0" or "1";
     * validation turns it into a boolean.
     *
     * @var bool|string|int
     */
    public $badge = false;

    /**
     * The colour of the badge, as `#rrggbb`.
     */
    public string $background = '#000000';

    /**
     * The colour of the text of the badge, as `#rrggbb`.
     */
    public string $color = '#ffffff';

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
    public static function hierarchy(): Hierarchy
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
     * The tags with a published product, in their order (see `order()`): what the sitemap lists. A tag no product
     * has, or drafts only, is not among them, nor a hidden one.
     *
     * @return list<static>
     */
    public static function findWithProducts(): array
    {
    }

    /**
     * Whether the tag stands as a badge on its products on the site: it says so and is visible.
     */
    public function isBadge(): bool
    {
    }

    /**
     * The colours of the badge as the `style` of its element on the site: the custom properties the stylesheet
     * of the shop draws a badge with (`.shop-badge`).
     */
    public function badgeStyle(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * The colour of a badge, so the list tells the badges apart.
     */
    public function swatch(): array|null
    {
    }

    /**
     * The address of the tag on the site, e.g. `https://my-shop.kupisa.shop/shop/tag/new`.
     */
    public function getUrl(): string
    {
    }
}
