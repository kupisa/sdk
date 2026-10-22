<?php

/**
 * The button of a card in a list, if it has one (see Product::cardAction()): for a product of one kind the form that
 * puts it into the cart, its quantity with − and + next to it as on the page of the product (starting at the fewest
 * that may be ordered), for a product with options the way to its page to choose them. In the preview of the editor
 * the form is shown but cannot be sent.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Product $product */
/** @var string|null $action One of the `Product::CARD_*` constants, or null for no button. */

use modules\shop\common\models\Product;
use yii\helpers\Html;

?>
<?php if ($action === Product::CARD_ADD): ?>
    <?= Html::beginForm(['/shop/cart/add'], 'post', ['class' => 'shop-card-action']) ?>
        <?= Html::hiddenInput('product_id', (string) $product->id) ?>
        <?= Html::beginTag('fieldset', ['class' => 'shop-card-buy', 'disabled' => !empty($this->params['preview'])]) ?>
            <div class="shop-stepper">
                <?= Html::button('−', [
                    'class'              => 'shop-stepper-button',
                    'data-quantity-step' => -1,
                    'aria-label'         => Yii::t('shop', 'One less'),
                ]) ?>
                <?= Html::input('number', 'quantity', (string) $product->minQuantity(), [
                    'class'         => 'form-control',
                    'min'           => $product->minQuantity(),
                    'max'           => $product->getDefaultVariant()->maxQuantity(),
                    'step'          => $product->quantityStep(),
                    'data-quantity' => true,
                    'data-min'      => $product->minQuantity(),
                    'data-step'     => $product->quantityStep(),
                    'aria-label'    => Yii::t('shop', 'Quantity'),
                ]) ?>
                <?= Html::button('+', [
                    'class'              => 'shop-stepper-button',
                    'data-quantity-step' => 1,
                    'aria-label'         => Yii::t('shop', 'One more'),
                ]) ?>
            </div>
            <?= Html::submitButton(Yii::t('shop', 'Add to cart'), ['class' => 'btn btn-primary']) ?>
        <?= Html::endTag('fieldset') ?>
    <?= Html::endForm() ?>
<?php elseif ($action === Product::CARD_OPTIONS): ?>
    <div class="shop-card-action">
        <?= Html::a(
            Yii::t('shop', 'Choose options'),
            $product->getUrl(),
            ['class' => 'btn btn-outline-primary'],
        ) ?>
    </div>
<?php endif ?>
