<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the pictures, or '' for none. */
/** @var string $columnClass The column classes of one picture, e.g. `col-6 col-md-4`. */
/** @var list<array{image: common\models\Media, caption: string}> $items */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <div class="row block-gallery-grid">
        <?php foreach ($items as $item): ?>
            <figure class="<?= $columnClass ?> block-gallery-item">
                <a href="<?= Html::encode($item['image']->url) ?>" target="_blank" rel="noopener">
                    <?= $item['image']->img(['alt' => $item['image']->alt ?? $item['caption']]) ?>
                </a>
                <?php if ($item['caption'] !== ''): ?>
                    <figcaption><?= Html::encode($item['caption']) ?></figcaption>
                <?php endif ?>
            </figure>
        <?php endforeach ?>
    </div>
</div>
