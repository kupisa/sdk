<?php

declare(strict_types=1);

namespace backend\grid;

use Yii;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;
use yii\grid\Column;

/**
 * The last column of a list in the administration: the actions of a row, as a "three dots" button that opens
 * a menu. Every list has one, so a row is always worked on the same way. In a GridView:
 *
 * ```php
 * [
 *     'class' => ActionsColumn::class,
 *     'items' => static fn (Post $post): array => [
 *         ['label' => Yii::t('blog', 'Edit'), 'icon' => 'pen', 'url' => ['update', 'id' => $post->id]],
 *         ['label' => Yii::t('blog', 'Delete'), 'icon' => 'trash-alt', 'url' => ['delete', 'id' => $post->id], 'linkOptions' => ['data-method' => 'post']],
 *     ],
 * ]
 * ```
 *
 * An item is as `yii\bootstrap5\Dropdown` takes it, plus `icon`, the name of the Font Awesome icon shown before
 * its label (https://fontawesome.com/v5/search), e.g. `pen` for `fa-pen`. A click on the menu does not open the row (see public/js/src/app.js).
 */
class ActionsColumn extends Column
{
    /**
     * The items of the menu of a row, given the model of the row, in the form of `yii\bootstrap5\Dropdown::$items`,
     * each with its `icon` (see above).
     *
     * @var callable(mixed $model): array
     */
    public $items;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
    }
}
