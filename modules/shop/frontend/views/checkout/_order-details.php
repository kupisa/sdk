<?php

/**
 * The details of an order as the customer reads them, on the "Thank you" page and on the page of the order in
 * their account: the contact, where it goes, how it is delivered and paid, who is invoiced, the company they
 * ordered as, and the note.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Order $order */
/** @var string $shippingName */
/** @var string $paymentLabel */
/** @var list<string> $shippingLines */
/** @var list<string> $billingLines */
/** @var list<string> $companyLines */

use yii\helpers\Html;

?>
<dl class="shop-thank-you-details">
    <div>
        <dt><?= Yii::t('shop', 'Contact') ?></dt>
        <dd>
            <?= Html::encode($order->email) ?>
            <?php if ($order->phone !== null): ?>
                <br><?= Html::encode($order->phone) ?>
            <?php endif ?>
        </dd>
    </div>
    <div>
        <dt><?= Yii::t('shop', 'Delivery address') ?></dt>
        <dd><?= implode('<br>', array_map(Html::encode(...), $shippingLines)) ?></dd>
    </div>
    <div>
        <dt><?= Yii::t('shop', 'Delivery method') ?></dt>
        <dd><?= Html::encode($shippingName) ?></dd>
    </div>
    <div>
        <dt><?= Yii::t('shop', 'Payment method') ?></dt>
        <dd><?= Html::encode($paymentLabel) ?></dd>
    </div>
    <?php if ($billingLines !== []): ?>
        <div>
            <dt><?= Yii::t('shop', 'Billing address') ?></dt>
            <dd><?= implode('<br>', array_map(Html::encode(...), $billingLines)) ?></dd>
        </div>
    <?php endif ?>
    <?php if ($companyLines !== []): ?>
        <div>
            <dt><?= Yii::t('shop', 'Company info') ?></dt>
            <dd><?= implode('<br>', array_map(Html::encode(...), $companyLines)) ?></dd>
        </div>
    <?php endif ?>
    <?php if ($order->note !== null): ?>
        <div>
            <dt><?= Yii::t('shop', 'Note') ?></dt>
            <dd><?= nl2br(Html::encode($order->note)) ?></dd>
        </div>
    <?php endif ?>
</dl>
