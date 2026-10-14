<?php

declare(strict_types=1);

namespace modules;

/**
 * The two kinds of modules: what a site is, and what it gets on top.
 */
enum ModuleType: string
{
    /**
     * A module that defines what the site does, such as the shop or the booking. A site gets its core modules
     * when it is created; they are not offered in the list of modules.
     */
    case Core = 'core';

    /**
     * A module a site enables in its list of modules, such as a blog or product reviews. It may require a core
     * module to be there (see {@see Module::requires()}).
     */
    case Regular = 'regular';
}
