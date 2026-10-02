<?php

declare(strict_types=1);

namespace themes;

/**
 * Who a theme is offered to (see {@see Theme::visibility()}).
 */
enum ThemeVisibility: string
{
    /**
     * Offered to every site.
     */
    case Public = 'public';

    /**
     * Offered only to the sites that were given the theme, e.g. a theme made for one client
     * (see {@see Themes::grant()}).
     */
    case Private = 'private';
}
