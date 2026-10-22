<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the pictures, or '' for none. */
/** @var string $columnClass The column classes of one picture, e.g. `col-6 col-md-4`. */
/** @var list<array{image: common\models\Media, caption: string}> $items */

use frontend\widgets\lightbox\Lightbox;
use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <?php Lightbox::begin(['options' => ['class' => 'row block-gallery-grid']]) ?>
        <?php foreach ($items as $item): ?>
            <figure class="<?= $columnClass ?> block-gallery-item">
                <a href="<?= Html::encode($item['image']->url) ?>" data-lightbox-item data-lightbox-open>
                    <?= $item['image']->img(['alt' => $item['image']->alt ?? $item['caption']]) ?>
                </a>
                <?php if ($item['caption'] !== ''): ?>
                    <figcaption><?= Html::encode($item['caption']) ?></figcaption>
                <?php endif ?>
            </figure>
        <?php endforeach ?>
    <?php Lightbox::end() ?>
</div>
