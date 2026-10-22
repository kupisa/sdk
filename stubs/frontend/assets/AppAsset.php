<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * The stylesheet and the script of the site, loaded on every page.
 *
 * The files in public/dist are compiled from public/css/src (`site.scss`, the design tokens, the layout, the forms
 * and the blocks) and public/js/src with webpack (`npm run build` in apps/frontend). The site loads no CSS
 * framework and no JavaScript library; a form loads jQuery and the client-side validation of Yii by itself,
 * only on its own page.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'dist/site.css',
    ];
    public $js = [
        'dist/site.js',
    ];
}
