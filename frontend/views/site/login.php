<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\LoginForm $model */

use common\helpers\Signup;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login container py-5">
    <div class="form-card">
        <h1 class="h2"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted"><?= Yii::t('app', 'Enter your email address and password to continue.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'autofocus' => true, 'autocomplete' => 'email']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'current-password']) ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary w-100', 'name' => 'login-button']) ?>
        <?php ActiveForm::end() ?>

        <p class="small text-muted text-center mt-4 mb-0">
            <?= Html::a(Yii::t('app', 'Forgot your password?'), ['site/request-password-reset']) ?>
            <?php if (Signup::isEnabled() && Signup::activation() === Signup::ACTIVATION_EMAIL): ?>
                &middot;
                <?= Html::a(Yii::t('app', 'Resend verification email'), ['site/resend-verification-email']) ?>
            <?php endif ?>
        </p>
        <?php if (Signup::isEnabled()): ?>
            <p class="small text-muted text-center mt-2 mb-0">
                <?= Yii::t('app', 'Don’t have an account?') ?> <?= Html::a(Yii::t('app', 'Create one'), ['site/signup']) ?>
            </p>
        <?php endif ?>
    </div>
</div>
