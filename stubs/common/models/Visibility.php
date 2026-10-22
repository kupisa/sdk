<?php

declare(strict_types=1);

namespace common\models;

/**
 * Who a module or a theme is offered to (see `modules\Module::visibility()` and `themes\Theme::visibility()`).
 */
enum Visibility: string
{
    /**
     * Offered to every site, or to the sites of the regions it names.
     */
    case Public = 'public';

    /**
     * Offered only to the sites that were given it or that it names, e.g. a module or a theme made for one
     * client (see `modules\Modules::grant()` and `themes\Themes::grant()`).
     */
    case Private = 'private';
}
