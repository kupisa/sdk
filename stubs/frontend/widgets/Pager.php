<?php

declare(strict_types=1);

namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/**
 * The pager of a long list on the site (the products of the shop, the posts of the blog): the way to the
 * previous and the next page, the first and the last page always, the pages right around the current one, and
 * an ellipsis where pages are left out ("« 1 … 4 5 6 … 10 »"); a single page that an ellipsis would hide is
 * shown instead. Rendered with the classes the stylesheet of the site styles (`pagination`, `page-item`,
 * `page-link`), so a view needs to give it the pagination only.
 */
class Pager extends LinkPager
{
    /**
     * How many pages are shown on each side of the current one.
     */
    public int $around = 1;

    public $options = ['class' => 'pagination'];
    public $linkContainerOptions = ['class' => 'page-item'];
    public $linkOptions = ['class' => 'page-link'];
    public $disabledListItemSubTagOptions = ['class' => 'page-link'];

    /**
     * {@inheritdoc}
     */
    protected function renderPageButtons(): string
    {
    }
}
