<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $text The text of the banner, plain. */
/** @var string $link The link after the text as an anchor, or '' when it is not filled in. */

use yii\helpers\Html;

?>
<div class="container">
    <p><span data-value="text"><?= Html::encode($text) ?></span><?= $link === '' ? '' : ' ' . $link ?></p>
</div>
