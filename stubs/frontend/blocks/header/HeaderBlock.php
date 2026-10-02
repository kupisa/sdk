<?php

declare(strict_types=1);

namespace frontend\blocks\header;

use common\helpers\Branding;
use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use Yii;

/**
 * The header of the site, shown on every page: the logo of the site (see `common\helpers\Branding`, chosen in
 * the settings) or its name, and the main menu of the site (see `common\models\Menu`, rendered by
 * `frontend\widgets\Nav`).
 */
class HeaderBlock extends Block
{
    /**
     * The id of the menu shown as the navigation (see `common\models\Menu`), or null for none.
     */
    public int|null $menu = null;

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function group(): BlockGroup
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function controls(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
