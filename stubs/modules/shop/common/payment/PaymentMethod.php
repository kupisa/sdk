<?php

declare(strict_types=1);

namespace modules\shop\common\payment;

use common\helpers\Hook;
use common\models\SiteSetting;
use modules\shop\common\models\Order;
use ReflectionClass;
use yii\base\Model;

/**
 * A way the customer pays. Unlike a way of delivery, which the shop names, a way of payment is behaviour the
 * platform knows: cash on delivery asks for nothing, a bank transfer gives the customer the instructions, a
 * card will call a provider. So each is a class, {@see all()} lists them, and on the page of each under
 * Settings › Payment the shop turns it on, calls and describes it in its own words in place of the platform's,
 * sets what the way costs the customer (see {@see price()}) and what the way needs (see {@see settingsForm()}).
 * Everything the shop set is a setting under `shop.payment.<name>.` (`shop.payment.bank.enabled`, `.label`,
 * `.description`, `.price`, `.instructions`, and `.position`, its place in the list, set by dragging it there).
 * The checkout offers {@see enabled()} and an order names the method by its {@see name()} (see
 * `Order::$payment_method`) and keeps what it cost (see `Order::$payment_fee`).
 *
 * A module brings a way of its own (a card through a provider) through the `payment.methods` hook: it appends
 * an instance of its class to the list of the platform.
 */
abstract class PaymentMethod
{
    /**
     * The name the method is stored by: `cod`, `bank`.
     */
    abstract public function name(): string;

    /**
     * What the platform calls the method, until the shop calls it otherwise: "Cash on delivery".
     */
    abstract public function defaultLabel(): string;

    /**
     * How the platform describes the method to the customer, until the shop does otherwise: what happens when
     * they choose it.
     */
    abstract public function defaultDescription(): string;

    /**
     * Whether the shop is offering the method until it says otherwise.
     */
    abstract public function isEnabledByDefault(): bool;

    /**
     * Whether the customer pays on the site right after placing the order, through a provider (a card): the
     * checkout sends them to pay (see {@see paymentUrl()}), the page of the order offers to pay while the payment
     * is awaited, and the emails about the order wait until it is paid (see `OrderEmails`). Cash on delivery and
     * a bank transfer are paid away from the site.
     */
    public function isOnline(): bool
    {
    }

    /**
     * Whether the customer pays the order in cash when it is delivered, to the courier or at the counter: the shop
     * learns it is paid only once the delivery is done, so the order stays with its payment pending until the shop
     * marks it paid. What the modules ask for an order (see `Order::isCashOnDelivery()`), like one that hands the
     * delivery to a courier with the amount to collect. A module that brings a way of its own paid the same way
     * says so here too.
     */
    public function isCashOnDelivery(): bool
    {
    }

    /**
     * Where the customer pays the order, for a way paid online (see {@see isOnline()}): the page of the provider,
     * or a page of the module of the way. It is asked every time the customer goes to pay, so it may start a new
     * payment each time. The way marks the order paid (`Order::$payment_status`) once the provider says it is;
     * until then the order waits for its payment.
     */
    public function paymentUrl(Order $order): string
    {
    }

    /**
     * The small pictures the checkout shows next to the price of the method (the brands of the cards a card
     * takes), each an SVG with the name it stands for; none for most methods. The checkout shows the first few
     * and counts the others.
     *
     * @return list<array{label: string, svg: string}>
     */
    public function logos(): array
    {
    }

    /**
     * The form of what the customer gives for the method at the checkout (the token of a card), or null for a
     * method that asks for nothing there. While the method is chosen it is loaded and checked with the checkout
     * (see `CheckoutForm::paymentDetails()`), its first error shown under the ways of payment, and given to
     * {@see charge()}.
     */
    public function checkoutForm(): Model|null
    {
    }

    /**
     * The view that renders what the method shows at the checkout while it is chosen (the fields of a card),
     * right under the ways of payment, given the `form` (the ActiveForm) and the `model` (see
     * {@see checkoutForm()}); null for a method that shows nothing. An alias path, like {@see settingsView()}.
     *
     * The view registers the script it needs. The checkout never renders it again while the visitor fills the
     * page in, so what they typed stays; after every refresh of the page the form gets the
     * `shop:checkout-refreshed` event, and the summary names the total of the order and its currency
     * (`data-checkout-amount`, `data-checkout-currency`), so the script follows what the order comes to.
     */
    public function checkoutView(): string|null
    {
    }

    /**
     * Takes the payment for the order the checkout is placing, given what the customer gave at the checkout (see
     * {@see checkoutForm()}). It runs inside the transaction that places the order, right before the cart is
     * emptied and the order stored for good: false,
     * with the reason as an error on `$details`, refuses the order (a card declined), so nothing is placed and
     * the customer stays on the checkout with the reason. True when the payment went through (the method marks
     * the order paid) or waits for the customer (3D Secure: the order then awaits its payment, and the checkout
     * sends them to {@see paymentUrl()}). A method paid away from the site takes nothing here.
     */
    public function charge(Order $order, Model|null $details): bool
    {
    }

    /**
     * Gives back what {@see charge()} took when the order could not be stored after all, so the customer never
     * pays for an order that is not there.
     */
    public function cancelCharge(Order $order): void
    {
    }

    /**
     * The form of the page of the method in the administration: its label and description, and the settings of
     * its own after them (see {@see PaymentMethodForm}).
     */
    public function settingsForm(): PaymentMethodForm
    {
    }

    /**
     * The view that renders the fields of the settings of the method's own, after the label and the
     * description, given the `form` (the ActiveForm) and the `model` (the settings form); null for a method
     * without settings of its own. An alias path, so a module may bring one with its method.
     */
    public function settingsView(): string|null
    {
    }

    /**
     * Every way of payment the platform knows, in the order the shop dragged them into (see {@see sort()}), the
     * ways it has not placed yet after them in the order of the platform.
     *
     * @return list<PaymentMethod>
     */
    public static function all(): array
    {
    }

    /**
     * Keeps the order the shop dragged the ways into, given their names: each is given its place as the setting
     * `shop.payment.<name>.position`, the first 0. A name of no way is passed over.
     *
     * @param list<string> $names
     */
    public static function sort(array $names): void
    {
    }

    /**
     * The ways of payment the shop turned on, in their order: what the checkout offers.
     *
     * @return list<PaymentMethod>
     */
    public static function enabled(): array
    {
    }

    /**
     * The method of the given name, or null for one the platform does not know.
     */
    public static function find(string $name): PaymentMethod|null
    {
    }

    /**
     * What the customer reads as the method at the checkout: what the shop called it, or {@see defaultLabel()}.
     * The administration calls the method by {@see defaultLabel()} always, so it is found whatever the shop
     * wrote.
     */
    public function label(): string
    {
    }

    /**
     * A sentence for the customer under the label at the checkout: {@see defaultDescription()} until the shop
     * saved its own, then what it saved, even nothing, so the shop may leave the way without a sentence.
     */
    public function description(): string
    {
    }

    /**
     * Whether the shop offers the method, as it set it, or by default until it did.
     */
    public function isEnabled(): bool
    {
    }

    /**
     * What the method costs the customer, as the shop set it (a fee for cash on delivery), or nothing.
     */
    public function price(): float
    {
    }

    /**
     * The instructions the shop wrote for the customer who chooses the method; empty for a method without any.
     */
    public function instructions(): string
    {
    }

    /**
     * The place of the method among the ways of payment, the lowest first; a way the shop has not placed yet
     * (one a module brought later) comes after the others.
     */
    public function position(): int
    {
    }

    /**
     * Puts the method in the given place among the ways of payment (see {@see sort()}).
     */
    public function setPosition(int $position): void
    {
    }

    /**
     * The icon of the method in the administration: the `icons/<name>.svg` next to the class of the method (a
     * module keeps it with its class), drawn like the icons of the page editor (outline, 24 by 24, a stroke of
     * 1.75).
     */
    public function icon(): string
    {
    }

    /**
     * The name of a setting of the method: `shop.payment.bank.instructions`.
     */
    public function settingName(string $setting): string
    {
    }
}
