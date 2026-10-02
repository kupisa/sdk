<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the services, or '' for none. */
/** @var string $text The text under the title, plain, with line breaks, or '' for none. */
/** @var list<array{title: string, text: string, price: string}> $items */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== '' || $text !== ''): ?>
        <div class="block-services-intro">
            <?php if ($title !== ''): ?>
                <h2><?= Html::encode($title) ?></h2>
            <?php endif ?>
            <?php if ($text !== ''): ?>
                <p class="lead"><?= nl2br(Html::encode($text)) ?></p>
            <?php endif ?>
        </div>
    <?php endif ?>
    <ul class="block-services-list row">
        <?php foreach ($items as $item): ?>
            <li class="col-md-6 col-lg-4 block-services-item">
                <h3 class="h4"><?= Html::encode($item['title']) ?></h3>
                <?php if ($item['text'] !== ''): ?>
                    <p><?= nl2br(Html::encode($item['text'])) ?></p>
                <?php endif ?>
                <?php if ($item['price'] !== ''): ?>
                    <span class="block-services-price"><?= Html::encode($item['price']) ?></span>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>
