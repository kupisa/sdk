<?php

/**
 * The specifications of a product, a section of its page (see ProductController::sections()): its attributes,
 * each with its values after it.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array $specifications The attributes of the product, each its `attribute` with its `values` (see Product::getSpecifications()). */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="container">
    <dl class="shop-product-specifications">
        <?php foreach ($specifications as $specification): ?>
            <div>
                <dt><?= Html::encode($specification['attribute']->name) ?></dt>
                <dd><?= Html::encode(implode(', ', ArrayHelper::getColumn($specification['values'], 'name'))) ?></dd>
            </div>
        <?php endforeach ?>
    </dl>
</div>
