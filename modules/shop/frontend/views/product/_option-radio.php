<?php

/**
 * One option of a product ("Size") with the values its variants come in, as radio buttons shown as pills with
 * their names, one of them chosen: how an attribute is shown unless it says otherwise (see
 * `Attribute::getDisplay()`; the other ways are `_option-dropdown.php`, `_option-color.php` and
 * `_option-image.php`). The inputs are named `values[<attribute id>]`, so they go with the form that puts the
 * product in the cart, and the script of the shop switches the page to the variant they name (see
 * `public/js/shop.js`). A template a theme replaces.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Attribute $attribute The option. */
/** @var modules\shop\common\models\Attribute[] $values The values to choose from, in their order. */
/** @var int[] $selectedValueIds The ids of the values of the variant shown. */

use yii\helpers\Html;
?>
<fieldset class="shop-option shop-option-radio" data-option>
    <legend class="shop-option-name"><?= Html::encode($attribute->name) ?></legend>
    <div class="shop-option-values">
        <?php foreach ($values as $value): ?>
            <label class="shop-option-value">
                <?= Html::radio("values[$attribute->id]", in_array($value->id, $selectedValueIds, true), ['value' => $value->id]) ?>
                <span><?= Html::encode($value->name) ?></span>
            </label>
        <?php endforeach ?>
    </div>
</fieldset>
