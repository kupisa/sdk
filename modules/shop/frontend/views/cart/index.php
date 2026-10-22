<?php

/**
 * The cart on the site: a line per variant with its picture, the title of its product, its own title ("Red / M")
 * while the product has options, its price with the unit it is for, the stepper of its quantity (by the step
 * of the product, see `data-min` and `data-step`) with the way to take it out, and what
 * the line costs, with or without VAT as the shop shows its prices; then the discount code while the shop takes
 * them (_discount-code.php, its field in a form of its own, `shop-discount-code`, after the form of the cart),
 * the subtotal, saying so when it is without VAT, the discounts and the total after them while there are any, a
 * word that the delivery and the payment are counted at the checkout, the way to the checkout and back to the
 * shop. The whole page is one form sent to `cart/update`: the quantities as
 * `quantity[<variant id>]`, and the "Remove" button of a line as `remove` with the id of its variant; the button
 * that updates it is for a visitor without the script, which sends every change as it is made and swaps the
 * form in from the answer (`data-cart-part`, with the number of pieces for the header in `data-cart-count`,
 * see `public/js/shop.js`). An empty cart says so and leads to the shop. A template a theme replaces:
 * everything shown comes from the controller (see CartController::cartParams()).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title */
/** @var modules\shop\common\models\CartLine[] $lines */
/** @var int $count How many pieces the cart holds. */
/** @var string $subtotalLabel What the lines cost together, as the visitor reads it. */
/** @var list<array{label: string, amount: string}> $discounts The discounts, as the visitor reads them; empty for none. */
/** @var string $totalLabel What the cart costs after the discounts. */
/** @var bool $takesCodes Whether the shop takes discount codes. */
/** @var string $code The discount code applied; empty for none. */
/** @var string $codeInput The code the visitor typed, while it did not apply. */
/** @var string $codeError What was wrong with the code; empty for nothing. */
/** @var string $taxNote The words next to the subtotal that say it has no VAT in it ("excl. VAT"); empty for a subtotal with VAT. */

use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;

ShopAsset::register($this);

$formatter = Yii::$app->formatter;
?>
<div class="shop-cart container mt-4 mb-5">
    <?php if ($lines === []): ?>
        <div class="shop-cart-empty" data-cart-part="cart" data-cart-count="0">
            <h1><?= Html::encode($title) ?></h1>
            <p><?= Yii::t('shop', 'Your cart is empty.') ?></p>
            <?= Html::a(Yii::t('shop', 'Continue shopping'), ['/shop/product/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php else: ?>
        <?= Html::beginForm(['update'], 'post', ['id' => 'shop-cart-form', 'data-cart-part' => 'cart', 'data-cart-count' => $count]) ?>
            <h1><?= Html::encode($title) ?></h1>

            <ul class="shop-cart-lines">
                <?php foreach ($lines as $line): ?>
                    <li class="shop-cart-line" data-variant="<?= $line->variant->id ?>">
                        <?= Html::a($line->variant->getMainImage()->img(['thumbnail' => true, 'alt' => '']), $line->variant->getUrl(), ['class' => 'shop-cart-image']) ?>
                        <div class="shop-cart-details">
                            <?= Html::a(Html::encode($line->product->title), $line->variant->getUrl(), ['class' => 'shop-cart-title']) ?>
                            <?php if ($line->variant->getTitle() !== ''): ?>
                                <div class="shop-cart-variant"><?= Html::encode($line->variant->getTitle()) ?></div>
                            <?php endif ?>
                        </div>
                        <div class="shop-cart-price">
                            <?= $formatter->asCurrency($line->variant->getShownPrice()) ?>
                            <?php if ($line->product->unitSymbol() !== ''): ?>
                                / <?= Html::encode($line->product->unitSymbol()) ?>
                            <?php endif ?>
                        </div>
                        <div class="shop-cart-quantity">
                            <div class="shop-stepper">
                                <?= Html::button('−', ['class' => 'shop-stepper-button', 'data-quantity-step' => -1, 'aria-label' => Yii::t('shop', 'One less')]) ?>
                                <?= Html::input('number', "quantity[{$line->variant->id}]", (string) $line->quantity, [
                                    'class' => 'form-control',
                                    'min' => 0,
                                    'max' => $line->variant->maxQuantity(),
                                    'step' => 1,
                                    'data-quantity' => true,
                                    'data-min' => $line->product->minQuantity(),
                                    'data-step' => $line->product->quantityStep(),
                                    'aria-label' => Yii::t('shop', 'Quantity of “{product}”', ['product' => $line->product->title]),
                                ]) ?>
                                <?= Html::button('+', ['class' => 'shop-stepper-button', 'data-quantity-step' => 1, 'aria-label' => Yii::t('shop', 'One more')]) ?>
                            </div>
                        </div>
                        <div class="shop-cart-total"><?= $formatter->asCurrency($line->getShownTotal()) ?></div>
                        <?= Html::submitButton(Yii::t('shop', 'Remove'), [
                            'class' => 'shop-cart-remove',
                            'name' => 'remove',
                            'value' => $line->variant->id,
                        ]) ?>
                    </li>
                <?php endforeach ?>
            </ul>

            <div class="shop-cart-summary">
                <?php if ($takesCodes): ?>
                    <?= $this->render('_discount-code', [
                        'formId'    => 'shop-discount-code',
                        'code'      => $code,
                        'codeInput' => $codeInput,
                        'codeError' => $codeError,
                    ]) ?>
                <?php endif ?>
                <div class="shop-cart-subtotal">
                    <span>
                        <?= Yii::t('shop', 'Subtotal') ?>
                        <?php if ($taxNote !== ''): ?>
                            <span class="shop-cart-tax"><?= Html::encode($taxNote) ?></span>
                        <?php endif ?>
                    </span>
                    <span class="shop-cart-sum"><?= Html::encode($subtotalLabel) ?></span>
                </div>
                <?php if ($discounts !== []): ?>
                    <?php foreach ($discounts as $discount): ?>
                        <div class="shop-cart-discount">
                            <span><?= Html::encode($discount['label']) ?></span>
                            <span><?= Html::encode($discount['amount']) ?></span>
                        </div>
                    <?php endforeach ?>
                    <div class="shop-cart-subtotal">
                        <span><?= Yii::t('shop', 'Total') ?></span>
                        <span class="shop-cart-sum"><?= Html::encode($totalLabel) ?></span>
                    </div>
                <?php endif ?>
                <p class="shop-cart-note"><?= Yii::t('shop', 'Delivery and payment are counted at the checkout.') ?></p>
                <?= Html::submitButton(Yii::t('shop', 'Update cart'), ['class' => 'btn btn-outline-secondary shop-cart-update']) ?>
                <?= Html::a(Yii::t('shop', 'Checkout'), ['/shop/checkout/index'], ['class' => 'btn btn-primary btn-lg shop-cart-checkout']) ?>
                <?= Html::a(Yii::t('shop', 'Continue shopping'), ['/shop/product/index'], ['class' => 'shop-cart-continue']) ?>
            </div>
        <?= Html::endForm() ?>

        <?php if ($takesCodes): ?>
            <?= Html::beginForm(['update'], 'post', ['id' => 'shop-discount-code', 'data-discount-code-form' => true]) ?>
            <?= Html::endForm() ?>
        <?php endif ?>
    <?php endif ?>
</div>
