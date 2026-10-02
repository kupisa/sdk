<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\Page $page */

use frontend\widgets\PageContent;

$this->title = $page->title;
?>

<div class="page-view">
    <?= PageContent::widget(['page' => $page]) ?>
</div>
