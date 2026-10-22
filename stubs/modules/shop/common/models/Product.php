<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\helpers\Hook;
use common\models\Media;
use common\models\Page;
use common\models\SiteQuery;
use common\models\Term;
use common\models\TermRelation;
use modules\shop\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\HtmlPurifier;

/**
 * A product of the shop: a kind of page with its pictures and its options in the meta of the page (see
 * `common\behaviors\MetaBehavior`), so its description is built in the page editor like the body of any page,
 * and what it sells in its variants (see {@see Variant}): the price, the SKU, the stock and the availability
 * live there, never on the product. A product without options has one variant; a product with options (the
 * attributes it comes in different values of, see {@see $options}) has one per combination of their values.
 * Its categories are terms (see {@see Category}), given with `setTerms()` and read with `getTerms()` like on any
 * page.
 *
 * Products are listed in the administration under Products and on the site under `/shop`.
 *
 * @property-read Variant[] $variants
 */
class Product extends Page
{
    /**
     * The ids of the pictures of the product in the media library, in their order; the first one is the main
     * picture, shown in the lists. Untyped, so a form may send it as an empty string when there is none;
     * validation turns it into a list of numbers.
     *
     * @var list<int>|string
     */
    public $images = [];

    /**
     * The ids of the attributes the product comes in different values of ("Colour", "Size"), in the order the
     * site shows them; three at most. Empty for a product sold as it is. Untyped like {@see $images}.
     *
     * @var list<int>|string
     */
    public $options = [];

    /**
     * The key of the rate of VAT the product is charged (see {@see TaxRate}); empty for the default rate of the
     * shop. Read with {@see getTaxRate()}.
     */
    public string $tax_rate = '';

    /**
     * A few words on the product, shown next to its price on the site, as HTML with bold, italic, links and
     * lists (see `backend\widgets\richtexteditor\RichTextEditor`); cleaned as it is validated. Empty for none.
     */
    public string $short_description = '';

    /**
     * The ids of the products the shop chose to offer next to this one ("You may also like"), in their order;
     * empty to leave it to the shop (see {@see getRelated()}). Untyped like {@see $images}.
     *
     * @var list<int>|string
     */
    public $related = [];

    /**
     * The fewest a visitor may order of the product, 1 unless the shop sets more. Untyped, so a form may set it
     * as a string; validation turns it into a number. Read with {@see minQuantity()}.
     *
     * @var int|string
     */
    public $min_quantity = 1;

    /**
     * How many more an order may have above the minimum at a time, 1 unless the shop sets more: with a minimum of
     * 5 and a step of 3, an order has 5, 8, 11 and so on. Untyped like {@see $min_quantity}. Read with
     * {@see quantityStep()}.
     *
     * @var int|string
     */
    public $quantity_step = 1;

    /**
     * The unit the price is for, one of the keys of {@see units()} ("kg" for a price per kilogram), shown after
     * the price on the site; empty for none.
     */
    public string $unit = '';

    /**
     * What the product weighs in kilograms, 0.25 for 250 grams, for the delivery; null while it is not known.
     * Untyped, so a form may set it as a string; validation checks it as a number.
     *
     * @var float|string|null
     */
    public $weight = null;

    /**
     * How many options a product may have at most.
     */
    public const MAX_OPTIONS = 3;

    /**
     * The card of the product puts it into the cart (see {@see cardAction()}).
     */
    public const CARD_ADD = 'add';

    /**
     * The card of the product leads to its page to choose its options (see {@see cardAction()}).
     */
    public const CARD_OPTIONS = 'options';

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
    public static function capability(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * What a product sells lives in its variants, in a table of their own.
     */
    public static function inDemo(): bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function references(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * Its categories, its tags and, as values of the attributes, its specifications.
     */
    public static function taxonomies(): array
    {
    }

    /**
     * The published products, the latest first: what the site shows.
     *
     * @return SiteQuery<static>
     */
    public static function published(): ActiveQuery
    {
    }

    /**
     * The published products that can be bought, the latest first: those with a variant that can be bought (see
     * `Variant::availableCondition()`), what a list the site makes by itself offers.
     *
     * @return SiteQuery<static>
     */
    public static function available(): ActiveQuery
    {
    }

    /**
     * The products on sale that can be bought, the latest first: those with a variant that can be bought at a
     * price below the price it is compared with (the old price, shown struck through).
     *
     * @return SiteQuery<static>
     */
    public static function onSale(): ActiveQuery
    {
    }

    /**
     * The ids of the terms of a kind (the categories) with a published product in them: what the site offers a
     * visitor, see `Category::findWithProducts()`.
     *
     * @param class-string<Term> $class
     * @return list<int>
     */
    public static function termIdsWithProducts(string $class): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * An option whose attribute was deleted is dropped as the product is read, so the site and the editor show
     * the options that are left; the product is stored without it the next time it is saved.
     */
    public function afterFind(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
    }

    /**
     * Every option is an attribute (a term at the root of the attributes), and there are no more than allowed.
     */
    public function validateOptions(string $attribute): void
    {
    }

    /**
     * Whether the product comes in different values of some attributes, so it has a variant per combination.
     */
    public function hasOptions(): bool
    {
    }

    /**
     * The attributes of the product: the values of attributes it has itself ("Material: cotton, elastane"),
     * beside the options its variants come in, grouped by their attribute in the order of the attributes. They
     * are terms of the product (see `TermsBehavior`), the values only. A hidden attribute or value is among them:
     * hiding one leaves it out of the filters of the catalogue only, and the page of a product shows all it has.
     *
     * @return list<array{attribute: Attribute, values: list<Attribute>}>
     */
    public function getSpecifications(): array
    {
    }

    /**
     * Gives the product the values of attributes it has itself, by id, stored when it is saved. Anything but a
     * value, and a value of an attribute the options of the product use, is left out: an attribute is either an
     * option or an attribute of the product. Call it after the options are loaded.
     *
     * @param list<int|string> $valueIds
     */
    public function setSpecifications(array $valueIds): void
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
     * The units a price may be for, with their names, by key.
     *
     * @return array<string, string>
     */
    public static function units(): array
    {
    }

    /**
     * The short name of the unit the price is for, as the site shows it after the price ("kg", "pc"); empty
     * without a unit.
     */
    public function unitSymbol(): string
    {
    }

    /**
     * The fewest a visitor may order of the product.
     */
    public function minQuantity(): int
    {
    }

    /**
     * How many more an order may have above the minimum at a time.
     */
    public function quantityStep(): int
    {
    }

    /**
     * Whether a visitor may order the given quantity: the minimum, or more by whole steps.
     */
    public function allowsQuantity(int $quantity): bool
    {
    }

    /**
     * The quantity a visitor may order that is closest to the given one from above: the minimum for less, and the
     * next whole step for a quantity in between (with a minimum of 5 and a step of 3, 7 becomes 8).
     */
    public function allowedQuantity(int $quantity): int
    {
    }

    /**
     * The most a visitor may order when there is no more than the given limit (the stock): the largest allowed
     * quantity not above it, or none when the limit is below the minimum.
     */
    public function allowedQuantityUpTo(int $limit): int
    {
    }

    /**
     * What a visitor reads about the quantities the product is ordered in: "Minimum order: 5" or "Ordered in
     * quantities of 5, 8, 11 …"; empty while any quantity may be ordered.
     */
    public function quantityNote(): string
    {
    }

    /**
     * The first quantities a visitor may order, for a sentence: "5, 8, 11 …".
     */
    public function quantityExamples(): string
    {
    }

    /**
     * The short description with only what the editor writes, or empty when no text is left.
     */
    public static function cleanShortDescription(string $html): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * The short description is searched too: its text comes before the text of the blocks.
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * Whether a discount (a coupon, the discount of a customer) may be taken off the product: every product may,
     * unless a module says otherwise through the `product.discountable` hook (see `Discount`).
     */
    public function isDiscountable(): bool
    {
    }

    /**
     * The rate of VAT the product is charged: its own, or the default rate of the shop while it has none (or its
     * rate was deleted); null while the shop has no rates.
     */
    public function getTaxRate(): TaxRate|null
    {
    }

    /**
     * The variants of the product in their order.
     *
     * @return ActiveQuery<Variant>
     */
    public function getVariants(): ActiveQuery
    {
    }

    /**
     * The variant a product without sizes or colours sells: its first one, whose price, SKU and stock the form
     * of the product edits. A new product gets a new one, stored with the product (see the product controller).
     */
    public function getDefaultVariant(): Variant
    {
    }

    /**
     * The attributes the product comes in different values of, in the order of its options, each with the values
     * its variants actually have (a size no variant comes in is not offered). Empty for a product without options.
     *
     * @return list<array{attribute: Attribute, values: list<Attribute>}>
     */
    public function getOptionValues(): array
    {
    }

    /**
     * The variant that has exactly the given values (the ids of one value of every option, in any order), or
     * null when no variant comes in that combination. Given no values, the variant of a product without options.
     *
     * @param list<int> $valueIds
     */
    public function findVariantByValues(array $valueIds): Variant|null
    {
    }

    /**
     * The variant the page of the product opens on: the one asked for by id while it is the product's (a link
     * from the cart), otherwise the first one that can be bought, or the first one when none can.
     */
    public function getVariantToShow(int|null $id): Variant
    {
    }

    /**
     * What the card of the product in a list offers while the shop shows a button there (`Module::cardButton()`):
     * {@see CARD_ADD}, the product put into the cart from the list, for one that comes in one kind,
     * {@see CARD_OPTIONS}, the way to its page to choose, for one with options, or null for none, also when none of
     * it can be bought now.
     */
    public function cardAction(): string|null
    {
    }

    /**
     * Whether a visitor may buy the product now: any of its variants can be bought (see `Variant::isAvailable()`).
     */
    public function isAvailable(): bool
    {
    }

    /**
     * The availability of the product as a whole, as a word for a class name, from its variants: `in-stock` while
     * any variant is in stock, `on-order` while any is sold on order, `sold-out` otherwise (see
     * `Variant::availabilityState()`).
     */
    public function availabilityState(): string
    {
    }

    /**
     * The availability of the product in words, for the state of {@see availabilityState()}.
     */
    public function availabilityLabel(): string
    {
    }

    /**
     * How many of the product are in stock, the variants that count their stock added up; null while none of them
     * counts it.
     */
    public function getStock(): int|null
    {
    }

    /**
     * The variant with the lowest price: what a card of the catalogue shows, "from" that price while the variants
     * differ in price (see {@see pricesDiffer()}).
     */
    public function getCheapestVariant(): Variant
    {
    }

    /**
     * Whether the variants of the product are not all sold at the same price.
     */
    public function pricesDiffer(): bool
    {
    }

    /**
     * The products the shop chose to offer next to this one, in their order (see {@see $related}); a product that
     * was deleted is left out, and so is one not published when only those are asked for, as on the site.
     *
     * @return list<static>
     */
    public function getChosenRelated(bool $publishedOnly = true): array
    {
    }

    /**
     * The products a visitor is offered next to this one ("You may also like"): the ones the shop chose, in their
     * order, and, when those are fewer than asked for, the published products that share a category or a tag
     * with it, the latest first, then the latest of the others. Never the product itself.
     *
     * @return list<static>
     */
    public function getRelated(int $count = 4): array
    {
    }

    /**
     * The tags of the product that stand as badges on it on the site ("New"), in their order (see
     * `Tag::isBadge()`); read once.
     *
     * @return list<Tag>
     */
    public function getBadges(): array
    {
    }

    /**
     * What the modules add to the card of the product in the catalogue, under its title, as HTML (the
     * `product.card` hook, a filter given the product): the stars of its reviews. Empty for nothing.
     */
    public function cardExtras(): string
    {
    }

    /**
     * What the modules add to the page of the product, under its title, as HTML (the `product.summary` hook, a
     * filter given the product): how its customers rate it. Empty for nothing.
     */
    public function summaryExtras(): string
    {
    }

    /**
     * The main picture of the product from the media library, or null when the product has none (or it was
     * deleted).
     */
    public function getImageMedia(): Media|null
    {
    }

    /**
     * The main picture of the product, or the stand-in of the platform while it has none: what a card or a line of
     * the cart shows.
     */
    public function getMainImage(): Media
    {
    }

    /**
     * The pictures of the product for its gallery, or the stand-in of the platform alone while it has none.
     *
     * @return list<Media>
     */
    public function getGallery(): array
    {
    }

    /**
     * The category the way to the product leads through: the most specific one it is in (the deepest, a shirt in
     * "Clothes" and in "Shirts" under it comes as Shop › Clothes › Shirts), or null when it is in none.
     */
    public function getMainCategory(): Category|null
    {
    }

    /**
     * The pictures of the product from the media library, in their order; a picture that was deleted is left out.
     *
     * @return list<Media>
     */
    public function getImages(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getEditUrl(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * A product is previewed in its own look (see `frontend\controllers\ProductController::actionPreview()`).
     */
    public function getPreviewUrl(): string
    {
    }
}
