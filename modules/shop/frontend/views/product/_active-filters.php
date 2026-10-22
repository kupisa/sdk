<?php

/**
 * The filters a visitor chose, above the products: a chip per value, for the price and for "only available",
 * each a link to the list without it, and the way to clear them all. Nothing while no filter is chosen.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var list<array{label: string, url: string}> $chips */
/** @var string $resetUrl */

use yii\helpers\Html;
?>
<?php if ($chips !== []): ?>
    <div class="shop-filter-chips">
        <?php foreach ($chips as $chip): ?>
            <a class="shop-filter-chip" href="<?= Html::encode($chip['url']) ?>" title="<?= Yii::t('shop', 'Remove') ?>">
                <?= Html::encode($chip['label']) ?>
                <span aria-hidden="true">&times;</span>
            </a>
        <?php endforeach ?>
        <a class="shop-filter-reset" href="<?= Html::encode($resetUrl) ?>"><?= Yii::t('shop', 'Clear filters') ?></a>
    </div>
<?php endif ?>
