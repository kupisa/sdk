<?php

declare(strict_types=1);

namespace modules;

use common\models\Page;
use common\models\Term;
use common\models\Visibility;
use frontend\blocks\Block;
use ReflectionClass;
use Yii;
use yii\helpers\Markdown;

/**
 * Base class of a module: a feature a site enables, such as a blog, that comes with its own pages of the
 * administration and of the site.
 *
 * Every module lives in its own directory under `modules/` named after the module, with a `Module` class in it,
 * e.g. `modules/blog/Module.php`; {@see Modules} finds it there, nothing has to be registered. A module made
 * for particular sites lives the same way under `sites/` (see `common\helpers\Directories`). The directory
 * mirrors `apps/`: the controllers and views of the module for the administration live in `backend/controllers`
 * and `backend/views`, those for the site in `frontend/controllers` and `frontend/views`, and the application
 * that loads the module looks in the directory named after it. The texts of the module are translated in its own
 * `messages` directory under the name of the module as the category, e.g. `Yii::t('blog', 'Posts')`.
 *
 * The module describes itself in the static methods below; the list of modules in the administration shows them.
 * A longer account of what the module does goes into `docs/<language>.md` (see {@see details()}). A module may
 * add content blocks to the pages of the site (see {@see blocks()}), bring kinds of pages of its own (see
 * {@see pageTypes()}), roles of its own (see {@see roles()}) and tools of the assistant of the administration (see
 * {@see tools()}).
 */
abstract class Module extends \yii\base\Module
{
    /**
     * The name of the module as shown to the user.
     */
    abstract public static function label(): string;

    /**
     * What the module does, in a sentence or two, as shown in the list of modules.
     */
    abstract public static function description(): string;

    /**
     * Whether the module defines what a site is or is enabled on top (see {@see ModuleType}). A module made for
     * particular sites, under `sites/`, is always a regular one.
     */
    abstract public static function type(): ModuleType;

    /**
     * The picture of the module in the administration (the lists of modules, its page): the `image.svg` next to
     * the class of the module, a simple 128 by 128 drawing of what the module does on a rounded coloured square
     * (see the pictures of the existing modules), inlined where it is shown. The picture of the platform
     * (modules/image.svg) for a module that brings none.
     */
    public static function image(): string
    {
    }

    /**
     * The names of the modules this one needs, e.g. `['shop']` for product reviews. The module is offered to a
     * site only while every one of them is enabled.
     *
     * @return list<string>
     */
    public static function requires(): array
    {
    }

    /**
     * Who the module is offered to: every site (or every site of its {@see regions()}), or, when it is private,
     * only the sites that were given it (see {@see Modules::grant()}) and those it names (see {@see sites()}).
     */
    public static function visibility(): Visibility
    {
    }

    /**
     * The ISO 3166-1 codes of the countries whose sites the module is offered to (see `Site::$region`), e.g.
     * `['CZ', 'SK', 'HU']` for a carrier that delivers there only; every site when empty. A site given the
     * module or named by it gets it wherever it is.
     *
     * @return list<string>
     */
    public static function regions(): array
    {
    }

    /**
     * The `uid` of each site the module is offered to without being given it, e.g. the sites of the client a
     * private module is made for.
     *
     * @return list<string>
     */
    public static function sites(): array
    {
    }

    /**
     * The content blocks the module adds to the pages of the site, by name, in the form of `params['blocks']` of
     * the frontend (see {@see Block}), e.g. `['review' => ReviewBlock::class]`. They are offered in the page
     * editor and shown on the site while the module is enabled; a block of a disabled module is skipped.
     *
     * @return array<string, class-string<Block>>
     */
    public static function blocks(): array
    {
    }

    /**
     * The kinds of pages the module brings, by type: the subclass of {@see Page} that loads a row of the `page`
     * table of that type, e.g. `['post' => Post::class]`. While the module is enabled, the platform creates a
     * page of that type as that class wherever it reads a page of any kind, e.g. in the page editor and the
     * preview on the site (see `Page::types()`).
     *
     * @return array<string, class-string<Page>>
     */
    public static function pageTypes(): array
    {
    }

    /**
     * The taxonomies the module brings, by type: the subclass of {@see Term} of each, e.g.
     * `['blog_category' => Category::class]`. While the module is enabled, the platform knows them wherever it
     * works on the terms of every taxonomy, e.g. when the content of a site travels (see `Term::types()` and
     * `common\content\Content`).
     *
     * @return array<string, class-string<Term>>
     */
    public static function taxonomies(): array
    {
    }

    /**
     * The roles the module brings, by name, each with its `label` and its `capabilities` (see
     * `common\components\Roles`), e.g. `['author' => ['label' => Yii::t('blog', 'Author'), 'capabilities' => ['blog']]]`
     * for a role that works on the part of the module only. A role of the platform named here gets the
     * capabilities in addition to its own (`['editor' => ['capabilities' => ['blog']]]`). The capabilities count
     * while the module is enabled; the names are known always, so a user with such a role stays labelled.
     *
     * @return array<string, array{label?: string, capabilities: list<string>}>
     */
    public static function roles(): array
    {
    }

    /**
     * The tools the module gives the assistant of the administration, as the classes of the tools (see
     * `backend\assistant\Tool`), e.g. `[ListEventsTool::class]`. The assistant may use them while the module is
     * enabled, on behalf of a user who has the capability each tool asks for.
     *
     * @return list<class-string<\backend\assistant\Tool>>
     */
    public static function tools(): array
    {
    }

    /**
     * The emails the module sends, as the classes of its notifications (see `common\mail\Notification`), e.g.
     * `[EventReminderNotification::class]`. The site edits them on Settings › Emails, and the module sends one with
     * `Yii::$app->notifier->send()`, while the module is enabled.
     *
     * @return list<class-string<\common\mail\Notification>>
     */
    public static function notifications(): array
    {
    }

    /**
     * The addresses the module answers to on the site, as the URL rules of the `urlManager`: the pattern of an
     * address and the route of the module it leads to, e.g. `['events' => 'example/event/index']`. They are in
     * place while the module is enabled, ahead of the addresses of the pages, and a link to the module is built
     * from the route (`Yii::$app->frontendUrlManager->createAbsoluteUrl(['/example/event/view', 'slug' => ...])`),
     * never by hand, so an address may change, even with the language of the site. The administration needs no
     * rules: a controller of a module is reached under `/admin/<module>/<controller>/<action>` as it is.
     *
     * Addresses that are not known ahead, like the ones the redirect module sends a visitor away from, are
     * caught by a rule of the module's own: the configuration of a class implementing `yii\web\UrlRuleInterface`
     * in place of the route, e.g. `['redirects' => ['class' => RedirectRule::class]]`.
     *
     * @return array<string, string|array{class: class-string<\yii\web\UrlRuleInterface>}>
     */
    public static function urlRules(): array
    {
    }

    /**
     * The words in the addresses of the module (`blog` and `category` in `/blog/category/news`), which a rule of
     * {@see urlRules()} names as `{name}`: each by its name, with the `label` the Addresses page of the
     * administration shows for it and its `default`, the word in English as a key of the module's own
     * translations, so a Serbian site gets `/blog/kategorija/news`:
     * `['category' => ['label' => Yii::t('blog', 'Category'), 'default' => Yii::t('blog', 'category', [], 'en')]]`.
     * The default is written through `Yii::t()` in the source language, so it stays the key itself and
     * `message/extract` sees it and keeps its translations (`common\helpers\Paths` translates it into the language
     * of the site later). The site may set any of them to a word of its own (see `Paths`).
     *
     * @return array<string, array{label: string, default: string}>
     */
    public static function paths(): array
    {
    }

    /**
     * What the module does in detail, as HTML, for the page of the module in the administration: the Markdown
     * file for the language of the administration in the `docs` directory of the module (e.g. `docs/sr-Latn.md`),
     * or the English one (`docs/en.md`) when the language has none. Null when the module comes without docs.
     * A picture the docs show (`![The banner](banner-en.svg)`, an SVG next to them) is put into the page as it
     * is, so the docs need no address on the web.
     */
    public static function details(): string|null
    {
    }

    /**
     * Whether the module has a settings page in the administration, at {@see settingsUrl()}: its
     * `backend/controllers/SettingsController.php`. A user who enables such a module is taken there right away.
     */
    public static function hasSettings(): bool
    {
    }

    /**
     * The URL of the settings page of the module, see {@see hasSettings()}.
     *
     * @return array{0: string}
     */
    public static function settingsUrl(): array
    {
    }

    /**
     * The name of the module: the name of its directory, which is also its id in the application.
     */
    public static function name(): string
    {
    }

    /**
     * The directory of the module.
     */
    public static function path(): string
    {
    }

    /**
     * Whether the current site may enable the module: a regular module the site was given or is named by, or
     * a public one made for the region of the site, whose required modules are all enabled.
     */
    public static function isAvailable(): bool
    {
    }

    /**
     * The name of the running application, `backend`, `frontend` or `console`: the directory of the module that
     * serves it, and what a module asks when it acts in one of them only (e.g. in the head of the site).
     */
    public static function application(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
    }
}
