<?php

declare(strict_types=1);

namespace backend\controllers;

use yii\filters\AccessControl;

/**
 * Base class for every backend controller: guests are sent to the login page.
 *
 * Only users whose role opens the administration can be logged in here (see {@see \backend\models\User}), so
 * "logged in" is all that is checked by default. A controller of a part of the administration asks for its
 * capability instead (see `common\components\Roles`):
 *
 * ```php
 * $behaviors = parent::behaviors();
 * $behaviors['access']['rules'] = [['allow' => true, 'permissions' => ['pages']]];
 * ```
 *
 * A controller with actions open to everyone excludes them from the `access` filter.
 */
abstract class Controller extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
    }
}
