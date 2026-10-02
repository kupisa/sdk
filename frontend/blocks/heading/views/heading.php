<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $level The element of the heading, `h1` to `h6`. */
/** @var string $align Where the text sits: `start` or `center`. */
/** @var string $text The text of the heading, plain. */

use yii\helpers\Html;

?>
<div class="container">
    <?= Html::tag($level, Html::encode($text), [
        'class' => $align === 'start' ? null : "text-$align",
        'data-value' => 'text',
    ]) ?>
</div>
