<?php

declare(strict_types=1);

namespace backend\widgets\searchbar;

use backend\models\SearchInterface;
use yii\base\Model;
use yii\base\Widget;

/**
 * The search bar above a list in the administration: the text to look for, the "Search" button and the
 * "Filters" button that opens the filters of the list. Every list has one, so a list is always searched the
 * same way. The bar is the form of the list, sent with GET to the `index` action (or the `action` given):
 *
 * ```php
 * SearchBar::widget([
 *     'id' => 'post-search-form',
 *     'model' => $searchModel,
 *     'placeholder' => Yii::t('blog', 'Search by title'),
 *     'filters' => $this->render('_filters', ['searchModel' => $searchModel]),
 * ])
 * ```
 */
class SearchBar extends Widget
{
    /**
     * The id of the form, e.g. `post-search-form`.
     */
    public string $id;

    /**
     * The search form of the list, with its `q` attribute (see {@see SearchInterface}).
     *
     * @var Model&SearchInterface
     */
    public Model $model;

    /**
     * What the text is looked for in, shown in the empty field, e.g. "Search by title or address".
     */
    public string $placeholder;

    /**
     * The fields of the filters, as HTML, e.g. the drop-down list of the statuses; empty for a list with none.
     */
    public string $filters = '';

    /**
     * The action the bar sends the search to: the `index` action of the controller, unless the list is elsewhere.
     */
    public array $action = ['index'];

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
