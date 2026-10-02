<?php

declare(strict_types=1);

namespace frontend\blocks\footer;

use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use Yii;
use yii\helpers\HtmlPurifier;

/**
 * The footer of the site, shown on every page: a text and a menu (see `common\models\Menu`, rendered by
 * `frontend\widgets\Nav`).
 */
class FooterBlock extends Block
{
    /**
     * The text as HTML. Anything unsafe is removed before it is shown. Empty shows the name of the site and the year.
     */
    public string $text = '';

    /**
     * The id of the menu shown in the footer (see `common\models\Menu`), or null for none.
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
