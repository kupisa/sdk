<?php

/**
 * Products of the shop on a page: the title with the link to more of them on its right, then the cards of the
 * catalogue (see `views/product/_card.php`, which a theme replaces for both) in a grid, or in a carousel: one row
 * that scrolls sideways, with an arrow on each side that moves it by what is in view (see `public/js/shop.js`, which
 * hides an arrow at its end; without the script the row is still swiped or scrolled through).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title, plain, or '' for none. */
/** @var string $link The link to more products as an anchor, or '' for none. */
/** @var modules\shop\common\models\Product[] $products */
/** @var bool $carousel Whether the products stand in a carousel rather than in a grid. */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== '' || $link !== ''): ?>
        <div class="shop-products-head">
            <?php if ($title !== ''): ?>
                <h2 class="shop-products-title" data-value="title"><?= Html::encode($title) ?></h2>
            <?php endif ?>
            <?= $link ?>
        </div>
    <?php endif ?>
    <?php if ($carousel): ?>
        <div class="shop-carousel" data-carousel>
            <div class="shop-carousel-track" data-carousel-track>
                <?php foreach ($products as $product): ?>
                    <?= $this->render('@modules/shop/frontend/views/product/_card', ['product' => $product]) ?>
                <?php endforeach ?>
            </div>
            <button type="button" class="shop-carousel-arrow shop-carousel-prev" data-carousel-step="-1" aria-label="<?= Yii::t('shop', 'Previous products') ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button type="button" class="shop-carousel-arrow shop-carousel-next" data-carousel-step="1" aria-label="<?= Yii::t('shop', 'Next products') ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
    <?php else: ?>
        <div class="shop-grid shop-grid-products">
            <?php foreach ($products as $product): ?>
                <?= $this->render('@modules/shop/frontend/views/product/_card', ['product' => $product]) ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</div>
