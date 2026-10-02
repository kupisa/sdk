<?php

declare(strict_types=1);

namespace themes\starter;

use themes\starter\blocks\banner\BannerBlock;
use themes\Theme as BaseTheme;
use Yii;

/**
 * The starter theme: it shows, in the smallest possible form, everything a theme can do, so that a theme of
 * your own starts as a copy of it. Its `public/theme.css` gives the design tokens of the site other values (a
 * warm palette and a serif face for the headings), its `blocks/hero/views/hero.php` replaces the
 * view of the hero of the site, and it brings a block of its own, the banner in `blocks/banner/` (see
 * {@see blocks()}).
 *
 * A theme drawn for a shop would name the module in {@see requires()} (`['shop']`), and a theme made for one
 * client would say it is private in {@see visibility()} (`ThemeVisibility::Private`), so that only the sites
 * given the theme see it (`./yii theme/grant <host> <theme>`).
 */
class Theme extends BaseTheme
{
    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
        return Yii::t('starter', 'Starter');
    }

    /**
     * {@inheritdoc}
     */
    public static function description(): string
    {
        return Yii::t('starter', 'A warm palette with serif headings, and a banner block. Copy it to start a theme of your own.');
    }

    /**
     * {@inheritdoc}
     */
    public static function features(): array
    {
        return [
            Yii::t('starter', 'Responsive'),
            Yii::t('starter', 'Serif headings'),
            Yii::t('starter', 'Banner block'),
            Yii::t('starter', 'Warm colours'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function blocks(): array
    {
        return [
            'banner' => BannerBlock::class,
        ];
    }
}
