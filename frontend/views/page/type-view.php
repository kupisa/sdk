<?php

/**
 * A page of a kind of page of the theme, built from its blocks like a plain page: what a kind shows while its
 * theme has no `views/<type>/view.php` of its own (see `themes\Theme::pageTypes()`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\Page $page */

use frontend\widgets\PageContent;
?>
<div class="page-view">
    <?= PageContent::widget(['page' => $page]) ?>
</div>
