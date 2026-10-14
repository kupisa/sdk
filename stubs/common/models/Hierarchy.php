<?php

declare(strict_types=1);

namespace common\models;

/**
 * How the terms of a taxonomy relate to one another, which decides how the administration lists them (see
 * `backend\controllers\TermController`) and whether a term takes a parent (see {@see Term::hierarchy()}).
 */
enum Hierarchy
{
    /**
     * A plain list: no term is below another (the tags of the blog).
     */
    case None;

    /**
     * A tree of any depth: a term may be below another, which may be below another (the categories of the blog
     * or of the shop). Listed as a tree, the terms below one indented under it.
     */
    case Tree;

    /**
     * Two levels only: terms at the top and their values right under them (the attributes of the shop and their
     * values). Listed by the terms at the top, each opened to its values.
     */
    case Levels;
}
