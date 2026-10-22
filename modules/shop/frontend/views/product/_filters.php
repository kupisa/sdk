<?php

/**
 * The filters of a list of products, as one form sent with GET, so the address of the list carries them: a group
 * per attribute the products come in, its values as checkboxes with the number of products each would give (a
 * value that would give none is greyed out), each with its swatch, the colour or the picture of the value, when it
 * has one, the first six shown and the rest behind "View more" (`_filter-value.php` renders one); "only
 * available"; and the price from and to, sent with the "Apply" button. Every group is folded unless something of
 * it is chosen. The sorting stands above the products (see `index.php`) and goes with the form by its id. The
 * script of the shop sends the form as soon as a checkbox or the sorting changes (see `public/js/shop.js`). On a
 * phone the form folds behind the "Filters" summary and opens as a panel, on a wide screen it stands open in the
 * column next to the products; without the script it stands open everywhere.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\frontend\models\ProductFilter $filter */
/** @var list<array{attribute: modules\shop\common\models\Attribute, values: list<array>, more: list<array>, moreOpen: bool, open: bool}> $facets See `ProductFilter::facets()`. */
/** @var string|null $priceMin The lowest price of the list, what the empty field says. */
/** @var string|null $priceMax The highest price of the list. */
/** @var string $formUrl */

use yii\helpers\Html;
?>
<details class="shop-filters-details" open data-shop-filters-details>
    <summary class="btn btn-outline-secondary shop-filters-toggle">
        <?= Yii::t('shop', 'Filters') ?>
        <?php if ($filter->count() > 0): ?>
            <span class="badge active"><?= $filter->count() ?></span>
        <?php endif ?>
    </summary>

    <form id="shop-filters" class="shop-filters" method="get" action="<?= Html::encode($formUrl) ?>" data-shop-filters>
        <div class="shop-filters-head">
            <span class="shop-filters-title"><?= Yii::t('shop', 'Filters') ?></span>
            <button type="button" class="shop-filters-close" data-shop-filters-close aria-label="<?= Yii::t('shop', 'Close') ?>">&times;</button>
        </div>

        <?php foreach ($facets as $facet): ?>
            <details class="shop-filter shop-filter-attribute"<?= $facet['open'] ? ' open' : '' ?>>
                <summary><?= Html::encode($facet['attribute']->name) ?></summary>
                <div class="shop-filter-values">
                    <?php foreach ($facet['values'] as $item): ?>
                        <?= $this->render('_filter-value', ['name' => $facet['attribute']->slug . '[]', 'item' => $item]) ?>
                    <?php endforeach ?>
                    <?php if ($facet['more'] !== []): ?>
                        <details class="shop-filter-more"<?= $facet['moreOpen'] ? ' open' : '' ?>>
                            <summary>
                                <span class="shop-filter-more-open"><?= Yii::t('shop', 'View more ({count})', ['count' => count($facet['more'])]) ?></span>
                                <span class="shop-filter-more-close"><?= Yii::t('shop', 'View less') ?></span>
                            </summary>
                            <?php foreach ($facet['more'] as $item): ?>
                                <?= $this->render('_filter-value', ['name' => $facet['attribute']->slug . '[]', 'item' => $item]) ?>
                            <?php endforeach ?>
                        </details>
                    <?php endif ?>
                </div>
            </details>
        <?php endforeach ?>

        <details class="shop-filter shop-filter-availability"<?= $filter->isAvailableOnly() ? ' open' : '' ?>>
            <summary><?= Yii::t('shop', 'Availability') ?></summary>
            <div class="shop-filter-values">
                <label class="shop-filter-value">
                    <?= Html::checkbox('available', $filter->isAvailableOnly(), ['value' => '1']) ?>
                    <span class="shop-filter-name"><?= Yii::t('shop', 'Only available') ?></span>
                </label>
            </div>
        </details>

        <details class="shop-filter shop-filter-price"<?= $filter->hasPrice() ? ' open' : '' ?>>
            <summary><?= Yii::t('shop', 'Price') ?></summary>
            <div class="shop-filter-price-fields">
                <?= Html::input('number', 'price_min', $filter->getPriceMin(), [
                    'class' => 'form-control',
                    'placeholder' => $priceMin,
                    'min' => 0,
                    'step' => 'any',
                    'aria-label' => Yii::t('shop', 'From'),
                ]) ?>
                <span>–</span>
                <?= Html::input('number', 'price_max', $filter->getPriceMax(), [
                    'class' => 'form-control',
                    'placeholder' => $priceMax,
                    'min' => 0,
                    'step' => 'any',
                    'aria-label' => Yii::t('shop', 'To'),
                ]) ?>
                <button type="submit" class="btn btn-outline-secondary"><?= Yii::t('shop', 'Apply') ?></button>
            </div>
        </details>
    </form>
</details>
