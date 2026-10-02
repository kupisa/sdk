<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\Page $page A page of nothing but the one block, with its presets. */

use frontend\widgets\PageContent;

?>
<div class="page-view">
    <?= PageContent::widget(['page' => $page]) ?>
</div>
