<?php

declare(strict_types=1);

namespace frontend\blocks;

use ReflectionClass;
use ReflectionProperty;
use yii\base\Widget;

/**
 * Base class of a content block: one piece of a page, rendered from the values stored in the page content.
 *
 * Every block lives in its own directory named after the block, e.g. `paragraph/ParagraphBlock.php`, with its
 * view files in `views` next to the class (`paragraph/views/paragraph.php`), as Yii looks them up for any widget.
 * A block declares its values as public properties with a default, e.g. `public string $text = '';`, and is
 * registered under its name in `params['blocks']` (see config/params.php).
 *
 * The page editor in the backend builds the form of a block from {@see values()} and {@see controls()}. A view
 * may mark the element that shows a value with `data-value="<name>"`, e.g. `<h2 data-value="text">`: the editor
 * then lets the user edit that value right in the preview of the page (see apps/frontend/public/js/page-preview.js).
 * The value is plain text, unless the element also says `data-html`, when it holds inline HTML the block
 * cleans before it shows it (see the paragraph). In a block with one such value, Enter starts the next block
 * (see {@see nextBlock()}) and Backspace in the empty value removes the block; in a block with several, like
 * the hero, they only edit the text.
 *
 * Beyond its values, every block has the same few settings (see {@see settings()}): a name of its own, the
 * space below it, the padding inside it, an anchor and a class of its own. They belong to the platform, not to
 * the block: the page editor edits them (the name in the header of the form of a block, the rest in its
 * "Styles" and "Advanced" panels), and `frontend\widgets\PageContent` applies them to the root element it
 * renders around the block. A block only says what its defaults are, and its view renders only its content,
 * in a `container` when it belongs in the width of the page.
 */
abstract class Block extends Widget
{
    /**
     * The choices of the spacing and the padding of a block: none, small or large. The stylesheet of the site
     * gives each a size (`block-spacing-sm`, `block-padding-lg`, see public/css/src/_blocks.scss).
     */
    public const array SIZES = ['none', 'sm', 'lg'];

    /**
     * The name of the block as shown in the page editor.
     */
    abstract public static function label(): string;

    /**
     * The group the page editor lists the block under when a block is added to a page.
     */
    abstract public static function group(): BlockGroup;

    /**
     * The icon of the block in the page editor: the `icon.svg` next to the class of the block, drawn for what
     * the block does (a 24 by 24 drawing in `currentColor`, see the icons of the core blocks), or the name of
     * a Font Awesome icon (https://fontawesome.com/v5/search) the block returns instead, e.g. `image` for `fa-image`.
     * A plain square when there is neither.
     */
    public static function icon(): string
    {
    }

    /**
     * How the page editor edits each value: one definition per value, in the order the form shows them, like
     * the rules of a model or the columns of a grid:
     *
     * ```php
     * return [
     *     [
     *         'name' => 'text',
     *         'type' => 'textarea',
     *         'label' => Yii::t('app', 'Text'),
     *     ],
     * ];
     * ```
     *
     * Every definition names its value (`name`), the control it is edited with (`type`) and the label shown
     * above the control (`label`). The controls live in the editor, see apps/backend/public/js/editor/controls:
     * `text` (with a `hint` shown under it), `textarea`, `number` (with `min`, `max`, `step` and, for a size, its `units`, see {@see Size}),
     * `code` (code in the `language` it names, `html`, `css` or `javascript`, edited with CodeMirror),
     * `toggle` (yes or no, a boolean), `choice` (with its `options`), `media`, `menu` (a menu of the site, held as its id, see
     * `common\models\Menu`), `link` (see {@see Link}), `links` and `repeater` (see below). A control may take further options, which the editor receives as they are given
     * here. A value without a definition is edited in a plain text input under its own name.
     *
     * A list of items, like the features of a site, is one value (`public array $items = [];`) edited with the
     * `repeater` control, and the controls of the fields of one item name it as their `parent`:
     *
     * ```php
     * ['name' => 'items', 'type' => 'repeater', 'label' => Yii::t('app', 'Items'), 'item_label' => 'title'],
     * ['name' => 'title', 'type' => 'text', 'label' => Yii::t('app', 'Title'), 'parent' => 'items'],
     * ```
     *
     * A control may name a `panel` (`'panel' => Yii::t('app', 'Colours')`): the editor then shows the value in a
     * panel of that title under the values without one, the first panel open and the others folded (see the
     * appearance of the site).
     *
     * A field may be any control but a repeater. The value of the field named by `item_label` is the title
     * of the item in the editor ("Item #1" while it is empty). The items are stored as a list of arrays keyed
     * by the names of the fields; the block checks each item before it shows it, as it does any value.
     *
     * @return list<array{name: string, type: string, label: string, parent?: string, hint?: string, panel?: string, language?: string, options?: list<array{value: string, label: string}>}>
     */
    public static function controls(): array
    {
    }

    /**
     * The values of the block that hold the id of a record of the site, and what kind of record each names:
     * `media` (a picture of the media library), `menu` or `page`, by the name of the value, or by `items.image`
     * for a field of the items of a repeater. The demo of a theme carries them to another site under keys of
     * their own (see `themes\Demo`). They are read from the controls, a `media` control naming a picture and a
     * `menu` control a menu; a block with a value that names a page says so itself.
     *
     * @return array<string, string>
     */
    public static function references(): array
    {
    }

    /**
     * The block that Enter starts below this one when a value of it is edited right in the preview (see
     * `data-value` above): the name of a block, or null for another block of the same kind. A heading says
     * `paragraph`, as the text goes on under it.
     */
    public static function nextBlock(): string|null
    {
    }

    /**
     * The settings every block has, with their defaults for this block: `title`, the name the page editor
     * shows the block by in place of the label of its kind, empty until the user names it ("Hero with the
     * prices"), `spacing`, the space below the block that keeps the next one away (none, sm or lg, see
     * {@see spacing()}), `padding`, the space inside the block above and below its content (see
     * {@see padding()}), `anchor`, the id of the block on the page for a link to it, and `class`, a class of
     * its own for the stylesheet of a theme.
     *
     * @return array{title: string, spacing: string, padding: string, anchor: string, class: string}
     */
    public static function settings(): array
    {
    }

    /**
     * The space below the block unless the page says otherwise: small. A block that flows into the next one,
     * like a paragraph, says `none`; a block that stands on its own, like a picture, says `lg`.
     */
    public static function spacing(): string
    {
    }

    /**
     * The padding above and below the content of the block unless the page says otherwise: none.
     */
    public static function padding(): string
    {
    }

    /**
     * The values a block starts with when it is added to a page in the editor, by name: a title, a text, a
     * button and the items of a repeater, written so the block looks finished as soon as it is added and the
     * user only changes them, like the presets of a section of a Shopify theme. They are content: the editor
     * stores them as the values of the new block, in the language of the site, and the site shows them until
     * the user writes their own. A value left out starts with its default (see {@see values()}); a picture, a
     * phone number or an address is left out, since a stand-in for those would go public as it is.
     *
     * @return array<string, mixed>
     */
    public static function presets(): array
    {
    }

    /**
     * The values of the block with their defaults: every public property the block declares.
     *
     * @return array<string, mixed>
     */
    public static function values(): array
    {
    }
}
