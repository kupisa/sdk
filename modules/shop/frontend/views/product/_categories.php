<?php

/**
 * The categories next to a list of products, one level of them: every category linking to its products. The
 * whole catalogue offers the ones at the top, a category the ones right below it; a category without any below it
 * shows none. The view decides that itself, so a theme that replaces it shows its own list on every page of the
 * catalogue (the whole tree of the categories, say).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Category[] $categories */

use yii\helpers\Html;
?>
<?php if ($categories !== []): ?>
    <nav class="shop-categories" aria-label="<?= Yii::t('shop', 'Categories') ?>">
        <ul class="nav">
            <?php foreach ($categories as $category): ?>
                <li><?= Html::a(Html::encode($category->name), $category->getUrl()) ?></li>
            <?php endforeach ?>
        </ul>
    </nav>
<?php endif ?>
