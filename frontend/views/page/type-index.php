<?php

/**
 * The list of the pages of a kind of page of the theme, in its order, with the way to the other pages of the
 * list below it: all of them, or those in a term of a taxonomy of the theme, headed by its description when it has
 * one. What a kind shows while its theme has no `views/<type>/index.php` of its own (see
 * `themes\Theme::pageTypes()`).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\Page[] $pages The pages of this page of the list. */
/** @var yii\data\Pagination $pagination */
/** @var common\models\Term|null $term The term the list is of, or null for all the pages of the kind. */

use frontend\widgets\Pager;
use yii\helpers\Html;
?>
<div class="page-type-index container mt-4 mb-5">
    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <?php if ($term !== null && $term->description !== null && $term->description !== ''): ?>
        <p class="mb-4"><?= nl2br(Html::encode($term->description)) ?></p>
    <?php endif ?>

    <?php if ($pages === []): ?>
        <p><?= Yii::t('app', 'There is nothing here yet.') ?></p>
    <?php endif ?>

    <?php foreach ($pages as $page): ?>
        <article class="mb-4">
            <h2 class="h4"><?= Html::a(Html::encode($page->title), $page->getUrl()) ?></h2>
        </article>
    <?php endforeach ?>

    <?= Pager::widget(['pagination' => $pagination]) ?>
</div>
