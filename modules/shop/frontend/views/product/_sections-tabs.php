<?php

/**
 * The sections of a product as tabs (see ProductController::sections()): the links to the sections in a row,
 * then the sections, each under its title. The script of the shop (`public/js/shop.js`, `data-tabs`) turns the
 * links into tabs and shows one section at a time, the first one or the one the address names
 * (`#specifications`); without it the sections stand one under the other and the links lead to them.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $sections The sections, each with its `name`, `label` and `content`. */

use yii\helpers\Html;

?>
<section class="shop-product-sections mt-5 mb-5" data-tabs>
    <div class="container">
        <nav class="shop-tabs" data-tab-list aria-label="<?= Yii::t('shop', 'Product information') ?>">
            <?php foreach ($sections as $section): ?>
                <a class="shop-tab" href="#<?= Html::encode($section['name']) ?>" id="tab-<?= Html::encode($section['name']) ?>" data-tab><?= Html::encode($section['label']) ?></a>
            <?php endforeach ?>
        </nav>
    </div>
    <?php foreach ($sections as $section): ?>
        <div class="shop-product-section" id="<?= Html::encode($section['name']) ?>" data-tab-panel>
            <div class="container">
                <h2 class="shop-product-section-title"><?= Html::encode($section['label']) ?></h2>
            </div>
            <?= $section['content'] ?>
        </div>
    <?php endforeach ?>
</section>
