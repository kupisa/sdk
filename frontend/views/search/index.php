<?php

/**
 * The page of results of the search: the search box with what was typed and the terms the site suggests (see
 * _box.php), then the pages found, each with its title, its kind and a piece of its text; or that nothing was
 * found.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $query What was looked for, trimmed. */
/** @var list<array{title: string, url: string, kind: string, excerpt: string}> $results */

use yii\helpers\Html;

$this->title = $query === '' ? Yii::t('app', 'Search') : Yii::t('app', 'Search: {query}', ['query' => $query]);
?>
<div class="site-search-page container w-max-800 py-5">
    <h1><?= Yii::t('app', 'Search') ?></h1>

    <?= $this->render('_box', ['query' => $query, 'suggest' => null, 'after' => '']) ?>

    <?php if ($query !== ''): ?>
        <?php if ($results === []): ?>
            <p class="site-search-empty"><?= Yii::t('app', 'Nothing found for “{query}”.', ['query' => Html::encode($query)]) ?></p>
        <?php else: ?>
            <p class="site-search-count"><?= Yii::t('app', '{n, plural, =1{1 result} other{# results}} for “{query}”', ['n' => count($results), 'query' => Html::encode($query)]) ?></p>
            <ul class="site-search-results">
                <?php foreach ($results as $result): ?>
                    <li class="site-search-result">
                        <?= Html::a(Html::encode($result['title']), $result['url'], ['class' => 'site-search-title']) ?>
                        <span class="site-search-kind"><?= Html::encode($result['kind']) ?></span>
                        <?php if ($result['excerpt'] !== ''): ?>
                            <p class="site-search-excerpt"><?= Html::encode($result['excerpt']) ?></p>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    <?php endif ?>
</div>
