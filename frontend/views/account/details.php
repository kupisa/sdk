<?php

/**
 * The account details of a logged-in user: the name and the email address in one form (a new address waits
 * under the field until the link sent to it is opened), the password in another (the current one asked for
 * only from a user who has one).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var frontend\models\AccountDetailsForm $details */
/** @var frontend\models\ChangePasswordForm $password */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Account details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My account'), 'url' => ['/account/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="account-section">
    <h2 class="account-section-title"><?= Yii::t('app', 'Account details') ?></h2>
    <p class="text-muted"><?= Yii::t('app', 'Your name and the email address you log in with.') ?></p>

    <?php $form = ActiveForm::begin(['id' => 'account-details-form']) ?>
    <?= $form->field($details, 'name')->textInput(['autocomplete' => 'name']) ?>
    <?= $form->field($details, 'email')->textInput(['type' => 'email', 'autocomplete' => 'email']) ?>
    <?php if ($details->pendingEmail() !== null): ?>
        <p class="account-pending-email text-muted"><?= Yii::t('app', 'Waiting for you to confirm {email}: we sent a link there. Save your current address to keep it.', ['email' => Html::encode($details->pendingEmail())]) ?></p>
    <?php endif ?>
    <?= Html::submitButton(Yii::t('app', 'Save details'), ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>
</section>

<section class="account-section">
    <h2 class="account-section-title"><?= Yii::t('app', 'Password') ?></h2>
    <?php if ($password->asksCurrentPassword()): ?>
        <p class="text-muted"><?= Yii::t('app', 'Choose a new password; the current one proves it is you.') ?></p>
    <?php else: ?>
        <p class="text-muted"><?= Yii::t('app', 'Your account has no password yet. Choose one to log in with next time.') ?></p>
    <?php endif ?>

    <?php $form = ActiveForm::begin(['id' => 'account-password-form']) ?>
    <?php if ($password->asksCurrentPassword()): ?>
        <?= $form->field($password, 'current_password')->passwordInput(['autocomplete' => 'current-password']) ?>
    <?php endif ?>
    <?= $form->field($password, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
    <?= Html::submitButton(Yii::t('app', 'Save password'), ['class' => 'btn btn-primary']) ?>
    <?php ActiveForm::end() ?>
</section>
