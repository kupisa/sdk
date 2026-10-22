<?php

/**
 * The pictures of a way of payment on the checkout, right before its price: the first few (the brands of the
 * cards it takes) and the number of the others, named in its tooltip. Nothing for a way without pictures.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var array{logos: list<array{label: string, svg: string}>, moreLogos: string, moreLogosTitle: string} $method */

use yii\helpers\Html;

?>
<?php if ($method['logos'] !== []): ?>
    <span class="shop-checkout-method-logos">
        <?php foreach ($method['logos'] as $logo): ?>
            <span class="shop-checkout-method-logo" title="<?= Html::encode($logo['label']) ?>" role="img" aria-label="<?= Html::encode($logo['label']) ?>"><?= $logo['svg'] ?></span>
        <?php endforeach ?>
        <?php if ($method['moreLogos'] !== ''): ?>
            <span class="shop-checkout-method-more" title="<?= Html::encode($method['moreLogosTitle']) ?>"><?= Html::encode($method['moreLogos']) ?></span>
        <?php endif ?>
    </span>
<?php endif ?>
