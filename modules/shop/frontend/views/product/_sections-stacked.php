<?php

/**
 * The sections of a product one under the other (see ProductController::sections()), each under its title; a
 * section alone has none.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $sections The sections, each with its `name`, `label` and `content`. */
/** @var bool $titles Whether the sections stand under their titles. */

use yii\helpers\Html;

?>
<section class="shop-product-sections mt-5 mb-5">
    <?php foreach ($sections as $section): ?>
        <div class="shop-product-section" id="<?= Html::encode($section['name']) ?>">
            <?php if ($titles): ?>
                <div class="container">
                    <h2 class="shop-product-section-title"><?= Html::encode($section['label']) ?></h2>
                </div>
            <?php endif ?>
            <?= $section['content'] ?>
        </div>
    <?php endforeach ?>
</section>
