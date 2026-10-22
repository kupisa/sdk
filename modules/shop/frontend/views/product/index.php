<?php

/**
 * A list of products on the site: all of them under /shop, or those of a category, headed by its name and its
 * description. One page of the products as cards in a grid, with the way to the other pages below it; above
 * them which of the products are shown and the sorting, then the filters chosen as chips (see
 * `_active-filters.php`); next to them, on a wide screen to their left and on a phone above them, the
 * categories a visitor would find a product in, one level of them (see `_categories.php`), and the filters of
 * the list (see `_filters.php`). A template a theme replaces: everything shown comes from the controller (see
 * ProductController::renderList()).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title */
/** @var string $description The description of the category or the tag the list is of; empty for none. */
/** @var modules\shop\common\models\Product[] $products The products of this page. */
/** @var yii\data\Pagination $pagination */
/** @var int $total How many products the list has in all. */
/** @var int $from The place of the first product of this page in the list, from 1; 0 for none. */
/** @var int $to The place of the last product of this page. */
/** @var modules\shop\common\models\Category[] $categories The categories to offer next to the list, one level of them. */
/** @var modules\shop\frontend\models\ProductFilter $filter */
/** @var list<array> $facets The attributes to filter by with their values, see `ProductFilter::facets()`. */
/** @var string|null $priceMin The lowest price of the list. */
/** @var string|null $priceMax The highest price of the list. */
/** @var array<string, string> $sortOptions */
/** @var list<array{label: string, url: string}> $chips The filters chosen, see `ProductFilter::chips()`. */
/** @var string $resetUrl The list without any filter. */
/** @var string $formUrl Where the form of the filters is sent. */

use modules\shop\frontend\assets\ShopAsset;
use modules\shop\frontend\models\ProductFilter;
use frontend\widgets\Pager;
use yii\helpers\Html;

ShopAsset::register($this);
?>
<div class="shop-index container mt-4 mb-5">
    <header class="mb-4">
        <h1><?= Html::encode($title) ?></h1>
        <?php if ($description !== ''): ?>
            <p class="lead"><?= nl2br(Html::encode($description)) ?></p>
        <?php endif ?>
    </header>

    <div class="shop-index-layout">
        <aside class="shop-sidebar">
            <?= $this->render('_categories', ['categories' => $categories]) ?>

            <?= $this->render('_filters', [
                'filter' => $filter,
                'facets' => $facets,
                'priceMin' => $priceMin,
                'priceMax' => $priceMax,
                'formUrl' => $formUrl,
            ]) ?>
        </aside>

        <div class="shop-index-products">
            <div class="shop-index-toolbar">
                <?php if ($total > 0): ?>
                    <p class="shop-index-count mb-0"><?= Yii::t('shop', 'Showing {from}–{to} of {total, plural, one{# product} other{# products}}', ['from' => $from, 'to' => $to, 'total' => $total]) ?></p>
                <?php endif ?>
                <div class="shop-index-sort">
                    <label for="shop-sort"><?= Yii::t('shop', 'Sort by') ?></label>
                    <?= Html::dropDownList('sort', $filter->getSort(), $sortOptions, [
                        'id' => 'shop-sort',
                        'class' => 'form-control',
                        // The sorting goes with the form of the filters, which stands elsewhere on the page.
                        'form' => 'shop-filters',
                        // The script leaves the default out of the address.
                        'data-default' => ProductFilter::SORT_NEWEST,
                    ]) ?>
                </div>
            </div>

            <?= $this->render('_active-filters', ['chips' => $chips, 'resetUrl' => $resetUrl]) ?>

            <?php if ($products === [] && $filter->isActive()): ?>
                <p class="shop-empty">
                    <?= Yii::t('shop', 'No products match the chosen filters.') ?>
                    <?= Html::a(Yii::t('shop', 'Clear filters'), $resetUrl) ?>
                </p>
            <?php elseif ($products === []): ?>
                <p class="shop-empty"><?= Yii::t('shop', 'There are no products yet.') ?></p>
            <?php else: ?>
                <div class="shop-grid">
                    <?php foreach ($products as $product): ?>
                        <?= $this->render('_card', ['product' => $product]) ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <div class="shop-index-pager">
                <?= Pager::widget(['pagination' => $pagination]) ?>
            </div>
        </div>
    </div>
</div>
