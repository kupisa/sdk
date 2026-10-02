<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var frontend\models\ResendVerificationEmailForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Resend verification email');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email container py-5">
    <div class="form-card">
        <h1 class="h2"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted"><?= Yii::t('app', 'Enter your email to receive a new verification link.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']) ?>
        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'autofocus' => true, 'autocomplete' => 'email']) ?>
        <?= Html::submitButton(Yii::t('app', 'Send the link'), ['class' => 'btn btn-primary w-100', 'name' => 'resend-verification-email-button']) ?>
        <?php ActiveForm::end() ?>

        <p class="small text-muted text-center mt-4 mb-0">
            <?= Yii::t('app', 'Already verified?') ?> <?= Html::a(Yii::t('app', 'Login'), ['site/login']) ?>
        </p>
    </div>
</div>
