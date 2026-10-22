<?php

/**
 * The page after an order is placed, laid out like the checkout: on the left the tick and the number of the
 * order, the greeting, that the confirmation was sent (or, for an order paid online and not paid yet, the way to
 * pay it), how to pay (the instructions of the way of payment,
 * when it has any), the details of the order (the contact, where it goes, how it is delivered and paid, the
 * note; see _order-details.php) and the button back to the shop with the pages of the site a customer may need
 * under it; on the right, grey, what was ordered with the amounts (_order-lines.php). A template a theme
 * replaces: everything shown comes from the controller (see CheckoutController::actionThankYou()).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The greeting, with the first name of the customer. */
/** @var modules\shop\common\models\Order $order */
/** @var modules\shop\common\models\OrderItem[] $items */
/** @var string $placed When the order was placed, as the visitor reads it. */
/** @var string $shippingName */
/** @var string $paymentLabel */
/** @var string $instructions What the customer does to pay; empty for a way without instructions. */
/** @var string|null $paymentUrl Where the customer pays the order, while it awaits a payment online; null otherwise. */
/** @var list<string> $shippingLines */
/** @var list<string> $billingLines */
/** @var list<string> $companyLines The company the customer ordered as, see Order::companyLines(); empty for none. */
/** @var list<array{label: string, url: string}> $links The pages of the site shown under the button, those the site has. */

use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;

ShopAsset::register($this);
?>
<div class="shop-checkout shop-thank-you container">
    <div class="shop-checkout-layout">
        <div class="shop-checkout-main">
            <div class="shop-thank-you-head">
                <span class="shop-thank-you-tick" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/></svg>
                </span>
                <div>
                    <p class="shop-thank-you-number"><?= Html::encode($order->getLabel()) ?> · <?= Html::encode($placed) ?></p>
                    <h1><?= Html::encode($title) ?></h1>
                </div>
            </div>

            <?php if ($paymentUrl !== null): ?>
                <section class="shop-checkout-section shop-thank-you-card shop-thank-you-payment">
                    <h2 class="h4"><?= Yii::t('shop', 'Your order awaits payment') ?></h2>
                    <p><?= Yii::t('shop', 'Once it is paid, we will send a confirmation to {email}.', ['email' => '<strong>' . Html::encode($order->email) . '</strong>']) ?></p>
                    <?= Html::a(Yii::t('shop', 'Pay now'), $paymentUrl, ['class' => 'btn btn-primary']) ?>
                </section>
            <?php else: ?>
                <section class="shop-checkout-section shop-thank-you-card">
                    <h2 class="h4"><?= Yii::t('shop', 'Your order is confirmed') ?></h2>
                    <p class="mb-0"><?= Yii::t('shop', 'We sent a confirmation to {email}. We will let you know when it is on its way.', ['email' => '<strong>' . Html::encode($order->email) . '</strong>']) ?></p>
                </section>
            <?php endif ?>

            <?php if ($instructions !== ''): ?>
                <section class="shop-checkout-section shop-thank-you-card shop-thank-you-instructions">
                    <h2 class="h4"><?= Yii::t('shop', 'How to pay') ?></h2>
                    <p class="mb-0"><?= nl2br(Html::encode($instructions)) ?></p>
                </section>
            <?php endif ?>

            <section class="shop-checkout-section shop-thank-you-card">
                <h2 class="h4"><?= Yii::t('shop', 'Order details') ?></h2>
                <?= $this->render('_order-details', [
                    'order' => $order,
                    'shippingName' => $shippingName,
                    'paymentLabel' => $paymentLabel,
                    'shippingLines' => $shippingLines,
                    'billingLines' => $billingLines,
                    'companyLines' => $companyLines,
                ]) ?>
            </section>

            <?= Html::a(Yii::t('shop', 'Continue shopping'), ['/shop/product/index'], ['class' => 'btn btn-primary btn-lg shop-checkout-submit']) ?>
            <?php if ($links !== []): ?>
                <nav class="shop-checkout-links" aria-label="<?= Html::encode(Yii::t('shop', 'Pages of the shop')) ?>">
                    <?php foreach ($links as $link): ?>
                        <?= Html::a(Html::encode($link['label']), $link['url']) ?>
                    <?php endforeach ?>
                </nav>
            <?php endif ?>
        </div>

        <aside class="shop-checkout-summary">
            <div class="shop-checkout-summary-inner">
                <h2 class="h4"><?= Yii::t('shop', 'Your order') ?></h2>
                <?= $this->render('_order-lines', ['order' => $order, 'items' => $items]) ?>
            </div>
        </aside>
    </div>
</div>
