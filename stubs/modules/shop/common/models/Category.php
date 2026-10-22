<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\models\Term;
use Yii;

/**
 * A category of the shop: a taxonomy with a hierarchy, so a category may have sub-categories ("Clothes" with
 * "Shirts" and "Trousers" under it). A product is put in categories with `setTerms()`.
 */
class Category extends Term
{
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
     * The categories with a published product in them and, so that the tree holds together, the ones above them,
     * in their order (see `order()`): what the site lists next to the products. A category left empty, or with
     * drafts only, is not among them, nor a hidden one or one below it.
     *
     * @return list<static>
     */
    public static function findWithProducts(): array
    {
    }

    /**
     * Categories as a tree, for a list on the site to render level by level: every category of the given ones
     * below the given parent (the root ones for none), each with the ones below it, in their order. A category
     * left out of the given ones takes what is below it with it.
     *
     * @param list<static> $categories
     * @return list<array{category: static, children: list<array>}>
     */
    public static function tree(array $categories, int|null $parent = null): array
    {
    }

    /**
     * The address of the category on the site, e.g. `https://my-shop.kupisa.shop/shop/category/shirts`.
     */
    public function getUrl(): string
    {
    }
}
