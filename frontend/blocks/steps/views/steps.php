<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title above the steps, or '' for none. */
/** @var list<array{title: string, text: string}> $items The steps in order. */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <ol class="block-steps-list">
        <?php foreach ($items as $item): ?>
            <li class="block-steps-item">
                <?php if ($item['title'] !== ''): ?>
                    <h3 class="h4"><?= Html::encode($item['title']) ?></h3>
                <?php endif ?>
                <?php if ($item['text'] !== ''): ?>
                    <p><?= nl2br(Html::encode($item['text'])) ?></p>
                <?php endif ?>
            </li>
        <?php endforeach ?>
    </ol>
</div>
