<?php

/**
 * The section of the invoice on the checkout: the company the customer orders as (see `_company.php`) and the
 * billing address, as Settings › Shop asks for them. Asked for first, the billing address is always there;
 * after the delivery address, behind "Same as the delivery address".
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $activeForm */
/** @var modules\shop\frontend\models\CheckoutForm $form */
/** @var string $title The title of the section. */
/** @var bool $billingFirst Whether the billing address is asked for first, ahead of the delivery address. */
/** @var bool $offersBilling Whether the checkout asks for a billing address. */
/** @var string $company Whether the checkout asks for a company, one of the `Module::COMPANY_*` constants. */
/** @var array<string, string> $companyFields The fields of the company asked for, their label by name. */
/** @var string $companyVersion What the fields of the company are, as a fingerprint. */
/** @var array<string, string> $countries The countries of the billing address, their names by code. */

use modules\shop\Module;
use yii\helpers\Html;

?>
<section class="shop-checkout-section">
    <h2 class="h4"><?= Html::encode($title) ?></h2>
    <?php if ($company !== Module::COMPANY_DISABLED): ?>
        <?= $this->render('_company', [
            'activeForm'   => $activeForm,
            'form'         => $form,
            'optional'     => $company === Module::COMPANY_OPTIONAL,
            'opensBilling' => !$billingFirst && $offersBilling,
            'fields'       => $companyFields,
            'version'      => $companyVersion,
        ]) ?>
    <?php endif ?>
    <?php if ($billingFirst): ?>
        <?= $this->render('_address', ['activeForm' => $activeForm, 'form' => $form, 'prefix' => 'billing_', 'countries' => $countries]) ?>
    <?php elseif ($offersBilling): ?>
        <?= $activeForm->field($form, 'billing_same', ['options' => ['class' => 'form-group checkbox']])->checkbox([
            'data-checkout-toggle'      => 'billing',
            'data-checkout-toggle-when' => 'unchecked',
        ]) ?>
        <div data-checkout-section="billing">
            <?= $this->render('_address', ['activeForm' => $activeForm, 'form' => $form, 'prefix' => 'billing_', 'countries' => $countries]) ?>
        </div>
    <?php endif ?>
</section>
