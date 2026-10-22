<?php

/**
 * The fields of an address of the customer on their account page (see AddressForm): the country first, then
 * the name, the street, the postal code and the city.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $activeForm */
/** @var modules\shop\common\models\AddressForm $address */

use common\helpers\Countries;
use frontend\widgets\SearchableSelect;

?>
<?= $activeForm->field($address, 'country')->widget(SearchableSelect::class, [
    'items'   => Countries::all(),
    'options' => ['prompt' => '', 'autocomplete' => 'country'],
]) ?>
<div class="row">
    <div class="col-md-6"><?= $activeForm->field($address, 'first_name')->textInput(['autocomplete' => 'given-name']) ?></div>
    <div class="col-md-6"><?= $activeForm->field($address, 'last_name')->textInput(['autocomplete' => 'family-name']) ?></div>
</div>
<?= $activeForm->field($address, 'street')->textInput(['autocomplete' => 'street-address']) ?>
<div class="row">
    <div class="col-md-4"><?= $activeForm->field($address, 'postal_code')->textInput(['autocomplete' => 'postal-code']) ?></div>
    <div class="col-md-8"><?= $activeForm->field($address, 'city')->textInput(['autocomplete' => 'address-level2']) ?></div>
</div>
