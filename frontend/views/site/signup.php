<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var frontend\models\SignupForm $model */

use frontend\models\SignupForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Create a new account');
$this->params['breadcrumbs'][] = $this->title;

// The pages the user accepts by signing up, when the site has them, each linked in the label of the checkbox.
$policies = SignupForm::policies();
$labels = ['terms' => Yii::t('app', 'terms of use'), 'privacy' => Yii::t('app', 'privacy policy')];
$links = [];
foreach ($policies as $purpose => $page) {
    $links[$purpose] = Html::a($labels[$purpose], $page->getUrl(), ['target' => '_blank']);
}
$acceptLabel = match (array_keys($policies)) {
    ['terms', 'privacy'] => Yii::t('app', 'I accept the {terms} and the {privacy}.', $links),
    ['terms'] => Yii::t('app', 'I accept the {terms}.', $links),
    ['privacy'] => Yii::t('app', 'I accept the {privacy}.', $links),
    default => '',
};
?>
<div class="site-signup container py-5">
    <div class="form-card">
        <h1 class="h2"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted"><?= Yii::t('app', 'Fill out the fields below to get started.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']) ?>
        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'autofocus' => true, 'autocomplete' => 'email']) ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
        <?php if ($policies !== []): ?>
            <?= $form->field($model, 'accept', ['options' => ['class' => 'form-group checkbox']])->checkbox(['label' => $acceptLabel]) ?>
        <?php endif ?>
        <?= Html::submitButton(Yii::t('app', 'Create account'), ['class' => 'btn btn-primary w-100', 'name' => 'signup-button']) ?>
        <?php ActiveForm::end() ?>

        <p class="small text-muted text-center mt-4 mb-0">
            <?= Yii::t('app', 'Already have an account?') ?> <?= Html::a(Yii::t('app', 'Login'), ['site/login']) ?>
        </p>
    </div>
</div>
