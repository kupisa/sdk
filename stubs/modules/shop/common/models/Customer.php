<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\models\User;
use yii\db\ActiveQuery;

/**
 * What the shop knows about a user of the site: a customer is a user with the `customer` role the shop brings
 * (see `Module::roles()`) or who ordered, whatever their role. The checkout finds or creates the user by their
 * email address (a new one as a customer), the administration adds one under Shop › Customers (see
 * `backend\controllers\CustomerController`), and what the shop learns beyond the columns of the user, the
 * phone, the addresses and the company they order as, lives in the meta of the user under `shop.` (see
 * {@see META_PHONE}, edited on the form of the user with `backend\models\ShippingAddressSection`,
 * `BillingAddressSection` and `CompanyInfoSection`, a card each). An order keeps its own copy of the addresses and
 * of the company, since those of the customer change and an order does not.
 */
final class Customer
{
    public const META_PHONE = 'shop.phone';
    public const META_SHIPPING_ADDRESS = 'shop.shipping_address';
    public const META_BILLING_ADDRESS = 'shop.billing_address';
    public const META_COMPANY_INFO = 'shop.company_info';

    public function __construct(public readonly User $user)
    {
    }

    /**
     * The phone number of the customer; empty without one.
     */
    public function phone(): string
    {
    }

    /**
     * Where the customer has their orders delivered: the keys of `Order::addressFields()`, or null without one.
     *
     * @return array<string, string>|null
     */
    public function shippingAddress(): array|null
    {
    }

    /**
     * The company the customer orders as: the keys of `Order::companyFields()`, or null for a person.
     *
     * @return array<string, string>|null
     */
    public function companyInfo(): array|null
    {
    }

    /**
     * Who the customer is invoiced as, when other than the delivery: the keys of `Order::addressFields()`; null
     * without one.
     *
     * @return array<string, string>|null
     */
    public function billingAddress(): array|null
    {
    }

    /**
     * The orders of the customer, the latest first.
     *
     * @return ActiveQuery<Order>
     */
    public function getOrders(): ActiveQuery
    {
    }
}
