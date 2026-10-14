<?php

declare(strict_types=1);

namespace backend\widgets\backlink;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * The way back from a page of the administration to the section it belongs to: the form of a page leads back to
 * the list of pages, the page of a module to the list of modules. A view renders it right above its title:
 *
 * ```php
 * <?= BackLink::widget() ?>
 * ```
 *
 * The section comes from the breadcrumbs every view sets (`$this->params['breadcrumbs']`): the last crumb with
 * an address. A page at the top of its section, like a list, has no crumb with an address and shows nothing.
 */
class BackLink extends Widget
{
    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
