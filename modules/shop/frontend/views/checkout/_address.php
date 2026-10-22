<?php

/**
 * The fields of an address on the checkout: the delivery address (no prefix) or the billing address (`billing_`).
 * The country comes first, so what follows can depend on it.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $activeForm */
/** @var modules\shop\frontend\models\CheckoutForm $form */
/** @var string $prefix '' or 'billing_'. */
/** @var array<string, string> $countries The countries the address may be in, their names by code. */

use frontend\widgets\SearchableSelect;

?>
<?= $activeForm->field($form, $prefix . 'country')->widget(SearchableSelect::class, [
    'items'   => $countries,
    'options' => ['prompt' => '', 'autocomplete' => 'country'],
]) ?>
<div class="row">
    <div class="col-md-6"><?= $activeForm->field($form, $prefix . 'first_name')->textInput(['autocomplete' => 'given-name']) ?></div>
    <div class="col-md-6"><?= $activeForm->field($form, $prefix . 'last_name')->textInput(['autocomplete' => 'family-name']) ?></div>
</div>
<?= $activeForm->field($form, $prefix . 'street')->textInput(['autocomplete' => 'street-address']) ?>
<div class="row">
    <div class="col-md-4"><?= $activeForm->field($form, $prefix . 'postal_code')->textInput(['autocomplete' => 'postal-code']) ?></div>
    <div class="col-md-8"><?= $activeForm->field($form, $prefix . 'city')->textInput(['autocomplete' => 'address-level2']) ?></div>
</div>
