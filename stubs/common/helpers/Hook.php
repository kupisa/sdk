<?php

declare(strict_types=1);

namespace common\helpers;

use Yii;

/**
 * The short way to the `hooks` component of the application, for views and modules (see
 * `common\components\Hooks` for what a hook is): `Hook::on()`, `Hook::filter()`, `Hook::run()` and `Hook::has()`
 * do what `Yii::$app->hooks->on()`, `->filter()`, `->run()` and `->has()` do.
 */
final class Hook
{
    /**
     * Attaches a handler to a hook, to run in the order of its priority (the lower first, 10 unless given).
     */
    public static function on(string $name, callable $handler, int $priority = 10): void
    {
    }

    /**
     * Passes a value through the handlers of a hook and returns what they made of it.
     */
    public static function filter(string $name, mixed $value, mixed ...$args): mixed
    {
    }

    /**
     * Calls the handlers of a hook with the arguments.
     */
    public static function run(string $name, mixed ...$args): void
    {
    }

    /**
     * Whether any handler is attached to a hook.
     */
    public static function has(string $name): bool
    {
    }
}
