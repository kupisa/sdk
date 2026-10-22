<?php

declare(strict_types=1);

namespace modules\shop;

use common\helpers\Countries;
use common\helpers\Hook;
use modules\Module as BaseModule;
use modules\ModuleType;
use modules\shop\backend\models\BillingAddressSection;
use modules\shop\backend\models\CompanyInfoSection;
use modules\shop\backend\models\SettingsForm;
use modules\shop\backend\models\ShippingAddressSection;
use modules\shop\common\content\ProductContent;
use modules\shop\common\mail\OrderPlacedNotification;
use modules\shop\common\mail\OrderReceivedNotification;
use modules\shop\common\mail\OrderStatusNotification;
use modules\shop\common\models\Attribute;
use modules\shop\common\models\Category;
use modules\shop\common\models\Product;
use modules\shop\common\models\Tag;
use modules\shop\common\models\Tax;
use modules\shop\frontend\assets\ShopAsset;
use modules\shop\frontend\blocks\products\ProductsBlock;
use modules\shop\frontend\controllers\ProductController;
use modules\shop\frontend\CurrentCart;
use modules\shop\frontend\Ecommerce;
use common\models\SiteSetting;
use common\models\User as UserModel;
use Yii;
use yii\web\User;

/**
 * The shop: a core module that makes the site a shop. It is set when the site is created
 * (`./yii site/create <host> "<name>" shop`) and never enabled or disabled in the administration.
 *
 * A product is a kind of page (see `common\models\Product`), so its description is built in the page editor
 * like any page; what it sells is in its variants (see `common\models\Variant`). Categories
 * (`common\models\Category`) are a taxonomy the platform administers. Its settings page (see
 * `backend\models\SettingsForm`) holds the currency of the prices, how the shop charges VAT and how many
 * products a page of the catalogue shows; the rates of VAT are records of their own (see `common\models\TaxRate`)
 * and what is counted from them is `common\models\Tax`. On the site the catalogue is under `/shop` (see
 * `frontend\controllers\ProductController`) and
 * the cart of the visitor under `/cart` (see `frontend\controllers\CartController` and `frontend\CurrentCart`) and
 * the checkout under `/checkout` (see `frontend\controllers\CheckoutController` and `frontend\models\CheckoutForm`).
 */
class Module extends BaseModule
{
    /**
     * The checkout offers no account: everyone orders as a guest (the `shop.customer_accounts` setting).
     */
    public const ACCOUNTS_DISABLED = 'disabled';

    /**
     * The checkout offers to create an account with the order; the visitor may order as a guest.
     */
    public const ACCOUNTS_OPTIONAL = 'optional';

    /**
     * The visitor has to log in or create an account with the order.
     */
    public const ACCOUNTS_REQUIRED = 'required';

    /**
     * The checkout asks for the delivery address first, and offers a billing address of its own behind "Same as
     * the delivery address" (the `shop.checkout_addresses` setting).
     */
    public const ADDRESSES_DELIVERY_FIRST = 'delivery_first';

    /**
     * The checkout asks for the delivery address only: the order is billed to it.
     */
    public const ADDRESSES_DELIVERY_ONLY = 'delivery_only';

    /**
     * The checkout asks for the billing address first, and offers a delivery address of its own behind "Same as
     * the billing address" (the order of WooCommerce).
     */
    public const ADDRESSES_BILLING_FIRST = 'billing_first';

    /**
     * The checkout asks nothing about a company: everyone orders as a person (the `shop.checkout_company` setting).
     */
    public const COMPANY_DISABLED = 'disabled';

    /**
     * The checkout offers to order as a company, behind a switch that unfolds its fields.
     */
    public const COMPANY_OPTIONAL = 'optional';

    /**
     * Every customer orders as a company: the fields of the company are always there (a shop that sells to
     * businesses).
     */
    public const COMPANY_REQUIRED = 'required';

    /**
     * The sections of the page of a product (its description, its specifications, ...) are tabs, one shown at
     * a time (the `shop.product_sections` setting).
     */
    public const SECTIONS_TABS = 'tabs';

    /**
     * The sections of the page of a product stand one under the other, each under its title.
     */
    public const SECTIONS_STACKED = 'stacked';

    /**
     * {@inheritdoc}
     *
     * Adds the "Shop" group to the navigation of the administration, ahead of the content,
     * with "Orders", "Products" and "Customers" in it, the categories, the attributes and the tags as the
     * sub-items of the products, adds the addresses of the shop to the form of every user, puts "Delivery",
     * "Payment" and "VAT rates" into the group of the shop in the settings, offers the categories and the tags
     * to the menus of the site and to the sitemap, leads
     * from the admin bar of the shop into the administration, and gives the formatter the currency of the shop,
     * so `asCurrency()` shows every price in it. On the site it puts the cart into the header, names its cookie
     * to the cookie banner, keeps the cart of a user through their login and tells the tags of the site what the
     * visitors do with the products (see `frontend\Ecommerce`).
     */
    public function init(): void
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
    public static function description(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function type(): ModuleType
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function hasSettings(): bool
    {
    }

    /**
     * {@inheritdoc}
     *
     * The customer: the role of a user the checkout creates, and the one a shop offers on signup. It opens
     * nothing in the administration; a customer is a user who ordered, whatever their role (see `Customer`).
     */
    public static function roles(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The emails about an order (see `common\mail\OrderEmails`): the confirmation to the customer and the news to
     * the site when it is placed, and the change of its status to the customer.
     */
    public static function notifications(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function pageTypes(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function taxonomies(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The products of the shop on any page, in the "Shop" group of the page editor.
     */
    public static function blocks(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function urlRules(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The shop lives under `/shop` (a category under `/shop/category`, a tag under `/shop/tag`), the cart under `/cart` and the checkout under `/checkout` (its "Thank you" page
     * under `/checkout/thank-you`, where an order paid online is paid under `/checkout/pay`), `/prodavnica`, `/korpa` and `/naruci` on a Serbian site, or under whatever
     * words the site chose; the orders and the addresses of a customer under the account area of the site,
     * `/account/orders` and `/account/addresses`.
     */
    public static function paths(): array
    {
    }

    /**
     * The ISO 4217 code of the currency of the shop, from its settings.
     */
    public static function currency(): string
    {
    }

    /**
     * Whether the checkout offers an account, one of the `ACCOUNTS_*` constants, from the settings of the shop.
     */
    public static function customerAccounts(): string
    {
    }

    /**
     * Which address the checkout asks for first and whether it offers the other one, one of the `ADDRESSES_*`
     * constants, from the settings of the shop.
     */
    public static function checkoutAddresses(): string
    {
    }

    /**
     * Whether the checkout asks for the company the customer orders as, one of the `COMPANY_*` constants, from
     * the settings of the shop.
     */
    public static function checkoutCompany(): string
    {
    }

    /**
     * How many products a page of the catalogue shows, from the settings of the shop.
     */
    public static function productsPerPage(): int
    {
    }

    /**
     * Whether the card of a product in the lists of the site carries a button into the cart (see
     * `Product::cardAction()`), from the settings of the shop; off unless the shop turns it on.
     */
    public static function cardButton(): bool
    {
    }

    /**
     * How the page of a product shows its sections, one of the `SECTIONS_*` constants, from the settings of the
     * shop.
     */
    public static function productSections(): string
    {
    }

    /**
     * The countries the shop delivers to, their ISO 3166-1 codes in the order the checkout offers them, from the
     * `shop.shipping_countries` setting (the codes separated by commas, set on Settings › Delivery). Empty for a
     * shop that delivers to every country.
     *
     * @return list<string>
     */
    public static function shippingCountries(): array
    {
    }

    /**
     * Whether the shop charges VAT, from its settings: false for a shop outside the VAT system, which counts and
     * shows none.
     */
    public static function taxEnabled(): bool
    {
    }

    /**
     * Whether the prices the shop writes in the administration (of the products, the delivery and the payment)
     * include VAT, from its settings. Meaningless while the shop charges no VAT.
     */
    public static function pricesIncludeTax(): bool
    {
    }

    /**
     * Whether the site shows the prices with VAT, from its settings. Meaningless while the shop charges no VAT.
     */
    public static function showPricesWithTax(): bool
    {
    }

    /**
     * VAT as the shop charges it (see `common\models\Tax`): read once per request, since every price shown asks.
     */
    public static function tax(): Tax
    {
    }
}
