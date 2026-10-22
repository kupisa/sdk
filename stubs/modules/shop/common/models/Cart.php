<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\helpers\Hook;
use common\models\SiteRecord;
use modules\shop\Module;
use Yii;

/**
 * The cart of a visitor: which variants they want and how many of each. The visitor is known by the token of
 * the cart, kept in their cookie (see `modules\shop\frontend\CurrentCart`), and by the user once they log in.
 *
 * The lines live in the meta of the cart as {@see $items}, so a cart keeps no price: the price is read from the
 * variant when the cart is shown, and fixed only when it becomes an order. What the visitor filled in on the
 * checkout so far is there too, as {@see $checkout}, so the checkout goes on where they left it. Another module
 * keeps what it adds to a cart (a coupon) in the meta under its own prefix, as on a page (see
 * `common\behaviors\MetaBehavior`).
 *
 * @property int $id
 * @property string $token The random token of the cookie of the visitor, unique within the site.
 * @property int|null $user_id The user the cart belongs to, once known.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
class Cart extends SiteRecord
{
    /**
     * How long a cart is kept since it last changed, in days: as long as the cookie of the visitor.
     */
    public const DAYS_KEPT = 30;

    /**
     * The lines of the cart: the id of a variant and how many of it, in the order they were added.
     *
     * @var list<array{variant_id: int, quantity: int}>
     */
    public array $items = [];

    /**
     * What the visitor filled in on the checkout so far, by attribute of `frontend\models\CheckoutForm`, never
     * the password; empty until they did, and again once the order is placed.
     *
     * @var array<string, mixed>
     */
    public array $checkout = [];

    /**
     * The discount code the visitor applied (see {@see applyCode()}); empty for none.
     */
    public string $discount_code = '';

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
     * A new cart with a token of its own, for the given user when there is one.
     */
    public static function create(int|null $userId): self
    {
    }

    /**
     * Drops the carts that did not change for {@see DAYS_KEPT} days: their cookies are gone too.
     */
    public static function pruneOld(): void
    {
    }

    /**
     * Puts more of a variant into the cart: on top of what is there of it, or as a new line. The cart has to be
     * saved for it to be kept.
     */
    public function add(Variant $variant, int $quantity): void
    {
    }

    /**
     * Sets how many of a variant the cart holds; none takes the line out.
     */
    public function setQuantity(int $variantId, int $quantity): void
    {
    }

    public function remove(int $variantId): void
    {
    }

    /**
     * How many of a variant the cart holds; none when it is not in the cart.
     */
    public function getQuantity(int $variantId): int
    {
    }

    /**
     * Whether the cart holds nothing.
     */
    public function isEmpty(): bool
    {
    }

    /**
     * How many pieces the cart holds, all lines together: the number the header shows.
     */
    public function getCount(): int
    {
    }

    /**
     * The lines of the cart with their variants and products, in the order of the items. A line whose variant is
     * gone is left out; {@see clean()} takes such lines out of the cart itself.
     *
     * @return list<CartLine>
     */
    public function getLines(): array
    {
    }

    /**
     * What the cart costs as the shop wrote the prices, every line together, before anything the checkout adds:
     * what the delivery is counted from.
     */
    public function getTotal(): float
    {
    }

    /**
     * What the cart costs as the site shows it, every line together: with or without VAT, as the settings of the
     * shop say.
     */
    public function getShownTotal(): float
    {
    }

    /**
     * The lines a discount may be taken off: those of the products that may be discounted (see
     * `Product::isDiscountable()`).
     *
     * @return list<CartLine>
     */
    public function getDiscountableLines(): array
    {
    }

    /**
     * What the lines a discount may be taken off cost, as the shop wrote the prices: what a discount in percent
     * is counted from, and the most any discount takes off.
     */
    public function getDiscountableTotal(): float
    {
    }

    /**
     * The discounts the modules give on the cart, through the `cart.discounts` hook: a handler gets the list so
     * far, the cart and the email address the order is placed with (null while it is not known), and returns the
     * list with its own discount added (see `Discount`). Together they never take off more than
     * {@see getDiscountableTotal()}: a discount beyond it is cut down, and one with nothing left is dropped.
     *
     * @return list<Discount>
     */
    public function getDiscounts(string|null $email = null): array
    {
    }

    /**
     * The discounts of the cart as the site shows them: the label of each and what it takes off, with or without
     * VAT as the shop shows its prices (see `Tax::discount()`).
     *
     * @return list<array{label: string, amount: float}>
     */
    public function getShownDiscounts(string|null $email = null): array
    {
    }

    /**
     * What the cart weighs in kilograms: the weight of every product times how many of it, a product without a
     * weight counted as nothing.
     */
    public function getWeight(): float
    {
    }

    /**
     * What a way of delivery costs for the cart sent to a country (by its code), as the shop wrote the price: as
     * the method says for the subtotal and the weight of the cart (see `ShippingMethod::priceFor()`), passed
     * through the `shipping.price` hook, whose handler gets the price, the method, the cart and the country and
     * returns the price (none for a code that gives free delivery).
     */
    public function shippingPrice(ShippingMethod $method, string $country): float
    {
    }

    /**
     * Whether the shop takes discount codes: while a module that knows them (a coupon) is enabled. The cart and
     * the checkout show the field of the code only then.
     */
    public static function takesCodes(): bool
    {
    }

    /**
     * Checks a discount code for the cart, through the `discount.code` hook: a handler gets the error so far
     * (at first that no such code exists), the code, the cart and the email address the order is placed with
     * (null while it is not known), and returns null when the code is its own and applies, its own error when it
     * is its own and does not apply (it expired, it was used up), or the error it got for a code it does not know.
     * Null when the code applies, the error for the visitor otherwise.
     */
    public function checkCode(string $code, string|null $email = null): string|null
    {
    }

    /**
     * Applies a discount code to the cart, in place of the one it had, when it applies (see {@see checkCode()}).
     * Null when it was applied, the error for the visitor otherwise. The cart has to be saved for it to be kept.
     */
    public function applyCode(string $code, string|null $email = null): string|null
    {
    }

    /**
     * Brings the cart in line with what can be bought now: a line whose variant is gone, whose product is not
     * published any more or which cannot be bought is taken out, a line asking for more than there is in stock is
     * cut down to it, and a quantity the product is not ordered in (below its minimum, or between its steps) is
     * raised to the next one it is. A discount code that no longer applies (it expired, the module that knew
     * it was disabled) is taken off. Returns what was changed, a sentence per line for the visitor, and saves the
     * cart when anything was.
     *
     * @return list<string>
     */
    public function clean(): array
    {
    }

    /**
     * Takes the lines of another cart (the one a user had before they logged in elsewhere) into this one, on
     * top of its own, with its discount code while this one has none, and drops the other cart.
     */
    public function merge(Cart $other): void
    {
    }
}
