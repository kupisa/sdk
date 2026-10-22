<?php

/**
 * The company the customer orders as, in the section of the invoice of the checkout: while it is optional, the
 * switch "I am ordering as a company" that unfolds its fields; then the name of the company across the row and
 * the numbers the checkout asks for side by side (see `CheckoutForm::companyFields()`). A part of the page the
 * refresh swaps only once the fields asked for change (the country the order is billed to changed them).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $activeForm */
/** @var modules\shop\frontend\models\CheckoutForm $form */
/** @var bool $optional Whether ordering as a company is a choice of the customer. */
/** @var bool $opensBilling Whether ordering as a company unfolds the billing address, folded behind "Same as the delivery address". */
/** @var array<string, string> $fields The fields of the company asked for, their label by name. */
/** @var string $version What the fields are, as a fingerprint. */

use yii\helpers\Html;

?>
<div data-checkout-part="company" data-checkout-version="<?= Html::encode($version) ?>">
    <?php if ($optional): ?>
        <?= $activeForm->field($form, 'company_buyer', ['options' => ['class' => 'form-group checkbox']])->checkbox([
            'data-checkout-toggle'  => 'company',
            'data-checkout-company' => $opensBilling,
        ]) ?>
    <?php endif ?>
    <div data-checkout-section="company">
        <?= $activeForm->field($form, 'company')->textInput(['autocomplete' => 'organization']) ?>
        <div class="row">
            <?php foreach ($fields as $field => $label): ?>
                <?php if ($field !== 'company'): ?>
                    <div class="col-md-4"><?= $activeForm->field($form, $field)->textInput() ?></div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
