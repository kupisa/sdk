<?php

/**
 * The addresses of the customer in their account, what the checkout starts from: the delivery address with the
 * phone, and the billing address behind "Same as the delivery address" (its fields fold away while ticked, with
 * no script; see `.shop-billing` in public/css/shop.css). The form is checked by the server alone: an address
 * may be left empty and the billing address counts only while the box is unticked, which the rules of each
 * address do not know, so their checks in the browser would stop the form.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\frontend\models\CustomerAddressesForm $form */

use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

ShopAsset::register($this);
?>
<?php $activeForm = ActiveForm::begin([
    'id'                     => 'shop-addresses-form',
    'enableClientValidation' => false,
]) ?>
<section class="account-section">
    <h2 class="account-section-title"><?= Yii::t('shop', 'Delivery address') ?></h2>
    <p class="text-muted"><?= Yii::t('shop', 'Where your orders go and the phone the courier calls; the checkout starts from it.') ?></p>

    <?= $activeForm->field($form, 'phone')->textInput(['type' => 'tel', 'autocomplete' => 'tel']) ?>
    <?= $this->render('_address', ['activeForm' => $activeForm, 'address' => $form->shipping]) ?>
</section>

<section class="account-section shop-billing">
    <h2 class="account-section-title"><?= Yii::t('shop', 'Billing address') ?></h2>
    <p class="text-muted"><?= Yii::t('shop', 'Who is invoiced, when other than the delivery address.') ?></p>

    <?= $activeForm->field($form, 'billing_same')->checkbox() ?>
    <div class="shop-billing-fields">
        <?= $this->render('_address', ['activeForm' => $activeForm, 'address' => $form->billing]) ?>
    </div>

    <?= Html::submitButton(Yii::t('shop', 'Save addresses'), ['class' => 'btn btn-primary']) ?>
</section>
<?php ActiveForm::end() ?>
