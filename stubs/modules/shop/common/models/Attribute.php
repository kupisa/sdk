<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\models\Hierarchy;
use common\models\Media;
use common\models\Term;
use Yii;

/**
 * An attribute of the products of the shop and its values, as one taxonomy on two levels: a term at the root is
 * an attribute ("Colour"), the terms under it are its values ("Red", "Blue"). A product that comes in several
 * colours names the attribute among its options (see `Product::$options`) and every variant of it has one value
 * of the attribute (see `Variant::getValues()`); a product may also have values of other attributes itself, its
 * attributes ("Material: cotton", see `Product::getSpecifications()`). Renaming an attribute or a value changes it
 * everywhere at once.
 *
 * An attribute says how the site shows its values to choose from (see {@see $display}): as buttons, in a
 * dropdown, as colours or as pictures; a value of an attribute shown as colours has its {@see $color}, one of an
 * attribute shown as pictures its {@see $image}, kept in the meta and shown in front of its name in the list of
 * the administration (see {@see swatch()}).
 */
class Attribute extends Term
{
    /**
     * The values as buttons with their names: how an attribute is shown unless it says otherwise.
     */
    public const DISPLAY_RADIO = 'radio';

    /**
     * The values in a dropdown.
     */
    public const DISPLAY_DROPDOWN = 'dropdown';

    /**
     * The values as circles of their colour.
     */
    public const DISPLAY_COLOR = 'color';

    /**
     * The values as their pictures.
     */
    public const DISPLAY_IMAGE = 'image';

    /**
     * How the site shows the values of the attribute, one of the `DISPLAY_*` constants; null for the default,
     * the buttons. Only an attribute has it (see {@see getDisplay()} for what a value answers).
     */
    public string|null $display = null;

    /**
     * The colour of a value of an attribute shown as colours, as `#rrggbb`. Untyped, so a form may send it as an
     * empty string when there is none.
     *
     * @var string|null
     */
    public $color = null;

    /**
     * The id of the picture of a value of an attribute shown as pictures, in the media library. Untyped like
     * {@see $color}.
     *
     * @var int|string|null
     */
    public $image = null;

    /**
     * {@inheritdoc}
     */
    public static function type(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function references(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function label(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function labelPlural(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * An attribute has no page on the site: its values are offered in the filters of the catalogue, and a hidden
     * one is left out of them. Hiding is for the filters only: the page of a product shows every attribute it has.
     */
    public static function isPublic(): bool
    {
    }

    /**
     * {@inheritdoc}
     *
     * Two levels: an attribute at the top, its values right under it (see `Term::validateParent()`).
     */
    public static function hierarchy(): Hierarchy
    {
    }

    /**
     * {@inheritdoc}
     *
     * A name is given once at its level: two attributes "Colour", or two values "Red" of one attribute, would
     * only confuse.
     */
    public function rules(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * The ways an attribute is shown on the site, by the `DISPLAY_*` constants: what its form offers.
     *
     * @return array<string, string>
     */
    public static function displayLabels(): array
    {
    }

    /**
     * How the values are shown on the site (one of the `DISPLAY_*` constants): what the attribute says, or the
     * buttons; a value answers for its attribute.
     */
    public function getDisplay(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * The colour of a value shown as colours, the picture of one shown as pictures, while it has one.
     */
    public function swatch(): array|null
    {
    }

    /**
     * The picture of the value from the media library, while the value is shown as one and the picture is still
     * there; null otherwise.
     */
    public function getImageMedia(): Media|null
    {
    }

    /**
     * Whether the term is a value of an attribute (below one), rather than an attribute itself.
     */
    public function isValue(): bool
    {
    }

    /**
     * The attributes with their values, in their order: what a product chooses its options from.
     *
     * @return list<array{attribute: static, values: list<static>}>
     */
    public static function findWithValues(): array
    {
    }
}
