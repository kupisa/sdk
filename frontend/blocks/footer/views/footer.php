<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $text Safe HTML, or '' to show the name of the site and the year. */
/** @var int|null $menu The id of the menu shown in the footer, or null for none. */

use frontend\widgets\Nav;
use yii\helpers\Html;

?>
<footer id="footer" class="block block-footer">
    <div class="container block-footer-bar">
        <p><?= $text !== '' ? $text : '© ' . Html::encode(Yii::$app->name) . ' ' . date('Y') ?></p>
        <?= Nav::widget(['menu' => $menu, 'label' => Yii::t('app', 'Footer navigation')]) ?>
    </div>
</footer>
