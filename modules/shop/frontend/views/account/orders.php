<?php

/**
 * The orders of the customer in their account, the latest first, paged: the number, when it was placed, its
 * status, the status of its payment and what it came to, each leading to the order.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Order[] $orders */
/** @var yii\data\Pagination $pagination */

use frontend\widgets\Pager;
use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;

ShopAsset::register($this);
?>
<section class="account-section shop-account-orders">
    <h2 class="account-section-title"><?= Yii::t('shop', 'Orders') ?></h2>
    <?php if ($orders === []): ?>
        <p class="text-muted"><?= Yii::t('shop', 'You have not ordered anything yet.') ?></p>
        <?= Html::a(Yii::t('shop', 'Continue shopping'), ['/shop/product/index'], ['class' => 'btn btn-primary']) ?>
    <?php else: ?>
        <p class="text-muted"><?= Yii::t('shop', 'Everything you ordered, the latest first.') ?></p>
        <table class="shop-orders">
            <thead>
                <tr>
                    <th><?= Yii::t('shop', 'Order') ?></th>
                    <th><?= Yii::t('shop', 'Placed') ?></th>
                    <th><?= Yii::t('shop', 'Status') ?></th>
                    <th><?= Yii::t('shop', 'Payment') ?></th>
                    <th class="shop-orders-total"><?= Yii::t('shop', 'Total') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= Html::a(Html::encode($order->getLabel()), ['order', 'number' => $order->number], ['class' => 'shop-orders-link']) ?></td>
                        <td><?= Yii::$app->formatter->asDate($order->created) ?></td>
                        <td><?= Html::encode($order->statusLabel()) ?></td>
                        <td><?= Html::encode($order->paymentStatusLabel()) ?></td>
                        <td class="shop-orders-total"><?= Yii::$app->formatter->asCurrency($order->total, $order->currency) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?= Pager::widget(['pagination' => $pagination]) ?>
    <?php endif ?>
</section>
