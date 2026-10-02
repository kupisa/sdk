<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use frontend\widgets\AdminBar;
use frontend\widgets\Alert;
use frontend\widgets\AreaContent;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->render('_head');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body>
<?php $this->beginBody() ?>

<?= AdminBar::widget() ?>
<?= AreaContent::widget(['name' => 'header']) ?>

<main id="main">
    <?php if (!empty($this->params['breadcrumbs'])): ?>
        <div class="container">
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        </div>
    <?php endif ?>
    <?php $alerts = Alert::widget() ?>
    <?php if ($alerts !== ''): ?>
        <div class="container"><?= $alerts ?></div>
    <?php endif ?>
    <?= $content ?>
</main>

<?= AreaContent::widget(['name' => 'footer']) ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
