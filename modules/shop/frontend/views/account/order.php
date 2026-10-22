<?php

/**
 * One order of the customer in their account: its number and when it was placed, its status, how to pay while
 * it is unpaid (the way to pay it online, or the instructions of the way of payment), its details (see ../checkout/_order-details.php) and
 * what it holds with the amounts (../checkout/_order-lines.php), and under them what the modules let the
 * customer do with it (the way to review what was bought).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Order $order */
/** @var modules\shop\common\models\OrderItem[] $items */
/** @var string $placed When the order was placed, as the customer reads it. */
/** @var string $shippingName */
/** @var string $paymentLabel */
/** @var string $instructions What the customer does to pay; empty once paid or for a way without instructions. */
/** @var string|null $paymentUrl Where the customer pays the order, while it awaits a payment online; null otherwise. */
/** @var list<string> $shippingLines */
/** @var list<string> $billingLines */
/** @var list<string> $companyLines The company the customer ordered as, see Order::companyLines(); empty for none. */
/** @var list<array{label: string, url: string|array}> $actions What the customer may do with the order, as the modules add it (see Order::accountActions()). */

use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;

ShopAsset::register($this);
?>
<section class="account-section shop-account-order">
    <p class="shop-thank-you-number"><?= Html::encode($placed) ?></p>
    <h2 class="account-section-title"><?= Html::encode($order->getLabel()) ?></h2>
    <p class="shop-account-order-status">
        <?= Html::encode($order->statusLabel()) ?> · <?= Html::encode($order->paymentStatusLabel()) ?>
    </p>

    <?php if ($paymentUrl !== null): ?>
        <section class="shop-thank-you-card shop-thank-you-payment">
            <h3 class="h4"><?= Yii::t('shop', 'Your order awaits payment') ?></h3>
            <?= Html::a(Yii::t('shop', 'Pay now'), $paymentUrl, ['class' => 'btn btn-primary']) ?>
        </section>
    <?php endif ?>

    <?php if ($instructions !== ''): ?>
        <section class="shop-thank-you-card shop-thank-you-instructions">
            <h3 class="h4"><?= Yii::t('shop', 'How to pay') ?></h3>
            <p class="mb-0"><?= nl2br(Html::encode($instructions)) ?></p>
        </section>
    <?php endif ?>

    <section class="shop-thank-you-card">
        <h3 class="h4"><?= Yii::t('shop', 'Order details') ?></h3>
        <?= $this->render('../checkout/_order-details', [
            'order' => $order,
            'shippingName' => $shippingName,
            'paymentLabel' => $paymentLabel,
            'shippingLines' => $shippingLines,
            'billingLines' => $billingLines,
            'companyLines' => $companyLines,
        ]) ?>
    </section>

    <section class="shop-thank-you-card">
        <h3 class="h4"><?= Yii::t('shop', 'Your order') ?></h3>
        <?= $this->render('../checkout/_order-lines', ['order' => $order, 'items' => $items]) ?>
    </section>

    <?php foreach ($actions as $action): ?>
        <?= Html::a(Html::encode($action['label']), $action['url'], ['class' => 'btn btn-primary']) ?>
    <?php endforeach ?>
    <?= Html::a(Yii::t('shop', 'Back to orders'), ['orders'], ['class' => 'btn btn-outline-secondary']) ?>
</section>
