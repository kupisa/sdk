<?php

/**
 * What the block shows in the page editor while it has no product to show: none picked, a category without
 * published products, no product on sale or sold yet.
 */

declare(strict_types=1);

/** @var yii\web\View $this */

?>
<div class="container">
    <p class="shop-products-note"><?= Yii::t('shop', 'The products will stand here once there are some to show. Pick them or choose where they come from on the left.') ?></p>
</div>
