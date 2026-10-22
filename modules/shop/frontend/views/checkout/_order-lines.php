<?php

/**
 * What an order holds as the customer reads it, on the "Thank you" page and on the page of the order in their
 * account: the lines with their pictures and the amounts under them, VAT included, every discount under the
 * subtotal, with the VAT by rate named under the total.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Order $order */
/** @var modules\shop\common\models\OrderItem[] $items */

use yii\helpers\Html;

$amount = static fn (string $value): string => Yii::$app->formatter->asCurrency($value, $order->currency);
?>
<ul class="shop-checkout-lines">
    <?php foreach ($items as $item): ?>
        <li class="shop-checkout-line">
            <?php if ($item->getImage() !== null): ?>
                <?= $item->getImage()->img(['thumbnail' => true, 'alt' => '']) ?>
            <?php endif ?>
            <span class="shop-checkout-line-title">
                <?= Html::encode($item->title) ?>
                <span class="shop-checkout-line-quantity">× <?= $item->quantity ?></span>
                <?php if ($item->variant_title !== ''): ?>
                    <span class="shop-checkout-line-variant"><?= Html::encode($item->variant_title) ?></span>
                <?php endif ?>
            </span>
            <span class="shop-checkout-line-total"><?= $amount($item->total) ?></span>
        </li>
    <?php endforeach ?>
</ul>
<dl class="shop-checkout-amounts">
    <dt><?= Yii::t('shop', 'Subtotal') ?></dt>
    <dd><?= $amount($order->subtotal) ?></dd>
    <?php foreach ($order->discounts as $discount): ?>
        <dt><?= Html::encode($discount['label']) ?></dt>
        <dd>−<?= $amount($discount['amount']) ?></dd>
    <?php endforeach ?>
    <dt><?= Yii::t('shop', 'Delivery') ?></dt>
    <dd><?= (float) $order->shipping > 0 ? $amount($order->shipping) : Yii::t('shop', 'Free') ?></dd>
    <?php if ((float) $order->payment_fee > 0): ?>
        <dt><?= $order->getAttributeLabel('payment_fee') ?></dt>
        <dd><?= $amount($order->payment_fee) ?></dd>
    <?php endif ?>
    <dt class="shop-checkout-grand"><?= Yii::t('shop', 'Total') ?></dt>
    <dd class="shop-checkout-grand"><?= $amount($order->total) ?></dd>
    <?php foreach ($order->taxLines() as $taxLine): ?>
        <dt class="shop-checkout-tax"><?= Html::encode($taxLine['label']) ?></dt>
        <dd class="shop-checkout-tax"><?= $amount($taxLine['amount']) ?></dd>
    <?php endforeach ?>
</dl>
