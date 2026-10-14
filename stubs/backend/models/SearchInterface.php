<?php

declare(strict_types=1);

namespace backend\models;

/**
 * The search form above a list in the administration (see `backend\widgets\searchbar\SearchBar`): the text to look for
 * in its `q` attribute, and the filters behind the "Filters" button. See {@see PageSearch} for one.
 *
 * @property string $q The text to look for.
 */
interface SearchInterface
{
    /**
     * Whether a search text or a filter is set, i.e. the list shows less than everything.
     */
    public function isFiltering(): bool;

    /**
     * Whether one of the filters behind the "Filters" button is set, so they are shown open.
     */
    public function hasFilters(): bool;
}
