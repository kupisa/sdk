<?php

/**
 * The home page of a site that has not chosen a page as its home page yet (setting `home_page`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index container py-5 text-center">
    <h1><?= Html::encode(Yii::$app->name) ?></h1>
    <p class="lead text-muted"><?= Yii::t('app', 'This site is being set up. Come back soon.') ?></p>
</div>
