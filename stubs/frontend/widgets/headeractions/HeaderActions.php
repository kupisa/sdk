<?php

declare(strict_types=1);

namespace frontend\widgets\headeractions;

use common\helpers\Hook;
use common\helpers\Signup;
use frontend\models\Search;
use Yii;
use yii\base\Widget;

/**
 * The actions of the header: the icons at the right of the bar, next to the navigation. The platform puts the
 * search there while the site has it on (see `frontend\models\Search`) and the account: for a visitor the way
 * to the login page, while the site takes new accounts (see `common\helpers\Signup`), and for a logged-in user,
 * always, the way to their account area (`/account`, where they log out too). A module adds its own through
 * the `header.actions` hook (filter):
 * the wishlist and the cart of a shop. Every action is its `label` (read by a screen reader and shown as a
 * tooltip), its `icon` (an SVG in the outline style of the site, `currentColor`) and either a `url` it leads to
 * or the id of the `dialog` it opens (see js/src/site.js), and says with `focused` whether it stays in the
 * focused header (see {@see $focused}): the cart of a shop does, the search and the account of the platform do not.
 *
 * The header of the site renders it in its bar (see blocks/header/views/header.php); a header of a theme does
 * the same, so the actions of every module reach it: `HeaderActions::widget()`.
 */
class HeaderActions extends Widget
{
    /**
     * Whether the header is focused (the `focused` layout: a page that keeps the visitor on what they are doing,
     * like the checkout): only the actions that say `focused` are shown then, and no dialog of the platform.
     */
    public bool $focused = false;

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
