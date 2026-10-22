<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use common\helpers\Countries;
use common\helpers\Hook;
use common\models\SiteRecord;
use common\models\User;
use modules\shop\common\mail\OrderEmails;
use modules\shop\common\payment\PaymentMethod;
use modules\shop\Module;
use Yii;
use yii\helpers\Html;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * An order: what a customer bought, with the prices and the names fixed as they were when it was placed (see
 * {@see OrderItem}). It is made from a cart with {@see fromCart()}, given the customer by the checkout, and
 * placed with {@see place()}, which numbers it within the site, stores it with its lines and takes the stock.
 * Cancelling it (the status set to {@see STATUS_CANCELLED} and saved) gives the stock back, and taking it up
 * again takes the stock again; between the other statuses the shop moves it as it sees fit.
 *
 * The customer is a user of the site or a guest, known by the name, the email address and the phone number they
 * gave; the addresses are the values {@see $shipping_address} and {@see $billing_address} in the meta, and the
 * company they order as, if any, {@see $company_info}. The
 * amounts are in the currency of the shop at the time and are what the customer pays, VAT included: {@see $tax}
 * is the VAT in the total, {@see $taxes} the same by rate, and every line keeps its own (see `OrderItem`). The status of the order and the status of its payment
 * are two: the shop moves the order along, and a payment module marks it paid. What another module keeps about an order lives in the meta under the prefix of the module.
 *
 * @property int $id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property int $number The number the customer and the shop know the order by, counted up from {@see FIRST_NUMBER}.
 * @property string $token The random token of the order, the only way to its page on the site (see {@see getUrl()}).
 * @property string $status One of the `STATUS_*` constants.
 * @property string $payment_status One of the `PAYMENT_*` constants.
 * @property int|null $user_id The customer as a user of the site; null for a guest.
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $subtotal What the lines cost together.
 * @property string $shipping What the delivery costs.
 * @property string $payment_fee What the way of payment costs (a fee for cash on delivery).
 * @property string $discount What was taken off.
 * @property string $tax The VAT in the total: of the lines, the delivery and the payment fee.
 * @property string $total What the customer pays: the subtotal with the delivery and the payment fee, less the discount.
 * @property string $currency The ISO 4217 code of the currency of the amounts.
 * @property string|null $shipping_method The way of delivery the customer chose, by its name.
 * @property string|null $payment_method The way of payment the customer chose, by its name.
 * @property string|null $note What the customer wrote with the order.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @property-read OrderItem[] $items
 * @property-read User|null $user
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
class Order extends SiteRecord
{
    /**
     * The number of the first order of a site.
     */
    public const FIRST_NUMBER = 1001;

    public const STATUS_NEW = 'new';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REFUNDED = 'refunded';

    /**
     * The scenario of the page of an order in the administration: only the statuses and the internal note are
     * changed there, never the customer or the amounts.
     */
    public const SCENARIO_MANAGE = 'manage';

    /**
     * Where the order is delivered, as the checkout collected it: the keys of {@see addressFields()}.
     *
     * @var array<string, string>|null
     */
    public array|null $shipping_address = null;

    /**
     * Who is invoiced, when other than the delivery: the keys of {@see addressFields()}.
     *
     * @var array<string, string>|null
     */
    public array|null $billing_address = null;

    /**
     * The company the customer orders as: the keys of {@see companyFields()}, the name of the company and its
     * numbers; null for a person.
     *
     * @var array<string, string>|null
     */
    public array|null $company_info = null;

    /**
     * The VAT the order holds by rate, the highest rate first: the rate in percent and the VAT charged at it, as
     * decimals with two places (see `Tax::summarize()`). Empty when no VAT was charged.
     *
     * @var list<array{rate: string, amount: string}>
     */
    public array $taxes = [];

    /**
     * The discounts taken off the order (see `Discount`), as they were when it was placed: who gave each, its
     * label, what it took off with VAT, as a decimal with two places, and what the module keeps with it (the
     * code of a coupon). Together they are {@see $discount}.
     *
     * @var list<array{source: string, label: string, amount: string, data: array<string, mixed>}>
     */
    public array $discounts = [];

    /**
     * The discount code the order was placed with (see `Cart::$discount_code`), as the customer typed it; empty
     * for none. A module that knows the code (a coupon) finds it by it.
     */
    public string $discount_code = '';

    /**
     * Whether the order was brought in by an import (see `modules\shop\backend\import\OrderImport`) rather than
     * placed here: it is history of another shop, which took its stock and counted its coupon, so this shop
     * never moves the stock for it, whatever happens to its status, and a module that counts orders leaves it out.
     */
    public bool $imported = false;

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
     *
     * A change of the status moves the stock (see {@see afterSave()}), so both happen or neither.
     */
    public function transactions(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
     * The statuses of an order with their labels, in the order the shop goes through them.
     *
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
    }

    /**
     * The statuses of the payment of an order with their labels.
     *
     * @return array<string, string>
     */
    public static function paymentStatusLabels(): array
    {
    }

    /**
     * The status of the order in words.
     */
    public function statusLabel(): string
    {
    }

    /**
     * The status of the payment of the order in words.
     */
    public function paymentStatusLabel(): string
    {
    }

    /**
     * The way of delivery the customer chose, by its name as the site shows it, or by its key once the method is
     * gone; empty until the checkout filled it.
     */
    public function shippingMethodName(): string
    {
    }

    /**
     * The way of payment the customer chose, as the site calls it (the words of the shop, see
     * `PaymentMethod::label()`), or by its name once the way is gone; empty until the checkout filled it.
     */
    public function paymentMethodLabel(): string
    {
    }

    /**
     * The way of payment the customer chose, or null until the checkout filled it or once the way is gone.
     */
    public function paymentMethod(): PaymentMethod|null
    {
    }

    /**
     * Whether the customer pays the order on the site, through a provider (see `PaymentMethod::isOnline()`).
     */
    public function paysOnline(): bool
    {
    }

    /**
     * Whether the customer pays the order in cash when it is delivered (see `PaymentMethod::isCashOnDelivery()`);
     * false once the way of payment is gone.
     */
    public function isCashOnDelivery(): bool
    {
    }

    /**
     * Whether the customer may still pay the order on the site: a way paid online, the payment awaited, the
     * order not cancelled. The page of the order then offers to pay (see {@see getPaymentUrl()}).
     */
    public function awaitsPayment(): bool
    {
    }

    /**
     * What the customer does to pay, while the payment is awaited: the instructions of the way of payment (a
     * bank transfer); empty once paid or for a way without instructions.
     */
    public function paymentInstructions(): string
    {
    }

    /**
     * What the order holds as a table for an email (the `order_items` placeholder, see `OrderEmails`): a row
     * per line with its quantity and its total, then the subtotal, the delivery, the payment fee while there is
     * one, every discount while there is one, the total, and the VAT in it by rate.
     */
    public function itemsTable(): string
    {
    }

    /**
     * The VAT of the order by rate as it is read under the amounts: "Including VAT 20%" with the VAT charged at
     * it, or "VAT 20%" for a summary that shows the amounts without VAT and adds it on top. Empty when no VAT was
     * charged.
     *
     * @return list<array{label: string, amount: string}>
     */
    public function taxLines(bool $included = true): array
    {
    }

    /**
     * The class of the badge of a status in the administration: a new order asks for attention, a completed one
     * is done, a cancelled one is off, and the ones in between are quiet.
     */
    public static function statusClass(string $status): string
    {
    }

    /**
     * The class of the badge of a status of the payment: pending asks for attention, paid is done, refunded is off.
     */
    public static function paymentStatusClass(string $status): string
    {
    }

    /**
     * What the order is called: "Order #1001".
     */
    public function getLabel(): string
    {
    }

    /**
     * The address of the page of the order on the site, the "Thank you" page of the checkout, reached by the
     * token of the order only: what the customer is sent to once they ordered and what the email leads to.
     */
    public function getUrl(): string
    {
    }

    /**
     * The address on the site where the customer pays the order, by the token of the order like its page: it
     * sends them on to the way of payment while the order awaits the payment (see {@see awaitsPayment()}), or
     * to the page of the order.
     */
    public function getPaymentUrl(): string
    {
    }

    /**
     * What the customer may do with the order from its page in their account, beyond reading it (the
     * `order.actions` hook, a filter given the order): a module appends an action with its `label` and its
     * `url`, like the way to review what was bought.
     *
     * @return list<array{label: string, url: string|array}>
     */
    public function accountActions(): array
    {
    }

    /**
     * What the shop may do with the order from its page in the administration, the small buttons under its title,
     * by name (the `order.admin.actions` hook, a filter given the order): make a copy of it, refund it while it is
     * paid, and delete it. A module
     * appends an action of its own or takes one away (`unset($actions['delete'])` for a shop whose orders are never
     * deleted), and `OrderController` refuses an action that is not here, whatever is posted to it.
     *
     * An action has its `label`, its `icon` and its `url`, and may have `method` (`post` for one that changes
     * something), `confirm` (the question asked first), `danger` (true for one that destroys) and `dialog`, a view
     * rendered with the `order` and the `id` of the `<dialog>` it holds, which the button opens in place of the
     * address (see `backend/views/order/_delete.php`).
     *
     * @return array<string, array{label: string, icon?: string, url?: string|array, method?: string, confirm?: string, danger?: bool, dialog?: string}>
     */
    public function adminActions(): array
    {
    }

    /**
     * The route of the page of the order in the administration.
     */
    public function getEditUrl(): array
    {
    }

    /**
     * An address as it is written on a parcel, a line per row: the name, the company with its tax id, the street,
     * the postal code with the city, and the country by its name (see `Countries`). Empty
     * for no address.
     *
     * @param array<string, string>|null $address {@see $shipping_address} or {@see $billing_address}.
     * @return list<string>
     */
    public static function addressLines(array|null $address): array
    {
    }

    /**
     * The company of an order as lines: its name, then each of its numbers with its label ("Tax ID: 123456789").
     * Empty for none.
     *
     * @param array<string, string>|null $company {@see $company_info}.
     * @return list<string>
     */
    public static function companyLines(array|null $company): array
    {
    }

    /**
     * The fields of an address with their labels, in the order they are shown: what {@see $shipping_address} and
     * {@see $billing_address} hold. The company the customer orders as is not part of an address, see
     * {@see $company_info}.
     *
     * @return array<string, string>
     */
    public static function addressFields(): array
    {
    }

    /**
     * The fields of the company an order is placed as, what {@see $company_info} holds, all of them optional: its
     * name, its registration number (the "matični broj" in Serbia, the "IČO" in Slovakia), its tax number (the
     * "PIB", the "DIČ") and its VAT number (the "IČ DPH"; in Serbia the tax number serves).
     *
     * @return array<string, string>
     */
    public static function companyFields(): array
    {
    }

    /**
     * The fields of {@see addressFields()} an address must have: the name, the street, the postal code, the
     * city and the country: every field of an address.
     *
     * @return string[]
     */
    public static function requiredAddressFields(): array
    {
    }

    /**
     * An order for what a cart holds, not yet placed: its lines with the prices of the moment and its amounts,
     * in the currency of the shop, for the customer the cart belongs to, with the delivery and the payment fee
     * as the shop wrote them and the discounts the modules give (see `Cart::getDiscounts()`, for the email
     * address the order is placed with), and the discount code of the cart. Every amount is taken with VAT, the lines charged the rates of their
     * products, a discount the rates of the lines it is taken off and the delivery and the fee the default rate
     * (see `Tax`), and the VAT is added up. The checkout adds who the customer is and what they chose, then calls
     * {@see place()}.
     */
    public static function fromCart(Cart $cart, float $shipping = 0.0, float $paymentFee = 0.0, string|null $email = null): self
    {
    }

    /**
     * Counts the total from the amounts: the subtotal with the delivery and the payment fee, less the discount.
     * Called after any of them changed, before the order is placed.
     */
    public function updateTotal(): void
    {
    }

    /**
     * Places the order: checks the customer and that every line can still be bought in its quantity, one its
     * product is ordered in, then, in one transaction, gives it the next number of the site and a token of its
     * own, stores it with its lines and takes what was bought off the stock of the variants (only where the stock
     * is counted, never below none). False with the errors on the order when anything is not right; the errors
     * of the lines are on `items`.
     */
    public function place(): bool
    {
    }

    /**
     * A new order the shop makes in the administration ("New order" on the list, see
     * `OrderController::actionCreate()`), stored empty at once so it is made like any order is changed, a part at a
     * time in the panels of its page: the next number and a token of its own, new and unpaid, in the currency of the
     * shop, without a customer, lines, delivery or payment, its history started with the `created` event by the user
     * logged in. Nothing is taken off the stock until lines are added, and no email is sent.
     */
    public static function createEmpty(): self
    {
    }

    /**
     * Makes a new order out of this one, as it was placed: the same customer, addresses, company and note, the same lines at
     * the same prices, the same amounts, VAT and discounts, the same ways of delivery and of payment. It gets the
     * next number and a token of its own, starts as new and unpaid, and takes its items off the stock, even below
     * what is there (never below none), since the shop decided it. What the modules keep about the order (a payment
     * of Stripe, a review asked for) stays with this one, and no email is sent. Both histories say where the copy
     * came from. Returns the copy, stored.
     */
    public function duplicate(): self
    {
    }

    /**
     * Records that the order was paid back in full: its payment becomes refunded and the order cancelled, which
     * puts its items back on the stock and tells the customer (see {@see afterSave()}), and its history says who
     * refunded it. The money itself is given back the way it was paid (by hand, or in the panel of Stripe or
     * PayPal); the `order.refunded` hook (run, given the order) lets a module act on it.
     */
    public function refund(): bool
    {
    }

    /**
     * Deletes the order with its lines and its history (the database takes them with it), in one transaction, once
     * the `order.deleting` hook (run, given the order) let the modules clear what they keep about it. With
     * `$restock` what the lines hold goes back on the stock while {@see canRestock()} says it may.
     */
    public function remove(bool $restock): bool
    {
    }

    /**
     * Whether deleting the order may put its items back on the stock: not when it was cancelled, which gave them
     * back already, and not when it was imported, whose stock was never taken here.
     */
    public function canRestock(): bool
    {
    }

    /**
     * Stores what the shop changed of the order in the administration (see `OrderItemsForm`), whatever the order
     * stands at: the lines given in place of the ones it has, and the way of delivery and of payment with what they
     * cost, set on the order before. A line it had and does not get is deleted, the others are stored with their
     * quantity and price and the total and VAT counted from them; a new line is taken off the stock and a changed
     * one moved by what changed, while the order holds its stock ({@see canRestock()}). The amounts of the order
     * are counted again ({@see recount()}), the history says what changed (a `changed` event) and the
     * `order.changed` hook (run, given the order and the changes) tells the modules. No email is sent. Nothing
     * happens when nothing changed.
     *
     * The changes are a list of `items` (each the `title` of the line, its `variant_id`, its quantity `from` and `to`,
     * 0 for a line added or taken out, and its `price_from` and `price_to` when the price changed) and, while they
     * changed, the `shipping` and the `payment` (each the name of the way `from` and `to` and what it cost,
     * `amount_from` and `amount_to`).
     *
     * @param list<OrderItem> $items The lines, those the order had and new ones, with their quantity and price.
     */
    public function saveChanges(array $items): void
    {
    }

    /**
     * Counts the amounts of the order again from its lines, once they changed: the subtotal, the discounts as they
     * were given (cut down in proportion when they come to more than the subtotal now), the VAT by rate (of the
     * lines, of the delivery and the fee at the default rate of the shop, and what the discounts take off it, in
     * proportion to the lines of each rate) and the total. The delivery and the fee stay as they are.
     */
    public function recount(): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * A change of the status or of the payment goes into the history, by whoever is logged in, and a change of the
     * status is emailed to the customer (see `OrderEmails`). An order paid online is confirmed to the customer and
     * to the site once it is paid, not when it is placed. An order that was cancelled gives its stock back; one
     * taken up again out of cancelled takes it again.
     */
    public function afterSave($insert, $changedAttributes): void
    {
    }

    /**
     * Writes into the history of the order what happened to it now (see `OrderEvent`): the type, what to keep
     * about it in the meta, the words for a note or for what a module did, and who did it.
     *
     * @param array<string, mixed> $data
     */
    public function addEvent(string $type, array $data = [], string|null $message = null, int|null $userId = null): OrderEvent
    {
    }

    /**
     * The history of the order, the newest event first.
     *
     * @return ActiveQuery<OrderEvent>
     */
    public function getEvents(): ActiveQuery
    {
    }

    /**
     * @return ActiveQuery<OrderItem>
     */
    public function getItems(): ActiveQuery
    {
    }

    /**
     * @return ActiveQuery<User>
     */
    public function getUser(): ActiveQuery
    {
    }
}
