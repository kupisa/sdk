<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use frontend\assets\ThemeAsset;

// The stylesheet and the script of the site, and the stylesheet of its theme after them.
ThemeAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(
    ['charset' => Yii::$app->charset],
    'charset',
);
$this->registerMetaTag(
    [
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1',
    ],
);
// The favicon, the sign of Kupiša or the one the site chose (see `frontend\components\Favicon`, which writes it
// again under the same key with the version of the picture in its address).
$this->registerLinkTag(
    [
        'rel' => 'icon',
        'type' => 'image/x-icon',
        'href' => Yii::getAlias('@web/favicon.ico'),
    ],
    'favicon',
);
