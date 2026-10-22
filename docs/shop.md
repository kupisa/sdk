# The shop

The shop is a core module of the platform: it is what makes a site a shop. A site gets it when it is created,
and keeps it; it is never enabled or disabled later like a regular module. It brings the products with their
variants, the catalogue with its filters, the cart, the checkout, the orders, the customers, the ways of
delivery and of payment and VAT. Its public classes are in `stubs/modules/shop/`, their comments the reference.

## First: does the site have it?

**The shop exists only on a site that has it.** Before you use anything of it, read `.kupisa.json` (see
`AGENTS.md`): the selected site has the shop when `modules.core` of it names `shop`.

- When it does not, build nothing around the shop: no class of `modules\shop\…`, no hook of the shop, no product
  or price in a block. Say so to the developer instead, since it is a site of another kind.
- A module or a theme whose code uses a class of the shop names it in `requires()`:

  ```php
  public static function requires(): array
  {
      return ['shop'];
  }
  ```

  The platform then offers it only to a site that has the shop. Without it, a site without the shop could get
  it and break on it; `vendor/bin/kupisa push` and `dev` warn about every theme and module that forgets it.
- The views of the shop a theme replaces (see "A theme and the shop" below) need nothing of the kind: they are
  used only while the site has the shop, so a theme for any site may carry them.

## What the shop is made of

| Class | What it is |
|---|---|
| `modules\shop\Module` | The module: the currency (`currency()`), the VAT of the shop (`tax()`) and the settings of the shop (`cardButton()`, `taxEnabled()`, ...). |
| `common\models\Product` | A product: a kind of page (`common\models\Page`), with its pictures, short description, options and related products in its meta. |
| `common\models\Variant` | What is sold: every product has one at least (`getDefaultVariant()`), one per combination of its options otherwise, each with its SKU, price, compare-at price, stock and availability. |
| `common\models\Attribute` | The attributes of the shop (Colour, Size) and their values (Red, M), a taxonomy of two levels. A product varies by up to three of them (`$options`); others describe it (`getSpecifications()`). |
| `common\models\Category`, `Tag` | The categories (a tree) and the tags of the products, taxonomies with pages on the site. |
| `common\models\Cart`, `CartLine` | The cart of a visitor and its lines; `frontend\CurrentCart::find()` gives the cart of the visitor of the request, or null. |
| `common\models\Discount` | A discount a module gives on a cart (see the `cart.discounts` hook). |
| `common\models\Order`, `OrderItem` | An order and its lines, with the prices and names fixed as they were when it was placed. |
| `common\models\OrderEvent` | What happened to an order, its history; a module writes its own with `$order->addEvent()`. |
| `common\models\Customer` | What the shop knows about a user of the site: `new Customer($user)`, its phone, addresses, company and orders. |
| `common\models\Tax`, `TaxRate`, `TaxAmount` | VAT: the rates of the shop and what they come to. |
| `common\models\ShippingMethod` | A way of delivery the shop set up, with its price and its rules. |
| `common\payment\PaymentMethod` | A way of payment: a class the platform or a module brings (cash on delivery, bank transfer, a card). |
| `frontend\blocks\products\ProductsBlock` | The products block of the page editor, and the lists it picks products from (`sources()`). |
| `frontend\assets\ShopAsset` | The stylesheet and the script of the shop on the site. |

The namespaces are under `modules\shop\` (`modules\shop\common\models\Product`). The controllers, the forms of
the administration and the export and import of the shop have no stub: they are the shop's own, and a module
joins them through the hooks below.

## Products and prices

- A product of the site is found like any page: `Product::published()` for the published ones,
  `Product::available()` for those that can be bought as well, `Product::onSale()` for those below their
  compare-at price. `isAvailable()` answers for one product, `getUrl()` gives its address.
- What a product costs is the price of its variants: `getCheapestVariant()` and `pricesDiffer()` (the card
  shows "From …" then), or the variant a visitor picks.
- A price is shown as the shop shows it, with VAT or without, never as it is written: `$variant->getShownPrice()`
  and `getShownComparePrice()`, or `Module::tax()->shown($price, $rate)` for an amount of your own. Write it with
  the currency of the shop through the formatter, which the shop sets up:
  `Yii::$app->formatter->asCurrency($variant->getShownPrice())`.
- `Variant::isAvailable()` is the one question whether a variant can be bought; `maxQuantity()` how many.
- The amounts of an order are what the customer pays, VAT included (`$order->total`, `$order->tax`).

## What a module keeps

A module keeps what it knows about a record of the shop in its meta, under the prefix of the module, as with a
page: `$product->setMeta('acme.warranty', 24)` and `getMeta('acme.warranty')` on a product, a variant, an order
or a cart. It never adds a column or a table. What it does with an order goes into the history of the order:

```php
$order->addEvent('acme.sent', ['parcel' => $number], Yii::t('acme', 'Sent with parcel {number}.', ['number' => $number]));
```

The type of the event starts with the name of the module.

Leave the rest to the shop: the stock, the amounts and the statuses of an order change through its own methods
(`place()`, `refund()`, an order saved with a new `status` or `payment_status`), never by writing the columns
around them.

## Hooks of the shop

A module attaches to them with `Hook::on()` in `init()`, like to the hooks of the platform (see
`docs/module.md`). A filter is given the value and what it depends on and returns the value; a run hook only
tells.

### The product

| Hook | Kind | Given | What it does |
|---|---|---|---|
| `product.sections` | filter | the sections, the `Product` | The sections under a product on its page. Append one: `name` (the id of its element), `label`, `position` (the description stands at 10, the specifications at 20) and `content`, HTML; one with no content is left out. |
| `product.summary` | filter | `''`, the `Product` | HTML under the title on the page of a product (the stars of the reviews). |
| `product.card` | filter | `''`, the `Product` | HTML under the title of the card of a product in every list. |
| `product.discountable` | filter | `true`, the `Product` | Whether a discount may be taken off the product. |
| `products.sources` | filter | the sources by name | The lists the products block picks products from by itself. Append one with its `label` and `products`, a callable given how many and giving products that can be bought (`Product::available()`). |
| `variant.form` | filter | the sections, the `Variant` | The sections of the form of a variant in the administration, like `page.form` (see `backend\models\FormSection`). |

### The cart and the checkout

| Hook | Kind | Given | What it does |
|---|---|---|---|
| `cart.changed` | run | the `Cart`, the `Variant`, how many | A line the visitor changed: how many were added, or taken out as a negative number. |
| `cart.discounts` | filter | the discounts, the `Cart`, the email address or null | The discounts of the cart. Append a `Discount`: who gives it (the name of the module), its label, the amount as the shop writes its prices and what the module keeps with it on the order. |
| `discount.code` | filter | the error, the code, the `Cart`, the email address or null | Whether a discount code applies: null for a code of the module that applies, its reason for one that does not, the error it was given otherwise. |
| `shipping.price` | filter | the price, the `ShippingMethod`, the `Cart`, the country | What a way of delivery costs for the cart (free delivery). |
| `shipping.countries` | filter | the codes of the countries, the `Cart` | The countries the delivery address may be in: take out one a product of the cart may not go to. |
| `checkout.company_fields` | filter | the fields by name, the country billed to | The fields of the company the checkout asks for: take out one a country has no use for. |
| `payment.methods` | filter | the ways of payment | Append an instance of a class of the module that extends `PaymentMethod` (see below). |

### The order

| Hook | Kind | Given | What it does |
|---|---|---|---|
| `order.actions` | filter | the actions, the `Order` | What the customer may do with an order from its page in their account: append one with its `label` and `url`. |
| `order.admin.actions` | filter | the actions by name, the `Order` | The buttons under the title of an order in the administration: append one with its `label`, `icon`, `url`, `method`, `confirm` or `danger`, or take out `duplicate`, `refund` or `delete`. |
| `order.changed` | run | the `Order`, the changes | The shop changed the lines, the delivery or the payment of an order in the administration. |
| `order.refunded` | run | the `Order` | The shop recorded that the order was paid back. |
| `order.deleting` | run | the `Order` | An order is about to be deleted: clear what the module keeps about it. |
| `datalayer.item` | filter | the item, the `Variant` | A product as an item of the events of the shop (Google Analytics 4): add the brand a module keeps. |

## Examples

A section under every product, with its warranty:

```php
public function init(): void
{
    parent::init();

    Hook::on('product.sections', [self::class, 'warrantySection']);
}

/**
 * The warranty of a product as a section under it, the `product.sections` hook.
 */
public static function warrantySection(array $sections, Product $product): array
{
    $months = (int) $product->getMeta('acme.warranty', 0);

    $sections[] = [
        'name'     => 'warranty',
        'label'    => Yii::t('acme', 'Warranty'),
        'position' => 30,
        'content'  => $months === 0 ? '' : Html::tag('p', Yii::t('acme', '{n} months', ['n' => $months])),
    ];

    return $sections;
}
```

A discount of 10% off what may be discounted, for a cart of more than 100:

```php
Hook::on('cart.discounts', static function (array $discounts, Cart $cart): array {
    $total = $cart->getDiscountableTotal();

    if ($total > 100) {
        $discounts[] = new Discount('acme', Yii::t('acme', 'Big order'), round($total * 0.1, 2));
    }

    return $discounts;
});
```

A way of payment is a class of the module that extends `PaymentMethod` and names its `name()` (prefixed with the
module when the word is common), `defaultLabel()`, `defaultDescription()` and `isEnabledByDefault()`; the shop
gives it its settings page, its place on the checkout and the fee. A way paid online says so with `isOnline()`
and gives with `paymentUrl($order)` where the customer pays, and marks the order paid once they did (the
`payment_status` set to `Order::PAYMENT_PAID` and saved). Read the comments of
`stubs/modules/shop/common/payment/PaymentMethod.php` before you write one: a way that takes the card on the
checkout itself has more to do (`checkoutForm()`, `checkoutView()`, `charge()`).

## A theme and the shop

The views of the shop on the site are in `modules/shop/` of the SDK, at the path a theme replaces them at, and
`modules/shop/frontend/public/css/shop.css` is its stylesheet, whose classes a theme styles. To change a view,
copy it into the theme under the same path and edit the copy, as with the views of the site (see
`docs/theme.md`):

| The view of the shop | Its replacement in the theme |
|---|---|
| `modules/shop/frontend/views/product/_card.php` | `themes/<name>/modules/shop/frontend/views/product/_card.php` |
| `modules/shop/frontend/blocks/products/views/products.php` | `themes/<name>/modules/shop/frontend/blocks/products/views/products.php` |

- The shop is styled first through the tokens of the site, which its stylesheet uses: a theme that gives the
  tokens its values changes the shop too. Style a class of `shop.css` in `theme.css` where the tokens are not
  enough, and replace a view only where the stylesheet cannot do it.
- Keep the classes and the `data-` attributes of a view you replace (`data-shop-variants`, `data-cart-part`,
  `data-checkout`, ...): the script of the shop relies on them, and the checkout and the cart work through them.
- A block of the theme that shows products (the newest, a category) uses the shop, so the theme names it in
  `requires()`. Give such a block products that can be bought (`Product::available()`), register
  `ShopAsset` in it when it draws the cards of the shop, and show its prices as above.
