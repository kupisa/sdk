<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the quotes, or '' for none. */
/** @var list<array{quote: string, name: string, detail: string}> $items */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <div class="row">
        <?php foreach ($items as $item): ?>
            <div class="col-md-6 col-lg-4">
                <figure class="block-testimonials-item">
                    <blockquote><?= nl2br(Html::encode($item['quote'])) ?></blockquote>
                    <?php if ($item['name'] !== '' || $item['detail'] !== ''): ?>
                        <figcaption>
                            <?php if ($item['name'] !== ''): ?>
                                <strong><?= Html::encode($item['name']) ?></strong>
                            <?php endif ?>
                            <?php if ($item['detail'] !== ''): ?>
                                <span><?= Html::encode($item['detail']) ?></span>
                            <?php endif ?>
                        </figcaption>
                    <?php endif ?>
                </figure>
            </div>
        <?php endforeach ?>
    </div>
</div>
