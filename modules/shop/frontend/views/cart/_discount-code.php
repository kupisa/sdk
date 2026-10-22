<?php

/**
 * The discount code, in the summary of the cart and of the checkout: the field with "Apply" while no code is
 * applied, the code applied with the way to remove it otherwise, and what was wrong with the code under them.
 * The field and its buttons belong to a form of their own by their `form` attribute (`$formId`, rendered after
 * the form of the page, since forms do not nest), so pressing Enter in the page still sends the page and
 * pressing it in the field applies the code; the script sends them with the page instead (see
 * `public/js/shop.js`). A template a theme replaces: everything shown comes from the controller.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $formId The id of the form the field and its buttons belong to. */
/** @var string $code The code applied; empty for none. */
/** @var string $codeInput What the field holds: the code the visitor typed, while it did not apply. */
/** @var string $codeError What was wrong with the code; empty for nothing. */

use yii\helpers\Html;
?>
<div class="shop-discount-code">
    <?php if ($code === ''): ?>
        <div class="shop-discount-code-field">
            <?= Html::input('text', 'discount_code', $codeInput, [
                'form'         => $formId,
                'class'        => 'form-control',
                'placeholder'  => Yii::t('shop', 'Discount code'),
                'aria-label'   => Yii::t('shop', 'Discount code'),
                'autocomplete' => 'off',
            ]) ?>
            <?= Html::submitButton(Yii::t('shop', 'Apply'), [
                'form'  => $formId,
                'name'  => 'apply_code',
                'value' => '1',
                'class' => 'btn btn-outline-secondary',
            ]) ?>
        </div>
    <?php else: ?>
        <div class="shop-discount-code-applied">
            <span class="shop-discount-code-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12V4a1 1 0 0 1 1-1h8l9 9-9 9z"/><path d="M7.5 7.5h.01"/></svg>
                <?= Html::encode($code) ?>
            </span>
            <?= Html::submitButton(Yii::t('shop', 'Remove'), [
                'form'  => $formId,
                'name'  => 'remove_code',
                'value' => '1',
                'class' => 'shop-discount-code-remove',
            ]) ?>
        </div>
    <?php endif ?>
    <?php if ($codeError !== ''): ?>
        <p class="shop-discount-code-error"><?= Html::encode($codeError) ?></p>
    <?php endif ?>
</div>
