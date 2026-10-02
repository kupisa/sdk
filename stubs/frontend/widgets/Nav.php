<?php

declare(strict_types=1);

namespace frontend\widgets;

use common\models\Menu;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Renders a menu of the site (see `common\models\Menu`) as navigation: `<nav>` with the items as a list, and
 * the items below an item as a list inside it, three levels deep at most; anything deeper is left out. An item
 * that points nowhere (a page that was deleted or is not published) is left out. Nothing is rendered without
 * a menu or without items. In a block: `Nav::widget(['menu' => $this->menu, 'label' => Yii::t('app', 'Main navigation')])`.
 *
 * An item with items below it is a `nav-parent`, its link followed by a button that opens them where they
 * fold (the side panel on a phone, see public/js/src/site.js) and by the list `nav-sub`. When any of the items
 * below it has items of its own, the parent is a `nav-mega` too: its items are groups (`nav-group`), each its
 * link as the heading and the items below it as a `nav-sub`, so the header can lay them out as a mega menu.
 * The stylesheet of the header decides how each shows (see public/css/src/blocks/_header.scss).
 */
class Nav extends Widget
{
    /**
     * How many levels of items are rendered: an item, the items below it, and theirs.
     */
    public const int LEVELS = 3;

    /**
     * The id of the menu, as a block holds it, or null for none.
     */
    public int|null $menu = null;

    /**
     * What the navigation is, for a screen reader.
     */
    public string $label = '';

    /**
     * The HTML attributes of the list of the items at the top.
     */
    public array $options = ['class' => 'nav'];

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
