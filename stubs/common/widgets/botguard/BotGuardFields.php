<?php

declare(strict_types=1);

namespace common\widgets\botguard;

use common\components\BotGuard;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * The fields of the guard against bots inside a form (see `common\components\BotGuard`), for the kind of form its
 * model checks (`common\validators\BotGuardValidator`):
 *
 * ```php
 * <?= BotGuardFields::widget(['action' => 'login']) ?>
 * ```
 *
 * The trap no person sees, the token of the form and the number `botguard.js` finds for it while the visitor fills
 * the form in; a form sent before the number is found waits for it.
 */
class BotGuardFields extends Widget
{
    /**
     * The kind of form (`login`, `signup`, `form`, ...).
     */
    public string $action = '';

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
