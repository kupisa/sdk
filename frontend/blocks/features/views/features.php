<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the items, or '' for none. */
/** @var string $columnClass The column class of one item, e.g. `col-md-4`. */
/** @var list<array{image: common\models\Media|null, title: string, text: string}> $items */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <div class="row">
        <?php foreach ($items as $item): ?>
            <div class="<?= $columnClass ?> block-features-item">
                <?php if ($item['image'] !== null): ?>
                    <?= $item['image']->img() ?>
                <?php endif ?>
                <?php if ($item['title'] !== ''): ?>
                    <h3 class="h4"><?= Html::encode($item['title']) ?></h3>
                <?php endif ?>
                <?php if ($item['text'] !== ''): ?>
                    <p><?= nl2br(Html::encode($item['text'])) ?></p>
                <?php endif ?>
            </div>
        <?php endforeach ?>
    </div>
</div>
