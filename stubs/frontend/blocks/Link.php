<?php

declare(strict_types=1);

namespace frontend\blocks;

use yii\helpers\Html;

/**
 * A link a block holds, as the page editor stores it with its `link` control: `['label' => 'Read more',
 * 'url' => '/about', 'new_tab' => false]`. A value of a block that is a link declares that as its default:
 * `public array $button = Link::EMPTY;`.
 */
final class Link
{
    /**
     * A link with nothing filled in.
     */
    public const array EMPTY = ['label' => '', 'url' => '', 'new_tab' => false];

    /**
     * The link as an anchor, with the given HTML attributes; one that opens in a new tab does so safely. Nothing
     * for a link without a label or an address, or for a value that is not a link at all.
     */
    public static function html(mixed $link, array $options = []): string
    {
    }

    /**
     * Whether the value is not a link with both a label and an address.
     */
    public static function isEmpty(mixed $link): bool
    {
    }
}
