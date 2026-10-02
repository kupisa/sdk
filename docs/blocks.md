# A block

A block is one piece of a page: a hero, a gallery, a banner. A user adds blocks to a page in the page editor and
fills in their values; the site renders them one under another. A theme brings blocks of its own next to those
of the platform. `examples/themes/starter/blocks/banner/` is a complete one, and
`stubs/frontend/blocks/Block.php` the reference: its comments say everything a block may declare.

## The files

```
themes/<name>/blocks/banner/
    BannerBlock.php       the class: the values, how they are edited, what the view gets
    views/banner.php      the view: the HTML of the block
    icon.svg              its icon in the page editor (optional)
```

The theme names the block in `Theme::blocks()`: `['banner' => BannerBlock::class]`. The name is the one the
pages store the block under, so it never changes once sites use it. Its styles go into `public/theme.css`
under the class `block-banner`.

## The class

It extends `frontend\blocks\Block`.

- **Values** are public properties with a default: `public string $text = '';`. They are what a page stores.
- `label()`: the name of the block in the editor, translated.
- `group()`: where the editor lists it, a case of `frontend\blocks\BlockGroup` (`Text`, `Media`, `Sections`).
- `controls()`: how each value is edited, one definition per value in the order the form shows them:

  ```php
  return [
      ['name' => 'text', 'type' => 'text', 'label' => Yii::t('starter', 'Text')],
      ['name' => 'link', 'type' => 'link', 'label' => Yii::t('starter', 'Link')],
  ];
  ```

  The controls: `text`, `textarea`, `number`, `toggle`, `choice` (with its `options`), `media` (a picture),
  `menu`, `link`, `repeater`, `code`. The comment of `Block::controls()` describes each.
- `presets()`: the values a new block starts with in the editor, written so it looks finished as soon as it is
  added (a title, a text, the items of a list), translated. A picture is preset only as the stand-in of the
  platform (see below); a phone number or an address never, since it would go public as it is.
- `spacing()` and `padding()`: the space below the block and inside it unless the page says otherwise (`none`,
  `sm`, `lg`).
- `run()`: checks the values and hands the view what it shows:

  ```php
  return $this->render('banner', [
      'text' => $this->text,
      'link' => Link::html($this->link),
  ]);
  ```

  Everything derived is worked out here, never in the view.

## Kinds of values

- **A link** is `public array $button = Link::EMPTY;` with a `link` control; `Link::html($this->button,
  ['class' => 'btn btn-primary'])` gives the anchor, or `''` when it is not filled in.
- **A picture** is `public int|string|null $image = null;` with a `media` control: the id of a picture of the
  media library, or `Media::PLACEHOLDER` in the presets for the grey stand-in shown until the user picks one.
  `Media::image($this->image)` gives the `common\models\Media` or null, and the view prints it with
  `$image->img()` (`['thumbnail' => true]` for the small copy), never with an `<img>` written by hand.
- **A list of items** is one value, `public array $items = [];`, with a `repeater` control; the controls of
  the fields of one item name it as their `parent`, and `item_label` names the field that titles an item in the
  editor. Check every item in `run()` before the view gets it: an item is whatever a page stored.
- **A choice** is a string with a `choice` control and its `options` (`[['value' => 'left', 'label' => ...]]`);
  fall back to the default in `run()` when the stored value is not one of them.

## The view

- It renders only the content of the block. The platform renders the root element around it
  (`<div class="block block-banner ...">`) with the spacing, the padding, the anchor and the class the user set.
- Content that belongs in the width of the page sits in a `container`.
- It declares what it gets in `@var` comments at its top, prints every value encoded
  (`Html::encode($title)`, `nl2br(Html::encode($text))` for a text with line breaks) and holds no logic beyond
  `if` and `foreach`.
- The element that shows a plain text value says `data-value="<name>"` (`<h2 data-value="title">`): the user
  then edits that text right in the preview of the page editor.
- Semantic HTML, the classes of the block starting with `block-<name>-`.

## Changing a block of the platform

- To change only how it looks: the stylesheet of the theme (`.block-hero { ... }`).
- To change its HTML: a view at the same path in the theme (see "Replacing a view" in `docs/theme.md`).
- To give it other values or controls: a block of the theme's own, under a name of its own. The classes of the
  blocks of the platform are not public, except the header, the footer and the contact block.
