<?php

/**
 * The price of a variant and, while it is on sale, the old price struck through next to it; or, on the card of a
 * product whose variants differ in price, the lowest one as "From …"; with or without VAT, as the shop shows its
 * prices, and after it the unit the price is for ("/ kg") while the product has one. The script of the shop rebuilds the same
 * markup on the page of a product when the visitor picks another variant (see `public/js/shop.js`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Variant $variant */
/** @var bool $from Whether the price is the lowest of several. */

use yii\helpers\Html;

?>
<p class="shop-price mb-0">
    <?php if ($from): ?>
        <span><?= Yii::t('shop', 'From {price}', ['price' => Yii::$app->formatter->asCurrency($variant->getShownPrice())]) ?></span>
    <?php elseif ($variant->isOnSale()): ?>
        <span class="shop-price-sale"><?= Yii::$app->formatter->asCurrency($variant->getShownPrice()) ?></span>
        <s><?= Yii::$app->formatter->asCurrency($variant->getShownComparePrice()) ?></s>
    <?php else: ?>
        <span><?= Yii::$app->formatter->asCurrency($variant->getShownPrice()) ?></span>
    <?php endif ?>
    <?php if ($variant->product->unitSymbol() !== ''): ?>
        <span class="shop-price-unit">/ <?= Html::encode($variant->product->unitSymbol()) ?></span>
    <?php endif ?>
</p>
