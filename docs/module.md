# A module

A module is a feature a site enables on top of its theme: a kind of page of its own (realizations, events), a
taxonomy, blocks, settings, pages in the administration, fields on the forms of the platform. A site enables it
under Settings › Modules, like a module of the platform, and can disable it there again.

A module for a particular site is made only here, in `modules/<name>/` of this repository, which lands in the
`sites/` directory of the platform. There is no other place for it: the platform does not take modules from
anywhere else.

## The directory

```
modules/<name>/
    Module.php                       describes the module (required)
    image.svg                        its picture in the list of modules
    docs/<language>.md               what it does, shown on its page in the administration
    messages/<language>/<name>.php   the translations of its texts
    common/models/                   what both sides use: its kinds of pages, its taxonomies
    backend/controllers/, views/     its pages in the administration
    frontend/controllers/, views/    its pages on the site
    frontend/blocks/<block>/         its blocks (see docs/blocks.md)
```

Nothing is registered anywhere: the platform finds the module by its directory and its `Module` class. The
directories mirror the platform: the administration loads the controllers and views of `backend/`, the site
those of `frontend/`.

## The `Module` class

`modules/<name>/Module.php` declares the class `Module` in the namespace of its directory
(`sites\acme\modules\<name>\Module`), and it **extends `modules\Module`** (see `stubs/modules/Module.php`,
whose comments are the reference):

```php
namespace sites\acme\modules\realizations;

use modules\Module as BaseModule;
use modules\ModuleType;
use Yii;

/**
 * The realizations of Acme: the gardens it designed and built, as pages of their own kind.
 */
class Module extends BaseModule
{
    public static function label(): string
    {
        return Yii::t('realizations', 'Realizations');
    }

    public static function description(): string
    {
        return Yii::t('realizations', 'The gardens you designed and built, each with a page of its own.');
    }

    public static function type(): ModuleType
    {
        return ModuleType::Regular;
    }
}
```

- **Never `yii\base\Module`**, and never a class of your own in between. The platform asks every module what it
  brings through the methods of `modules\Module`; a class that does not extend it is not loaded at all, and the
  log of the server says why.
- `label()`, `description()` and `type()` are required. `type()` is always `ModuleType::Regular`: the core
  modules (the shop, ...) belong to the platform.
- Every other method has a default that brings nothing; override only what the module has:
  - `blocks()`: its blocks, by name: `['realizations' => RealizationsBlock::class]`.
  - `pageTypes()`: its kinds of pages, by type: `['realization' => Realization::class]`.
  - `taxonomies()`: its taxonomies, by type: `['realizations_kind' => Kind::class]`.
  - `roles()`: roles of its own, or more for a role of the platform:
    `['editor' => ['capabilities' => ['realizations']]]`.
  - `requires()`: the modules it needs (`['shop']`); it is offered only to a site that has them.
  - `visibility()`, `sites()` and `regions()`: who it is offered to. `Visibility::Private`
    (`common\models\Visibility`) for a module made for one client, offered then only to the sites it names in
    `sites()` by their `uid` and to those it was given, which the people who run the server do; `regions()` the
    countries whose sites it is made for, as ISO 3166-1 codes (`['CZ', 'SK', 'HU']`), every site when empty.
    The `uid` and the `region` of a connected site are in `.kupisa.json` (see `AGENTS.md`).
  - `urlRules()` and `paths()`: its addresses on the site (see "Pages on the site" below).
  - `hasSettings()`: true when it has a settings page (see "Settings" below).
- `tools()` and `notifications()` are not open to a module here yet: leave them as they are.
- `path()`, `name()`, `details()`, `image()`, `isAvailable()` and `application()` are the platform's. Never
  override them.
- What the module changes in the platform goes through a hook, attached in `init()` after `parent::init()` (see
  "Hooks" below).

## The name

The name of the module is the name of its directory: lowercase letters and digits only, and unique on the whole
server. It is also its translation category, the capability of its part of the administration, the prefix of
its settings and of what it keeps in a meta, and the first word of its addresses in the administration
(`/admin/<name>/<controller>/<action>`). It may not be the name of a module or a theme of the platform, and not
the name of a theme of this repository: pick one that says what it does for the client (`realizations`).

## Texts and translations

Every text goes through `Yii::t('<name>', '...')`, with the name of the module as the category, even a text the
platform has too ("Save changes"): a module relies on its own translations only. The translations are in
`messages/<language>/<name>.php`, for every language the site has, in the same change.

## A kind of page

A kind of page is a class in `common/models/` that extends `common\models\Page` (see `stubs/common/models/Page.php`)
and names its `type()` (prefixed with the module when the word is common), its `label()` and the `capability()`
a user needs for it, the name of the module. The values it has beyond the title, the slug and the content are
public properties, kept in the meta of the page; give them `rules()` like any attribute. Name it in
`pageTypes()`.

The page editor writes its content. The rest of it is edited on pages of the module in the administration, and
the page of a kind says where with `getEditUrl()` (`['/realizations/realization/update', 'id' => $this->id]`):

- `backend/controllers/RealizationController.php` extends `backend\controllers\Controller`, asks for the
  capability of the module in `behaviors()`, and has the list (`index`), `create`, `update` and `delete`.
- The form takes the sections other modules add to every page (the SEO settings):

  ```php
  $sections = new FormSections('page.form', $realization);
  $sections->load(Yii::$app->request->post());

  if ($realization->load(Yii::$app->request->post()) && $sections->apply() && $realization->save()) {
      ...
  }
  ```

  and its view renders them after its own cards with `SectionCards::widget(['form' => $form, 'sections' =>
  $sections])`, leaving out the slug when a section edits it (`<?php if (!$sections->edits('slug')): ?>`, with
  `SlugInput` for the field otherwise).
- The list has the search bar of every list (`backend\widgets\searchbar\SearchBar`, with a search model
  implementing `backend\models\SearchInterface`) and the actions of a row in an `ActionsColumn`, among them
  "Edit content", the page editor: `['/page/editor', 'id' => $realization->id]`.
- A page of the administration starts with `BackLink::widget()` where it has a way back, its title as
  `<h1 class="h3">` with the buttons next to it, and the fields in `card`s: Bootstrap 5 classes, as the
  administration loads Bootstrap.
- The item in the navigation of the administration is added on the `navigation` hook (see "Hooks").

## Pages on the site

A page of the site is an action of a controller in `frontend/controllers/`, extending `yii\web\Controller`,
reached by the addresses of `urlRules()`:

```php
public static function urlRules(): array
{
    return [
        '{realizations}'                    => 'realizations/realization/index',
        '{realizations}/<slug:[a-z0-9-]+>' => 'realizations/realization/view',
    ];
}

public static function paths(): array
{
    return [
        'realizations' => [
            'label'   => Yii::t('realizations', 'Realizations'),
            'default' => Yii::t('realizations', 'realizations', [], 'en'),
        ],
    ];
}
```

`{realizations}` is a word the site gets in its language and may change on Settings › Addresses; `paths()`
declares it, with the English word as `default`, written through `Yii::t()` so it is translated. The page of a
kind gives its address with `getUrl()`, built from the route
(`Yii::$app->frontendUrlManager->createAbsoluteUrl(['/realizations/realization/view', 'slug' => $this->slug])`),
never by hand.

- The action finds only published pages and answers 404 otherwise.
- It sets what the page shows as `$this->view->params['model']`, so the admin bar and the SEO tags know it.
- The view prints the content of the page editor with `PageContent::widget(['page' => $realization])`.
- A kind with a look of its own has a `preview` action too: the page whatever its status, for the users with
  the `pages` capability, with `$this->view->params['preview'] = true`, named by `getPreviewUrl()`.

## A taxonomy

A taxonomy (the kinds of realizations) is a class that extends `common\models\Term` and names its `type()`
(prefixed with the module: `realizations_kind`), `label()` and `labelPlural()`, and `hierarchy()` when its terms
are not a tree (`Hierarchy::None` for tags). Name it in `taxonomies()`. The administration of its terms is the
one of the platform: a controller in `backend/controllers/` that extends `backend\controllers\TermController`,
names the class (`public string $modelClass = Kind::class;`) and asks for the capability of the module.

A kind of page is put into terms with `setTerms()` and `getTerms()` (see
`stubs/common/behaviors/TermsBehavior.php`). A term that is not visible (`isVisible()`) is nowhere on the site.

## Settings

The settings of a module are settings of the site under its prefix. The form extends `common\models\SettingsForm`
with its values as public properties and `prefix()` returning `'<name>.'`. The page is
`backend/controllers/SettingsController.php`, an `index` action that fills the form with `loadSettings()` and
stores it with `save()`, asking for the `settings` capability; the module says so with `hasSettings()`, and
the platform lists the page under Settings. Elsewhere a setting is read with
`SiteSetting::get('<name>.<setting>')`.

## Fields on the forms of the platform

What a module keeps about a user, a page of any kind or a term of any taxonomy is a section of its form: a class
that extends `backend\models\FormSection`, added on the `user.form`, `page.form` or `term.form` hook (see
`stubs/backend/models/FormSection.php`). It keeps its values in the meta of the record under the prefix of the
module (`realizations.area`).

## Hooks

A module changes what the platform builds through `common\helpers\Hook`, in `init()`:

```php
public function init(): void
{
    parent::init();

    Hook::on('navigation', static function (array $navigation): array {
        $navigation['realizations'] = [
            'type'       => 'item',
            'parent'     => 'content',
            'label'      => Yii::t('realizations', 'Realizations'),
            'icon'       => 'post',
            'url'        => ['/realizations/realization/index'],
            'position'   => 15,
            'capability' => 'realizations',
        ];

        return $navigation;
    });
}
```

The hooks a module here may use:

- `navigation`: the items of the navigation of the administration (`content` is the group of the pages).
- `page.form`, `user.form`, `term.form`: the sections of those forms (see above).
- `menu.sources`: what an item of a menu may point to, e.g. the pages of its kind.
- `admin.bar`: the items of the admin bar on the site, given `$view->params['model']`.

## The picture and the docs

`image.svg` is a simple flat 128 by 128 drawing of what the module does on a rounded coloured square.
`docs/<language>.md` (`en.md` is the fallback) says what the module does for the site and its visitors, simply
and in a friendly voice, under "What it does" and "What it adds"; a bulleted list there is shown with a tick
before each item.

## What the module does not touch

The database schema, the configuration of the platform and the files of another module or theme. What it keeps
lives in the meta of the records of the platform, its pages, its terms and the settings of the site.
