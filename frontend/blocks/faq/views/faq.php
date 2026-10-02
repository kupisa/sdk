<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $group The group the questions share: one of them is open at a time (see js/src/site.js). */
/** @var string $title The title above the questions, or '' for none. */
/** @var list<array{question: string, answer: string}> $items */

use yii\helpers\Html;

?>
<div class="container">
    <?php if ($title !== ''): ?>
        <h2><?= Html::encode($title) ?></h2>
    <?php endif ?>
    <div class="block-faq-list">
        <?php foreach ($items as $item): ?>
            <details class="block-faq-item" data-accordion="<?= Html::encode($group) ?>">
                <summary><?= Html::encode($item['question']) ?></summary>
                <div class="block-faq-answer"><p><?= nl2br(Html::encode($item['answer'])) ?></p></div>
            </details>
        <?php endforeach ?>
    </div>
</div>
