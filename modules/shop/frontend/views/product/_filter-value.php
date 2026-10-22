<?php

/**
 * One value (or tag) in the filters of a list of products: a checkbox with its swatch, the colour or the picture of the
 * value, when it has one, its name and the number of products it would give; greyed out while it would give
 * none.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $name The name of the checkbox, the slug of the attribute as a list (`colour[]`). */
/** @var array{value: modules\shop\common\models\Attribute|modules\shop\common\models\Tag, swatch: array|null, count: int, checked: bool, disabled: bool} $item See `ProductFilter::facets()`. */

use yii\helpers\Html;
?>
<label class="shop-filter-value<?= $item['disabled'] ? ' disabled' : '' ?>">
    <?= Html::checkbox($name, $item['checked'], ['value' => $item['value']->slug, 'disabled' => $item['disabled']]) ?>
    <?php if (isset($item['swatch']['color'])): ?>
        <span class="shop-filter-swatch" style="background-color: <?= Html::encode($item['swatch']['color']) ?>"></span>
    <?php elseif (isset($item['swatch']['image'])): ?>
        <span class="shop-filter-swatch shop-filter-picture"><?= $item['swatch']['image']->img(['thumbnail' => true, 'alt' => '']) ?></span>
    <?php endif ?>
    <span class="shop-filter-name"><?= Html::encode($item['value']->name) ?></span>
    <span class="shop-filter-count"><?= $item['count'] ?></span>
</label>
