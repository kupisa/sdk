<?php

/**
 * The layout of one block on its own: the stylesheet of the site and its theme, and the content in the middle
 * of the window, without the header, the footer and the admin bar. The preview of a block in the page editor
 * of the administration is rendered with it (see `controllers\PageController::actionBlockPreview()`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;

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

<main id="main" class="block-preview">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
