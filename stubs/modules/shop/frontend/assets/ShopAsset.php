<?php

declare(strict_types=1);

namespace modules\shop\frontend\assets;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * The stylesheet and the script of the shop on the site (public/css/shop.css, public/js/shop.js), registered by
 * the views of the shop that need them. The stylesheet comes after the stylesheet of the site and only ever uses
 * its tokens, so a theme changes its look too. The script drives the gallery of a product: a click on a small
 * picture shows it large.
 */
class ShopAsset extends AssetBundle
{
    public $sourcePath = '@modules/shop/frontend/public';
    public $css = [
        'css/shop.css',
    ];
    public $js = [
        'js/shop.js',
    ];
    public $depends = [
        AppAsset::class,
    ];
}
