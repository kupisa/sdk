<?php

/**
 * Configuration of `./yii message/extract @themes/starter/messages/config.php`, which collects every
 * `Yii::t('starter', ...)` string of the theme into the message file of each language in this directory.
 */

declare(strict_types=1);

return [
    'sourcePath' => dirname(__DIR__),
    'messagePath' => __DIR__,
    'languages' => ['sr-Latn', 'sk'],
    'translator' => 'Yii::t',
    'sort' => true,
    'removeUnused' => true,
    'markUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '/messages',
    ],
];
