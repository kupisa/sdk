<?php

declare(strict_types=1);

namespace modules\shop\frontend\blocks\products;

use common\helpers\Hook;
use common\models\Term;
use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use frontend\blocks\Link;
use modules\shop\common\models\Category;
use modules\shop\common\models\OrderItem;
use modules\shop\common\models\Product;
use modules\shop\frontend\assets\ShopAsset;
use Yii;
use yii\db\ActiveQuery;

/**
 * Products of the shop on any page, as the cards of the catalogue: the ones the site picks, those of a category,
 * the newest, the ones on sale or the ones sold most, with a title and a link to more above them; in a grid, or
 * in a carousel, one row that is swiped through or moved by its arrows. A list the block makes by itself offers
 * only what can be bought (`Product::available()`); the products the site picks are shown as long as they are
 * published, sold out or not.
 *
 * The lists that pick the products themselves (the newest, ...) are the sources of the `products.sources` hook, so
 * a module adds its own: the product reviews module the best rated, say.
 */
class ProductsBlock extends Block
{
    /**
     * The products the site picks, in its order.
     */
    public const SOURCE_PICKED = 'picked';

    /**
     * The products of a category and of the categories below it that can be bought, the latest first.
     */
    public const SOURCE_CATEGORY = 'category';

    /**
     * The products in rows of a grid, all of them in view.
     */
    public const LAYOUT_GRID = 'grid';

    /**
     * The products in one row, swiped through on a phone and moved by arrows on a wide screen, so a section of a
     * page takes one row however many products it shows.
     */
    public const LAYOUT_CAROUSEL = 'carousel';

    /**
     * The title above the products, or '' for none.
     */
    public string $title = '';

    /**
     * Where the products come from: {@see SOURCE_PICKED}, {@see SOURCE_CATEGORY} or the name of a source of
     * {@see sources()}.
     */
    public string $source = self::SOURCE_PICKED;

    /**
     * The ids of the products the site picked, in their order, for {@see SOURCE_PICKED}.
     */
    public array $products = [];

    /**
     * The id of the category, for {@see SOURCE_CATEGORY}.
     */
    public string $category = '';

    /**
     * How many products to show at most: one of {@see COUNTS}.
     */
    public int|string $count = 8;

    /**
     * The link above the products to more of them ("View all"), shown when it is filled in (see {@see Link}).
     */
    public array $link = Link::EMPTY;

    /**
     * How the products are laid out: {@see LAYOUT_GRID} or {@see LAYOUT_CAROUSEL}.
     */
    public string $layout = self::LAYOUT_GRID;

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function group(): BlockGroup
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function spacing(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function controls(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The newest products, which every shop has, under a title.
     */
    public static function presets(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The products picked name pages and the category a term of the site, so the content of a site carries them
     * as such. Named here rather than read from the controls, whose categories need a site (the reference of the
     * SDK is written without one).
     */
    public static function references(): array
    {
    }

    /**
     * The lists that pick the products themselves, by name: each with its `label` and the `products` callable
     * that gives as many as asked for (`fn (int $limit): list<Product>`, products that can be bought only, see
     * `Product::available()`). The newest, those on sale and those sold most, and what the modules add through
     * the `products.sources` hook.
     *
     * @return array<string, array{label: string, products: callable(int): list<Product>}>
     */
    public static function sources(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
