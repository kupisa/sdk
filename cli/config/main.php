<?php

/**
 * The configuration of the command line tool: a Yii2 console application whose commands are the controllers
 * in `cli/controllers/`, without the commands Yii2 brings (migrations, cache, ...) except the help.
 */

use yii\console\controllers\HelpController;

return [
    'id'                  => 'kupisa',
    'name'                => 'Kupiša',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'kupisa\cli\controllers',
    'enableCoreCommands'  => false,
    'controllerMap'       => ['help' => HelpController::class],
    'aliases'             => ['@kupisa/cli' => dirname(__DIR__)],
];
