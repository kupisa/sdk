<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $align Where the button sits: `start` or `center`. */
/** @var string $button The link as a button, or nothing when it is not filled in. */

?>
<div class="<?= $align === 'start' ? 'container' : "container text-$align" ?>">
    <?= $button ?>
</div>
