<?php

/**
 * What the visitor is ordering, for the summary of the checkout: a line per variant with its picture, the title
 * of its product with the quantity, its own title while the product has options, and what the line costs;
 * then the discount code while the shop takes them and `$withCode` asks for it (_discount-code.php of the cart,
 * its field in the form `shop-discount-code` after the form of the checkout), the amounts, the subtotal with
 * the discounts under it, the delivery and the fee of the payment of the ways chosen and the total, with the VAT by
 * rate: added above the total while the amounts are shown without it, named under it ("Including VAT 20%")
 * while they include it. Rendered on the right of the checkout and, on a phone, folded at the top and open above the
 * button (see index.php).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\CartLine[] $lines */
/** @var string $subtotalLabel */
/** @var list<array{label: string, amount: string}> $discounts The discounts, as the visitor reads them; empty for none. */
/** @var bool $withCode Whether the field of the discount code is shown here: in one summary of the page only. */
/** @var bool $takesCodes Whether the shop takes discount codes. */
/** @var string $code The discount code applied; empty for none. */
/** @var string $codeInput The code the visitor typed, while it did not apply. */
/** @var string $codeError What was wrong with the code; empty for nothing. */
/** @var string $shippingLabel What the delivery costs with the way chosen, as the visitor reads it. */
/** @var string $paymentFeeLabel What the way of payment chosen costs; empty for nothing. */
/** @var string $totalLabel What the order comes to with the ways chosen, VAT included. */
/** @var list<array{label: string, amount: string}> $taxLines The VAT by rate, as the visitor reads it; empty for none. */
/** @var bool $taxesIncluded Whether the amounts above the total include the VAT: then it is named under the total, otherwise added above it. */

use yii\helpers\Html;

$formatter = Yii::$app->formatter;
?>
<ul class="shop-checkout-lines">
    <?php foreach ($lines as $line): ?>
        <li class="shop-checkout-line">
            <?= $line->variant->getMainImage()->img(['thumbnail' => true, 'alt' => '']) ?>
            <span class="shop-checkout-line-title">
                <?= Html::encode($line->product->title) ?>
                <span class="shop-checkout-line-quantity">× <?= $line->quantity ?></span>
                <?php if ($line->variant->getTitle() !== ''): ?>
                    <span class="shop-checkout-line-variant"><?= Html::encode($line->variant->getTitle()) ?></span>
                <?php endif ?>
            </span>
            <span class="shop-checkout-line-total"><?= $formatter->asCurrency($line->getShownTotal()) ?></span>
        </li>
    <?php endforeach ?>
</ul>
<?php if ($withCode && $takesCodes): ?>
    <?= $this->render('/cart/_discount-code', [
        'formId'    => 'shop-discount-code',
        'code'      => $code,
        'codeInput' => $codeInput,
        'codeError' => $codeError,
    ]) ?>
<?php endif ?>
<dl class="shop-checkout-amounts">
    <dt><?= Yii::t('shop', 'Subtotal') ?></dt>
    <dd><?= Html::encode($subtotalLabel) ?></dd>
    <?php foreach ($discounts as $discount): ?>
        <dt><?= Html::encode($discount['label']) ?></dt>
        <dd><?= Html::encode($discount['amount']) ?></dd>
    <?php endforeach ?>
    <dt><?= Yii::t('shop', 'Delivery') ?></dt>
    <dd data-checkout-shipping><?= Html::encode($shippingLabel) ?></dd>
    <?php if ($paymentFeeLabel !== ''): ?>
        <dt><?= Yii::t('shop', 'Payment') ?></dt>
        <dd data-checkout-payment><?= Html::encode($paymentFeeLabel) ?></dd>
    <?php endif ?>
    <?php if (!$taxesIncluded): ?>
        <?php foreach ($taxLines as $taxLine): ?>
            <dt><?= Html::encode($taxLine['label']) ?></dt>
            <dd><?= Html::encode($taxLine['amount']) ?></dd>
        <?php endforeach ?>
    <?php endif ?>
    <dt class="shop-checkout-grand"><?= Yii::t('shop', 'Total') ?></dt>
    <dd class="shop-checkout-grand" data-checkout-total><?= Html::encode($totalLabel) ?></dd>
    <?php if ($taxesIncluded): ?>
        <?php foreach ($taxLines as $taxLine): ?>
            <dt class="shop-checkout-tax"><?= Html::encode($taxLine['label']) ?></dt>
            <dd class="shop-checkout-tax"><?= Html::encode($taxLine['amount']) ?></dd>
        <?php endforeach ?>
    <?php endif ?>
</dl>
