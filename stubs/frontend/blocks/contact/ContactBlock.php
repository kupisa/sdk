<?php

declare(strict_types=1);

namespace frontend\blocks\contact;

use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use Yii;

/**
 * How to reach the site: the phone number (a link that calls it on a phone), the email address, the address
 * and the opening hours, beside a map of Google Maps when the page gives one. The title and the text are
 * edited right in the preview.
 */
class ContactBlock extends Block
{
    /**
     * The title, plain, or '' for none.
     */
    public string $title = '';

    /**
     * A text under the title, plain; a line break starts a new line. Or '' for none.
     */
    public string $text = '';

    /**
     * The phone number as it is shown, e.g. `063 123 456`; the link dials its digits.
     */
    public string $phone = '';

    /**
     * The email address.
     */
    public string $email = '';

    /**
     * The address, plain; a line break starts a new line.
     */
    public string $address = '';

    /**
     * The opening hours, plain; a line break starts a new line.
     */
    public string $hours = '';

    /**
     * The address of the map to show, as Google Maps gives it under "Share" > "Embed a map"
     * (`https://www.google.com/maps/embed?pb=...`; `https://www.google.com/maps?q=...&output=embed` works
     * too), or '' for no map.
     */
    public string $map = '';

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function group(): BlockGroup
    {
    }

    /**
     * {@inheritdoc}
     *
     * The block stands on its own, with room below it.
     */
    public static function spacing(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * The details are stand-ins that read as such (`example.com`, a number of zeros), never something a
     * visitor could take for real, until the site writes its own.
     */
    public static function presets(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function controls(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * A map that is not one of Google Maps is not shown, so the page never embeds a page from anywhere else.
     */
    public function run(): string
    {
    }

    /**
     * The `tel:` address that dials the phone number: its digits and a leading plus, e.g. `tel:+381631234567`
     * for `+381 63 123 4567`. '' for a number without digits.
     */
    public static function phoneUrl(string $phone): string
    {
    }

    /**
     * Whether the address is a map of Google Maps: `https://www.google.com/maps/...` or
     * `https://maps.google.com/maps...`, over HTTPS.
     */
    public static function isGoogleMap(string $url): bool
    {
    }
}
