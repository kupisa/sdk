<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\helpers\Countries;
use common\models\Record;
use modules\shop\common\payment\PaymentMethod;
use modules\shop\Module;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * A way the shop delivers: "Post", "Courier", "Pickup in the store". A kind of record named by the shop, its key
 * made from the name once and kept (an order names the method by it, see `Order::$shipping_method`), with its
 * base price and the rules of its price (see `ShippingRate`: by the countries, the value of the cart and the
 * weight), a sentence for the customer, whether it is offered and its place among the methods in its meta, with
 * the countries it delivers to and the ways of payment that go with it, none of either for all. The checkout
 * offers {@see findEnabled()} that {@see deliversTo()} the country of the address, asks each one
 * {@see priceFor()} and offers the ways of payment it {@see takesPayment()}, so the checkout never learns the
 * rules.
 */
class ShippingMethod extends Record
{
    /**
     * What the delivery costs while none of its rules is met. Untyped, so a form may set it as a string;
     * validation checks it as a number.
     *
     * @var string|float|int
     */
    public $price = '0';

    /**
     * The rules of the price in their order, each as `ShippingRate::toMeta()` writes it: the first one an order
     * meets gives the price. A form sets the rows it posts, which validation turns into rules.
     *
     * @var list<array<string, mixed>>|string
     */
    public $rates = [];

    /**
     * A sentence for the customer next to the method: "Delivered in 2–3 working days."
     */
    public string $description = '';

    /**
     * Whether the checkout offers the method. Untyped, so a form may set it as "0" or "1"; validation turns it
     * into a boolean.
     *
     * @var bool|string|int
     */
    public $enabled = true;

    /**
     * The place of the method among the others, the lowest first, set by dragging it in the list of the
     * administration. Untyped like the price.
     *
     * @var int|string
     */
    public $position = 0;

    /**
     * The ISO 3166-1 codes of the countries the method delivers to; empty for every country the shop delivers
     * to. An empty string while a form posts an empty list (see `PickList`), until validation makes a list of it.
     *
     * @var list<string>|string
     */
    public $countries = [];

    /**
     * The names of the ways of payment that go with the method (see `PaymentMethod::name()`); empty for every way
     * the shop offers. An empty string like the countries.
     *
     * @var list<string>|string
     */
    public $payment_methods = [];

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
     * Turns the rows of the rules a form posted into rules, each checked on its own: an error of one names the
     * rule by its number, and the rules keep their errors for the form to show (see {@see getRates()}).
     */
    public function validateRates(string $attribute): void
    {
    }

    /**
     * The rules of the price in their order: those the form just checked, with their errors, or those the method
     * keeps.
     *
     * @return list<ShippingRate>
     */
    public function getRates(): array
    {
    }

    /**
     * What the delivery costs for an order of the given value (as the shop writes its prices), weight in
     * kilograms and country of delivery: the price of the first rule the order meets, or the base price.
     */
    public function priceFor(float $subtotal, float $weight, string $country): float
    {
    }

    /**
     * Whether the method delivers to a country, by its code: every country while it names none.
     */
    public function deliversTo(string $country): bool
    {
    }

    /**
     * Whether a way of payment goes with the method, by its name: every way while it names none.
     */
    public function takesPayment(string $name): bool
    {
    }

    /**
     * Whether the checkout offers the method.
     */
    public function isEnabled(): bool
    {
    }

    /**
     * The countries a method may be limited to, their names by code: those the shop delivers to (Settings ›
     * Delivery, see `Module::shippingCountries()`), or every country while it named none.
     *
     * @return array<string, string>
     */
    public static function countryOptions(): array
    {
    }

    /**
     * The ways of payment a method may be limited to, by name, each called as the administration calls it.
     *
     * @return array<string, string>
     */
    public static function paymentOptions(): array
    {
    }

    /**
     * Every method of the shop in the order of their positions, then their names: what the administration lists.
     * A shop has a handful, so they are sorted here rather than in the database, where the position lives in
     * the meta.
     *
     * @return list<static>
     */
    public static function findAllOrdered(): array
    {
    }

    /**
     * The place of a new method: after every method there is, so it is added at the end of the list.
     */
    public static function nextPosition(): int
    {
    }

    /**
     * The methods the checkout offers, in their order.
     *
     * @return list<static>
     */
    public static function findEnabled(): array
    {
    }
}
