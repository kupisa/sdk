<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var common\models\Media|null $logo The logo, or null to show the name of the site. */
/** @var int|null $menu The id of the menu shown as the navigation, or null for none. */
/** @var bool $focused Whether the header is focused (the `focused` layout): the brand and the actions meant for it only, no navigation. */

use frontend\widgets\headeractions\HeaderActions;
use frontend\widgets\Nav;
use yii\helpers\Html;

$name = Yii::$app->name;
$brand = $logo === null
    ? Html::encode($name)
    : Html::img($logo->url, ['alt' => $name, 'height' => 40]);

// The menu, once in the bar and once in the side panel of a phone. Signing in and out is not part of it: a
// module brings its account links when it needs them, as actions of the header (see HeaderActions), which
// stand between the navigation and the menu button.
$nav = Nav::widget(['menu' => $menu, 'label' => Yii::t('app', 'Main navigation')]);
?>
<?php if ($focused): ?>
<header id="header" class="block block-header block-header-focused">
    <div class="container block-header-bar">
        <?= Html::a($brand, Yii::$app->homeUrl, ['class' => 'block-header-brand']) ?>
        <?= HeaderActions::widget(['focused' => true]) ?>
    </div>
</header>
<?php else: ?>
<header id="header" class="block block-header">
    <div class="container block-header-bar">
        <?= Html::a($brand, Yii::$app->homeUrl, ['class' => 'block-header-brand']) ?>
        <div class="block-header-nav"><?= $nav ?></div>
        <?= HeaderActions::widget() ?>
        <?= Html::button(
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>'
            . Html::tag('span', Yii::t('app', 'Menu'), ['class' => 'visually-hidden']),
            ['class' => 'block-header-toggle', 'data-dialog' => 'site-menu'],
        ) ?>
    </div>
    <dialog id="site-menu" class="dialog dialog-side block-header-menu">
        <div class="dialog-body">
            <?= Html::button('&times;' . Html::tag('span', Yii::t('app', 'Close'), ['class' => 'visually-hidden']), [
                'class' => 'dialog-close',
                'data-dialog-close' => true,
            ]) ?>
            <?= $nav ?>
        </div>
    </dialog>
</header>
<?php endif ?>
