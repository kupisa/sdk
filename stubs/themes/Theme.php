<?php

declare(strict_types=1);

namespace themes;

use frontend\blocks\Block;
use modules\Modules;
use ReflectionClass;
use Yii;
use yii\helpers\Markdown;

/**
 * Base class of a theme: the look of a site, which a site picks on the Theme page of its administration.
 *
 * Every theme lives in its own directory under `themes/` named after the theme, with a `Theme` class in it,
 * e.g. `themes/example/Theme.php`; {@see Themes} finds it there, nothing has to be registered. A theme made
 * for particular sites lives the same way under `sites/` (see `common\helpers\Directories`). The site always
 * loads its own stylesheet, layout and blocks (see apps/frontend); a theme goes on top of them:
 *
 * - `public/` holds what the browser gets, published by the asset manager: `theme.css`, which gives the design
 *   tokens of the site other values (see apps/frontend/public/css/src/_tokens.scss) and styles what it needs
 *   beyond that (see {@see css()}), and `screenshots/`, the pictures of the theme shown before a site picks it
 *   (see {@see screenshots()}).
 * - `views/` and `blocks/` mirror apps/frontend/views and apps/frontend/blocks: a view put there replaces the
 *   one of the site at the same path (`views/layouts/main.php`, `blocks/hero/views/hero.php`), and `modules/`
 *   does the same for a view of a module on the site (`modules/blog/frontend/views/post/view.php`). A theme
 *   keeps only the views it changes (see {@see Themes::register()}).
 * - `blocks/<name>/` also holds the blocks of its own, built like those of the site and added with
 *   {@see blocks()}, and the header or the footer of its own, which replace those of the site with
 *   {@see areas()}.
 * - `docs/<language>.md` tells what the theme looks like in detail (see {@see details()}), and `messages/`
 *   holds its translations under the name of the theme as the category, e.g. `Yii::t('example', 'Banner')`.
 *
 * The theme describes itself in the static methods below; the Theme page of the administration shows them.
 */
abstract class Theme
{
    /**
     * The name of the theme as shown to the user.
     */
    abstract public static function label(): string;

    /**
     * What the theme looks like, in a sentence or two, as shown in the list of themes.
     */
    abstract public static function description(): string;

    /**
     * What the theme brings or is made for, in a word or two each (translated), shown with a tick under its
     * description on the Theme page, e.g. "Responsive", "Medical", "Call button".
     *
     * @return list<string>
     */
    public static function features(): array
    {
    }

    /**
     * Who the theme is offered to: every site, or only the sites that were given it (see {@see Themes::grant()}).
     */
    public static function visibility(): ThemeVisibility
    {
    }

    /**
     * The names of the modules the theme needs, e.g. `['shop']` for a theme drawn for a shop. The theme is
     * offered to a site only while every one of them is enabled.
     *
     * @return list<string>
     */
    public static function requires(): array
    {
    }

    /**
     * The content blocks the theme adds to the pages of the site, by name, in the form of `params['blocks']` of
     * the frontend (see {@see Block}), e.g. `['banner' => BannerBlock::class]` for `blocks/banner/BannerBlock.php`.
     * They are offered in the page editor and shown on the site while the theme is active.
     *
     * @return array<string, class-string<Block>>
     */
    public static function blocks(): array
    {
    }

    /**
     * The areas of the site the theme renders with a block of its own, by name, in the form of `params['areas']`
     * of the frontend (see apps/frontend/config/params.php), e.g. `['header' => Header::class]` for a header
     * with other controls than the one of the site. The block of the theme replaces the one of the site in
     * the page editor and on the site while the theme is active; an area not named here stays as it is.
     *
     * @return array<string, class-string<Block>>
     */
    public static function areas(): array
    {
    }

    /**
     * The Google Fonts the theme offers for the text and the headings of the site in the appearance settings of
     * the page editor (see `frontend\blocks\appearance\AppearanceBlock`), by family name: ten or so faces that
     * suit what the theme is for. A theme that names none offers the ones of the platform.
     *
     * @return list<string>
     */
    public static function fonts(): array
    {
    }

    /**
     * The stylesheets of the theme, as paths within its `public` directory, loaded on every page of the site after
     * the stylesheet of the site: `theme.css` when the theme has one.
     *
     * @return list<string>
     */
    public static function css(): array
    {
    }

    /**
     * The pictures of the theme, as paths within its `public` directory, in the order of their file names: the
     * files of `public/screenshots/`. The first one is the picture of the theme on the Theme page and on its card
     * among the available themes, 7 by 4 like that of every theme (1440 by 823, the top of its home page on a wide
     * screen), so the cards are of one size while every picture is shown whole.
     *
     * @return list<string>
     */
    public static function screenshots(): array
    {
    }

    /**
     * What the theme looks like in detail, as HTML, for the page of the theme in the administration: the
     * Markdown text in the language of the administration (`docs/sr-Latn.md`) or the English one (`docs/en.md`)
     * when the language has none. Null when the theme comes without docs.
     */
    public static function details(): string|null
    {
    }

    /**
     * The name of the theme: the name of its directory.
     */
    public static function name(): string
    {
    }

    /**
     * The directory of the theme.
     */
    public static function path(): string
    {
    }

    /**
     * The `public` directory of the theme: what the browser gets, published by the asset manager.
     */
    public static function publicPath(): string
    {
    }

    /**
     * Whether the current site may pick the theme: a public theme, or a private one the site was given, whose
     * required modules are all enabled.
     */
    public static function isAvailable(): bool
    {
    }
}
