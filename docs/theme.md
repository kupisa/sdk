# A theme

A theme is the look of a site. The site always loads the stylesheet, the layouts and the blocks of the platform;
a theme goes on top of them and keeps only what it changes. `examples/themes/starter/` shows all of it in the
smallest form: copy it to `themes/<name>/`, rename its namespace and its translation category, and change it.

## The directory

```
themes/<name>/
    Theme.php                    describes the theme
    public/theme.css             the design tokens with other values, and the styles of what the theme adds
    public/screenshots/1.png     the pictures of the theme shown before a site picks it
    views/                       replaces a view of the site at the same path
    blocks/<block>/views/        replaces the view of a block of the platform
    blocks/<block>/              a block of the theme's own
    messages/<language>/<name>.php   the translations of its texts
    docs/<language>.md           what the theme looks like, shown on its page in the administration
```

Nothing is registered anywhere: the platform finds the theme by its directory and its `Theme` class.

## The `Theme` class

It extends `themes\Theme` (see `stubs/themes/Theme.php`, whose comments are the reference) and describes the
theme in static methods:

- `label()` and `description()`: the name and a sentence or two, both translated. Required.
- `features()`: what the theme brings, a word or two each ("Responsive", "Call button").
- `blocks()`: the blocks of its own, by name: `['banner' => BannerBlock::class]` (see `docs/blocks.md`).
- `areas()`: a header or a footer of its own in place of the one of the site: `['header' => HeaderBlock::class]`.
- `fonts()`: the Google Fonts the site may choose from in its appearance settings, ten or so that suit the theme.
- `requires()`: the modules the theme needs (`['shop']`); it is offered only to a site that has them.
- `visibility()`: `ThemeVisibility::Private` for a theme made for one client. It is then offered only to the
  sites it was given, which the people who run the server do.

## The stylesheet

`public/theme.css` loads on every page after the stylesheet of the site, so it only says what it changes.

- First, give the design tokens other values in `:root`. `frontend/public/css/src/_tokens.scss` lists every
  token: the colours, the fonts, the scale of sizes and spaces, the corners. Most of a theme is this.
- Then style what the theme adds (its own blocks, the elements its views add), using the tokens:
  `color: var(--color-primary)`, `padding: var(--space-3)`. No colour or size of its own outside `:root`, sizes
  in `rem`.
- The layout and utility classes of the site carry the names of Bootstrap (`container`, `row`, `col-md-6`,
  `d-flex`, `mt-3`, `btn btn-primary`), but the site loads no framework: only the classes the views in
  `frontend/` use are sure to exist. For anything else, write the rule in `theme.css`.
- The site may still change its colours and fonts in its appearance settings, which win over the theme. That is
  why everything must build on the tokens.

It is plain CSS: there is no build step.

## Replacing a view

`frontend/` in the SDK mirrors the site: `frontend/views/` holds the layouts and pages, and
`frontend/blocks/<block>/views/` the view of every block. To change one, copy it to the same path in the theme
and edit the copy:

| The view of the platform | Its replacement in the theme |
|---|---|
| `frontend/views/layouts/main.php` | `themes/<name>/views/layouts/main.php` |
| `frontend/blocks/hero/views/hero.php` | `themes/<name>/blocks/hero/views/hero.php` |

- The replacement gets the same variables as the original (its `@var` comments list them) and cannot ask for
  more. When a view needs another value, the block needs to be one of the theme's own.
- Keep the classes and the `data-` attributes of the original (`data-value`, `data-dialog`, ...): the page
  editor and the scripts of the site rely on them.
- A view of the platform prints its texts with `Yii::t('app', ...)`. Keep those as they are in a copy; a text
  the theme adds uses the category of the theme.
- Replace only what the stylesheet cannot do. A view the theme does not have stays the one of the platform and
  keeps getting its improvements.

## A header or a footer of its own

A header with other controls than the one of the site (a phone number, a button) is a block that extends
`frontend\blocks\header\HeaderBlock` (or `footer\FooterBlock`), named in `areas()`. It adds its values and
controls to those of the parent (`[...parent::controls(), ...]`) and renders a view of its own; start that view
from `frontend/blocks/header/views/header.php`. The menus stay: they belong to the site, not to the block
(`frontend\widgets\Nav` renders one, `frontend\widgets\headeractions\HeaderActions` the actions next to it).

## Texts and translations

- Every text goes through `Yii::t('<name>', '...')`, the name of the theme as the category, written in English.
- Its translations are in `messages/<language>/<name>.php`, a file that returns the texts by their English
  source, sorted:

  ```php
  <?php

  return [
      'Banner'     => 'Baner',
      'Responsive' => 'Responzivan dizajn',
  ];
  ```

- The interface of the platform exists in English, Serbian in Latin script (`sr-Latn`) and Slovak (`sk`); a
  theme translates its texts into the languages its sites use, and both unless told otherwise.

## The pictures and the docs

- `public/screenshots/` holds the pictures of the theme in the order of their file names. The first is its
  picture in the list of themes: the top of its home page on a wide screen, 1440 by 823 (7 by 4).
- `docs/<language>.md` (`en.md` is the fallback) tells a person who is not technical what the theme looks like
  and what it adds, under the headings "What it looks like" and "What it adds", in a friendly voice. A bulleted
  list there is shown with a tick before each item. Nothing for developers.
