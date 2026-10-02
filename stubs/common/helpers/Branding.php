<?php

declare(strict_types=1);

namespace common\helpers;

use common\models\Media;
use common\models\SiteSetting;

/**
 * The pictures the site is known by, chosen among the pictures of its media library on Settings › General
 * (see `backend\models\GeneralSettingsForm`): its logo and its favicon. Each is the picture the site chose,
 * or null while it chose none or the picture is gone, so whatever shows one of them (the header, the head of
 * every page, an email) asks here and falls back to the name of the site or the icon of the platform.
 */
final class Branding
{
    /**
     * The logo of the site (the `logo` setting), shown in the header in place of the name of the site.
     */
    public static function logo(): Media|null
    {
    }

    /**
     * The favicon of the site (the `favicon` setting), a square PNG or SVG shown in the tab of the browser.
     */
    public static function favicon(): Media|null
    {
    }
}
