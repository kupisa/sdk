<?php

/**
 * The search box of the site, with the icon of the search in it, and the button that searches; under them the
 * terms the site suggests (see `frontend\models\Search::terms()`), each a search of its own. The dialog of the
 * header and the page of results render it.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $query What is in the box. */
/** @var string|null $suggest The address of the suggestions for the box of the dialog, or null on the page of results. */
/** @var string $after HTML placed at the end of the row, e.g. the button that closes the dialog. */

use frontend\models\Search;
use yii\helpers\Html;
use yii\helpers\Url;

$terms = Search::terms();
?>
<form class="site-search-form" method="get" action="<?= Html::encode(Url::to(['/search/index'])) ?>" role="search">
    <div class="site-search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <?= Html::input('search', 'q', $query, [
            'class' => 'form-control',
            'placeholder' => Yii::t('app', 'What can we help you find?'),
            'aria-label' => Yii::t('app', 'Search'),
            'autocomplete' => 'off',
            'data-search-suggest' => $suggest,
        ]) ?>
    </div>
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    <?= $after ?>
</form>
<?php if ($terms !== []): ?>
    <div class="site-search-terms">
        <span class="site-search-terms-label"><?= Yii::t('app', 'Frequently searched') ?></span>
        <?php foreach ($terms as $term): ?>
            <?= Html::a(Html::encode($term), ['/search/index', 'q' => $term], ['class' => 'badge']) ?>
        <?php endforeach ?>
    </div>
<?php endif ?>
