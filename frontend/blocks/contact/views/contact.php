<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title The title, plain, or '' for none. */
/** @var string $text The text under the title, plain, with line breaks, or '' for none. */
/** @var string $phone The phone number as shown, or '' for none. */
/** @var string $phoneUrl The `tel:` address of the phone number, or '' for none. */
/** @var string $email The email address, or '' for none. */
/** @var string $address The address, plain, with line breaks, or '' for none. */
/** @var string $hours The opening hours, plain, with line breaks, or '' for none. */
/** @var string $map The address of the map of Google Maps to embed, or '' for none. */

use yii\helpers\Html;

$rows = [
    [Yii::t('app', 'Phone'), $phone === '' ? '' : ($phoneUrl === '' ? Html::encode($phone) : Html::a(Html::encode($phone), $phoneUrl))],
    [Yii::t('app', 'Email address'), $email === '' ? '' : Html::mailto(Html::encode($email), $email)],
    [Yii::t('app', 'Address'), nl2br(Html::encode($address))],
    [Yii::t('app', 'Opening hours'), nl2br(Html::encode($hours))],
];
$rows = array_filter($rows, static fn (array $row): bool => $row[1] !== '');
?>
<div class="container block-contact-inner">
    <div class="block-contact-details">
        <?php if ($title !== ''): ?>
            <h2 data-value="title"><?= Html::encode($title) ?></h2>
        <?php endif ?>
        <?php if ($text !== ''): ?>
            <p class="lead" data-value="text"><?= nl2br(Html::encode($text)) ?></p>
        <?php endif ?>
        <?php if ($rows !== []): ?>
            <dl class="block-contact-list">
                <?php foreach ($rows as [$label, $value]): ?>
                    <dt><?= Html::encode($label) ?></dt>
                    <dd><?= $value ?></dd>
                <?php endforeach ?>
            </dl>
        <?php endif ?>
    </div>
    <?php if ($map !== ''): ?>
        <div class="block-contact-map">
            <iframe src="<?= Html::encode($map) ?>" title="<?= Yii::t('app', 'Map') ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
    <?php endif ?>
</div>
