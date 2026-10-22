<?php

/**
 * One product in the catalogue: its main picture, whether it is in stock ("In stock", "On order", "Sold out"), its
 * title and its price (the lowest one as "From …" when its variants differ in price, the old price struck through
 * while it is on sale), and on the picture its tags shown as badges ("New", in their colours) and a badge when none
 * of it can be bought now; under the title what the modules add to a card (see Product::cardExtras()). The whole
 * card leads to the product. While the shop shows a button on the cards (see Product::cardAction()), it ends with
 * the quantity and "Add to cart" for a product of one kind, or the way to the options of one with variants (see
 * `_card-action.php`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Product $product */

use yii\helpers\Html;
?>
<article class="shop-card">
    <div class="shop-card-image">
        <?= $product->getMainImage()->img(['alt' => $product->getMainImage()->alt ?: $product->title]) ?>
        <?php if ($product->getBadges() !== [] || !$product->isAvailable()): ?>
            <div class="shop-card-badges">
                <?php foreach ($product->getBadges() as $tag): ?>
                    <span class="badge shop-badge" style="<?= Html::encode($tag->badgeStyle()) ?>"><?= Html::encode($tag->name) ?></span>
                <?php endforeach ?>
                <?php if (!$product->isAvailable()): ?>
                    <span class="badge shop-card-badge"><?= Yii::t('shop', 'Sold out') ?></span>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>
    <p class="shop-card-availability shop-availability-<?= Html::encode($product->availabilityState()) ?>">
        <?= Html::encode($product->availabilityLabel()) ?>
    </p>
    <h2 class="shop-card-title"><?= Html::a(Html::encode($product->title), $product->getUrl()) ?></h2>
    <?= $product->cardExtras() ?>
    <?= $this->render('_price', ['variant' => $product->getCheapestVariant(), 'from' => $product->pricesDiffer()]) ?>
    <?= $this->render('_card-action', ['product' => $product, 'action' => $product->cardAction()]) ?>
</article>
