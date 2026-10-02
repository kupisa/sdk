<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title, plain. */
/** @var string $text The text under the title, plain, with line breaks, or '' for none. */
/** @var string $button The button as an anchor, or '' when it is not filled in. */

use yii\helpers\Html;

?>
<div class="container block-cta-inner">
    <div class="block-cta-content">
        <h2 data-value="title"><?= Html::encode($title) ?></h2>
        <p class="lead" data-value="text"><?= nl2br(Html::encode($text)) ?></p>
    </div>
    <?php if ($button !== ''): ?>
        <div class="block-cta-actions"><?= $button ?></div>
    <?php endif ?>
</div>
