<?php

/**
 * The layout of a page that keeps the visitor on what they are doing, like the checkout of a shop: the admin
 * bar, the header focused (the brand and the actions meant for it only, no navigation; see
 * blocks/header/views/header.php and `frontend\widgets\headeractions\HeaderActions`), the flash messages and
 * the content, without the footer and the breadcrumbs, the content growing to the foot of the window
 * (`main-fill`). What a module puts on every page (the cookie banner) stays. A controller picks it with
 * `$this->layout = 'focused'` (`'//focused'` from a module).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use frontend\widgets\AdminBar;
use frontend\widgets\Alert;
use frontend\widgets\AreaContent;
use yii\helpers\Html;

$this->params['focused'] = true;
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

<main id="main" class="main-fill">
    <?php $alerts = Alert::widget() ?>
    <?php if ($alerts !== ''): ?>
        <div class="container"><?= $alerts ?></div>
    <?php endif ?>
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
