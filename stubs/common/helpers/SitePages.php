<?php

declare(strict_types=1);

namespace common\helpers;

use common\models\Page;
use common\models\SiteSetting;

/**
 * The pages the site uses for a purpose, chosen among its pages on Settings › General (see
 * `backend\models\GeneralSettingsForm`): the home page, the privacy policy, the terms of use and the contact
 * page. Each is the published page the site chose, or null while it chose none or the page is not published, so whatever
 * needs one of them (a link in the footer, the cookie banner, the checkbox of a form) asks here and shows
 * nothing without it.
 */
final class SitePages
{
    /**
     * The page shown as the home page, or null for the built-in home page (the `home_page` setting).
     */
    public static function home(): Page|null
    {
    }

    /**
     * The privacy policy of the site (the `privacy_page` setting).
     */
    public static function privacy(): Page|null
    {
    }

    /**
     * The terms of use of the site (the `terms_page` setting).
     */
    public static function terms(): Page|null
    {
    }

    /**
     * The contact page of the site (the `contact_page` setting).
     */
    public static function contact(): Page|null
    {
    }
}
