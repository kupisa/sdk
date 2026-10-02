<?php

/**
 * The hero as the starter theme draws it: the same as the one of the site (see apps/frontend/blocks/hero), with
 * a small line above the title that names the site. It is here to show how a theme replaces the view of a
 * block: the file sits at the same path as in apps/frontend/blocks and gets the same values from the block.
 */

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

use common\models\SiteSetting;
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
            <p class="block-hero-kicker"><?= Html::encode(SiteSetting::get('name', '')) ?></p>
            <h1 data-value="title"><?= Html::encode($title) ?></h1>
            <p class="lead" data-value="text"><?= nl2br(Html::encode($text)) ?></p>
            <?php if ($button !== ''): ?>
                <div class="block-hero-actions"><?= $button ?></div>
            <?php endif ?>
        </div>
        <?php if ($image !== null && $layout !== 'background'): ?>
            <div class="block-hero-media">
                <?= Html::img($image->url, ['alt' => $image->alt ?? '', 'width' => $image->width, 'height' => $image->height]) ?>
            </div>
        <?php endif ?>
    </div>
</div>
