<?php

/**
 * One option of a product shown as colours: a radio button per value as a circle of its colour, named for who
 * cannot see it and on hover; a value without a colour yet shows its name (see `_option-radio.php` for the
 * rest). A template a theme replaces.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Attribute $attribute The option. */
/** @var modules\shop\common\models\Attribute[] $values The values to choose from, in their order. */
/** @var int[] $selectedValueIds The ids of the values of the variant shown. */

use yii\helpers\Html;
?>
<fieldset class="shop-option shop-option-color" data-option>
    <legend class="shop-option-name"><?= Html::encode($attribute->name) ?></legend>
    <div class="shop-option-values">
        <?php foreach ($values as $value): ?>
            <label class="shop-option-value" data-tooltip="<?= Html::encode($value->name) ?>">
                <?= Html::radio("values[$attribute->id]", in_array($value->id, $selectedValueIds, true), ['value' => $value->id, 'aria-label' => $value->name]) ?>
                <?php if ($value->color !== null): ?>
                    <span class="shop-option-swatch" style="background-color: <?= Html::encode($value->color) ?>"></span>
                <?php else: ?>
                    <span><?= Html::encode($value->name) ?></span>
                <?php endif ?>
            </label>
        <?php endforeach ?>
    </div>
</fieldset>
