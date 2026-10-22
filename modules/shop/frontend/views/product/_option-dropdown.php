<?php

/**
 * One option of a product shown as a dropdown: one select named `values[<attribute id>]` with the values its
 * variants come in, the chosen one selected (see `_option-radio.php` for the rest). A template a theme replaces.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Attribute $attribute The option. */
/** @var modules\shop\common\models\Attribute[] $values The values to choose from, in their order. */
/** @var int[] $selectedValueIds The ids of the values of the variant shown. */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<fieldset class="shop-option shop-option-dropdown" data-option>
    <legend class="shop-option-name"><?= Html::encode($attribute->name) ?></legend>
    <?= Html::dropDownList("values[$attribute->id]", array_intersect($selectedValueIds, ArrayHelper::getColumn($values, 'id')), ArrayHelper::map($values, 'id', 'name'), [
        'class' => 'form-control shop-option-select',
        'aria-label' => $attribute->name,
    ]) ?>
</fieldset>
