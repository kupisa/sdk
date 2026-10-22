<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\models\Media;
use common\models\SiteRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * One line of an order: a variant bought so many times. The title of the product, the name of the variant, the
 * SKU and the price are copied from the variant when the order is placed and never read from it again, so the
 * order reads the same after the product changed or was deleted; the variant and the product are pointed at for
 * the way back to them in the administration, and forgotten when they are deleted. What another module keeps
 * about a line (the discount of a coupon) lives in its meta under the prefix of the module.
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $variant_id The variant bought, while it exists.
 * @property int|null $product_id The product bought, while it exists.
 * @property string $title The title of the product when the order was placed.
 * @property string $variant_title The name of the variant then ("Red / M"), empty for a product without options.
 * @property string|null $sku
 * @property string $price The price of one with VAT, as a decimal with two places.
 * @property int $quantity
 * @property string $total What the line costs with VAT: the price times the quantity.
 * @property string|null $tax_rate The rate of VAT the line was charged, in percent; null when no VAT was charged.
 * @property string $tax The VAT in the total.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @property-read Order $order
 * @property-read Variant|null $variant
 * @property-read Product|null $product
 */
class OrderItem extends SiteRecord
{
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
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * A line for what a line of a cart holds, with the price and the names as they are now, and the VAT the
     * product is charged (see `Tax`): the price and the total with VAT, the rate and the VAT in the total.
     */
    public static function fromLine(CartLine $line, Tax $tax): self
    {
    }

    /**
     * A new line for a variant the shop adds to an order in the administration: the names and the SKU of the
     * variant and its price of today with VAT, at the rate of its product, as {@see fromLine()} takes them from a
     * cart. The total and the VAT are counted when the line is stored (see `Order::saveChanges()`).
     */
    public static function fromVariant(Variant $variant, int $quantity, Tax $tax): self
    {
    }

    /**
     * The total of the line split into its VAT, as it was charged.
     */
    public function getTaxAmount(): TaxAmount
    {
    }

    /**
     * The main picture of what was bought, while the variant still exists; null once it is gone.
     */
    public function getImage(): Media|null
    {
    }

    /**
     * @return ActiveQuery<Order>
     */
    public function getOrder(): ActiveQuery
    {
    }

    /**
     * @return ActiveQuery<Variant>
     */
    public function getVariant(): ActiveQuery
    {
    }

    /**
     * @return ActiveQuery<Product>
     */
    public function getProduct(): ActiveQuery
    {
    }

    /**
     * The ids of the products the shop sold most of, by the quantity of the lines of its orders (a cancelled order
     * left out), the most sold first: whatever product still exists, published or not.
     *
     * @return list<int>
     */
    public static function bestsellerIds(int $limit): array
    {
    }
}
