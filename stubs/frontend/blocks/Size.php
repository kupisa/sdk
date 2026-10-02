<?php

declare(strict_types=1);

namespace frontend\blocks;

/**
 * A size a block holds, as the page editor stores it with its `number` control when the control offers units:
 * `['value' => 1.5, 'unit' => 'rem']`. A block names the units it offers once, e.g. `UNITS = ['rem', 'px']`,
 * gives them to the control (`'units' => self::UNITS`) and starts the value empty in the first of them
 * (`['value' => null, 'unit' => 'rem']`, what {@see empty()} gives). A `number` control without units holds a
 * plain number instead.
 */
final class Size
{
    /**
     * The size as a CSS length, e.g. `1.5rem`, or '' without a value, with a unit the block does not offer,
     * or for a value that is not a size at all.
     */
    public static function css(mixed $size, array $units): string
    {
    }

    /**
     * A size with no value yet, in the first of the units.
     *
     * @return array{value: null, unit: string}
     */
    public static function empty(array $units): array
    {
    }
}
