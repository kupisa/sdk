<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title, plain. */
/** @var string $text The text under the title, plain, with line breaks. */
/** @var common\models\Media|null $image The picture, or null for none. */
/** @var string $layout Where the picture goes: `background`, `right` or `left`. */
/** @var string $align Where the text sits: `start` or `center`. */
/** @var string $height How tall the block is at least: `sm`, `md`, `lg` or `full`. */
/** @var float $overlay How much the picture behind the text is darkened, 0 to 1. */
/** @var string $button The button as an anchor, or '' when it is not filled in. */

use yii\helpers\Html;

$classes = ['block-hero-inner', "block-hero-$layout", "block-hero-$align", "block-hero-$height"];
$style = null;
if ($image !== null) {
    $classes[] = 'block-hero-with-image';
    if ($layout === 'background') {
        $style = "--hero-image: url($image->url); --hero-overlay: $overlay;";
    }
}
?>
<div class="<?= implode(' ', $classes) ?>"<?= $style === null ? '' : ' style="' . Html::encode($style) . '"' ?>>
    <div class="container block-hero-body">
        <div class="block-hero-content">
            <h1 data-value="title"><?= Html::encode($title) ?></h1>
            <p class="lead" data-value="text"><?= nl2br(Html::encode($text)) ?></p>
            <?php if ($button !== ''): ?>
                <div class="block-hero-actions"><?= $button ?></div>
            <?php endif ?>
        </div>
        <?php if ($image !== null && $layout !== 'background'): ?>
            <div class="block-hero-media">
                <?= $image->img() ?>
            </div>
        <?php endif ?>
    </div>
</div>
