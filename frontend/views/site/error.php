<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\web\HttpException;

$this->title = $name;
$statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;
?>
<div class="site-error container py-5 text-center">
    <h1 class="display text-muted mb-0"><?= Html::encode((string) $statusCode) ?></h1>
    <h2><?= Html::encode($message) ?></h2>
    <p class="text-muted mb-4">
        <?= Yii::t('app', 'The above error occurred while the Web server was processing your request. Please contact us if you think this is a server error. Thank you.') ?>
    </p>
    <?= Html::a(Yii::t('app', 'Go to Homepage'), Yii::$app->homeUrl, ['class' => 'btn btn-outline-primary']) ?>
</div>
