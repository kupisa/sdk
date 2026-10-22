<?php

/**
 * One option of a product shown as pictures: a radio button per value as its picture, named for who cannot see
 * it and on hover; a value without a picture yet shows its name (see `_option-radio.php` for the rest). A
 * template a theme replaces.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Attribute $attribute The option. */
/** @var modules\shop\common\models\Attribute[] $values The values to choose from, in their order. */
/** @var int[] $selectedValueIds The ids of the values of the variant shown. */

use yii\helpers\Html;
?>
<fieldset class="shop-option shop-option-image" data-option>
    <legend class="shop-option-name"><?= Html::encode($attribute->name) ?></legend>
    <div class="shop-option-values">
        <?php foreach ($values as $value): ?>
            <label class="shop-option-value" data-tooltip="<?= Html::encode($value->name) ?>">
                <?= Html::radio("values[$attribute->id]", in_array($value->id, $selectedValueIds, true), ['value' => $value->id, 'aria-label' => $value->name]) ?>
                <?php if ($value->getImageMedia() !== null): ?>
                    <span class="shop-option-picture"><?= $value->getImageMedia()->img(['thumbnail' => true, 'alt' => '']) ?></span>
                <?php else: ?>
                    <span><?= Html::encode($value->name) ?></span>
                <?php endif ?>
            </label>
        <?php endforeach ?>
    </div>
</fieldset>
