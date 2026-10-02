<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var frontend\models\ResetPasswordForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Set your new password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password container py-5">
    <div class="form-card">
        <h1 class="h2"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted"><?= Yii::t('app', 'Choose a new password for your account.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'autocomplete' => 'new-password']) ?>
        <?= Html::submitButton(Yii::t('app', 'Save password'), ['class' => 'btn btn-primary w-100', 'name' => 'reset-password-button']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
