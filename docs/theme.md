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
    models/                      the kinds of pages and the taxonomies of the theme's own
    views/<type>/                the list and the page of a kind of page of the theme
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
- `pageTypes()`: the kinds of pages of its own, by type: `['program' => Program::class]` (see "A kind of page of
  its own" below).
- `taxonomies()`: the categories or tags those pages are put in, by type: `['vrtic_category' => Category::class]`
  (see "Categories and tags of its own" below).
- `fonts()`: the Google Fonts the site may choose from in its appearance settings, ten or so that suit the theme.
- `requires()`: the modules the theme needs (`['shop']`); it is offered only to a site that has them.
- `visibility()`: `Visibility::Private` (`common\models\Visibility`) for a theme made for one client. It is then
  offered only to the sites it names in `sites()`, by their `uid`, and to those it was given, which the people who
  run the server do.
- `regions()`: the countries whose sites it is made for, as ISO 3166-1 codes (`['CZ', 'SK']`); every site when
  left empty.

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
- The views of the shop are in `modules/shop/` of the SDK and are replaced under `themes/<name>/modules/shop/`
  the same way (see "A theme and the shop" in `docs/shop.md`). A theme whose own code uses the shop (a block
  that shows products) names it in `requires()`.

## A header or a footer of its own

A header with other controls than the one of the site (a phone number, a button) is a block that extends
`frontend\blocks\header\HeaderBlock` (or `footer\FooterBlock`), named in `areas()`. It adds its values and
controls to those of the parent (`[...parent::controls(), ...]`) and renders a view of its own; start that view
from `frontend/blocks/header/views/header.php`. The menus stay: they belong to the site, not to the block
(`frontend\widgets\Nav` renders one, `frontend\widgets\headeractions\HeaderActions` the actions next to it).

## A kind of page of its own

A theme that needs pages of another kind than a plain page (the daily programme of a kindergarten, the menus of
a restaurant) brings it as a class in `models/` that extends `common\models\Page` (see
`stubs/common/models/Page.php`), named in `pageTypes()`:

```php
class Program extends Page
{
    public string|null $date = null;

    public static function type(): string
    {
        return 'program';
    }

    public static function label(): string
    {
        return Yii::t('vrtic', 'Daily programme');
    }

    public static function labelPlural(): string
    {
        return Yii::t('vrtic', 'Daily programmes');
    }

    public static function path(): string
    {
        return Yii::t('vrtic', 'programmes', [], 'en');
    }
}
```

- `type()` is stored with every page of the kind; never change it once a site has pages of it.
- What a page of the kind has beyond its title, its address and its blocks is a public property; its value is
  kept with the page.
- The kind has an item of its own under "Content" in the administration, with the list of its pages and the form
  of a page, the one of a plain page. Its values are edited in the cards `formCards()` adds under the card of
  the title, each with a title, a sentence under it and its fields, written as the controls of a block:

  ```php
  public static function formCards(): array
  {
      return [
          [
              'label'       => Yii::t('vrtic', 'The day'),
              'description' => Yii::t('vrtic', 'The day the programme is for.'),
              'controls'    => [
                  ['name' => 'date', 'type' => 'date', 'label' => Yii::t('vrtic', 'Day')],
              ],
          ],
      ];
  }
  ```

  A control is `text`, `textarea`, `number`, `select` or `choice` (with their `options`, each a `value` and a
  `label`), `media` (a picture of the media library, its id) or `date` (a day as `Y-m-d`), with a `hint` under the
  field when it has one, and it names the property it edits. A text, a choice and a day are declared
  `public string|null $date = null;`, a number and a picture without a type (`public $photo = null;`). Every value
  is checked as its control says and is null while it is empty.
- `path()` is the word its addresses go under, in English: the list is at `/programmes` and each page at
  `/programmes/<slug>`, in the language of the site (the translation of the word) or as the site set it on
  Settings › Addresses.
- The list shows the published pages, twelve at a time, the newest first unless the kind says otherwise with
  `listOrder()`, as in `orderBy()`: a column of the page (`title`, `created`) or a value of its cards by its name,
  `['date' => SORT_ASC, 'title' => SORT_ASC]` for the programmes by their day and those of one day by their title
  (a number is ordered as a number, a page without the value comes last); `views/<type>/index.php` renders it
  with `$pages` and `$pagination` (`frontend\widgets\Pager::widget(['pagination' => $pagination])`), and
  `views/<type>/view.php` a page with `$page` (`frontend\widgets\PageContent::widget(['page' => $page])` for its
  blocks). The title and the breadcrumbs are set already. Without them the site uses those of the platform,
  `frontend/views/page/type-index.php` and `type-view.php`.
- The pages of the kind stay on a site that picks another theme, only they are not shown.

## Categories and tags of its own

The pages of a kind of the theme are put in categories or tags of the theme's own: a class in `models/` that
extends `common\models\Term` (see `stubs/common/models/Term.php`), named in `taxonomies()` of the theme and in
`taxonomies()` of the kind:

```php
class Category extends Term
{
    public static function type(): string
    {
        return 'vrtic_category';
    }

    public static function label(): string
    {
        return Yii::t('vrtic', 'Category');
    }

    public static function labelPlural(): string
    {
        return Yii::t('vrtic', 'Categories');
    }

    public static function path(): string
    {
        return Yii::t('vrtic', 'category', [], 'en');
    }
}
```

```php
// In Program, the kind of page:
public static function taxonomies(): array
{
    return [Category::class];
}
```

- `type()` starts with the name of the theme, and never changes once a site has terms of it.
- Categories have terms under terms; tags say so with `hierarchy()` returning `Hierarchy::None`.
- In the administration the taxonomy is under the item of the kind ("Programmes" › "Categories"), with the list
  and the form of its terms, and the form of a page of the kind picks its terms in a card on the right.
- A term has its page on the site under the word of the kind and the word of the taxonomy, `path()`:
  `/programmes/category/<slug>`, the list of the pages of the kind in the term and in the terms below it. It is
  rendered by `views/<type>/index.php` of the kind, with `$term` set (null on the list of all of them), its title
  and breadcrumbs set already. A taxonomy that should have no pages says so with `isPublic()` returning false.
- A view shows the terms of a page with `$page->visibleTerms(Category::class)`, each linking to `$term->getUrl()`.

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
