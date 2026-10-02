<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TermsBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use common\helpers\Paths;
use frontend\blocks\Block;
use frontend\blocks\BlockGroup;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;

/**
 * A page of the current site, reached on the site under `/<slug>`.
 *
 * The content is a JSON document listing the blocks of the page, see {@see getBlocks()}. Blocks are rendered
 * on the site by `frontend\widgets\PageContent`. While the page is open in the page editor, every change goes
 * into the preview first; saving the page copies the preview into the content.
 *
 * A feature may bring its own kind of page, e.g. a blog its posts: a subclass that names its {@see type()} and
 * declares the values it has beyond the columns as public properties, kept in the meta by `MetaBehavior`. Each
 * class finds the pages of its own type only, so a blog post is never a page and has its own addresses. What
 * works on a page of any kind, like the page editor, reads it with {@see findAnyType()} and gets the class of
 * its kind (see {@see types()}).
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property string $type The name of the kind of page, see {@see type()}.
 * @property string $title
 * @property string $slug Unique within the site and the type. Created from the title when left empty.
 * @property string|null $content JSON: `{"blocks": [{"name": "paragraph", "values": {"text": "..."}, "settings": {"spacing": "sm"}}, ...]}`.
 * @property string|null $preview The content as it is being edited, in the same JSON; null until edited.
 * @property string|null $search_text The text of the blocks as plain text, for the search of the site (see {@see searchText()}).
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $status
 * @property int $created
 * @property int $updated
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 * @method Term[] getTerms(string $class) See `TermsBehavior::getTerms()`.
 * @method void setTerms(string $class, array $ids) See `TermsBehavior::setTerms()`.
 */
class Page extends SiteRecord
{
    public const STATUS_DRAFT = 0;
    public const STATUS_PUBLISHED = 10;

    /**
     * Slugs a page cannot have because the platform answers to these paths itself; the words in the addresses of
     * the site itself (`search`) are reserved too, see {@see rules()}.
     */
    public const RESERVED_SLUGS = ['admin', 'site', 'page', 'debug', 'gii', 'assets', 'css', 'js', 'images'];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
    }

    /**
     * The name of this kind of page, stored in `type`: `page` for a plain page. A subclass names its own.
     */
    public static function type(): string
    {
    }

    /**
     * The name of this kind of page as the administration shows it, translated: "Page". A subclass names its own.
     */
    public static function label(): string
    {
    }

    /**
     * The capability a user needs to work on pages of this kind (see `common\components\Roles`): `pages` for a
     * plain page; a kind brought by a module names the module.
     */
    public static function capability(): string
    {
    }

    /**
     * Whether a page of this kind has an address of its own on the site, where a visitor opens it: a page, a
     * post. A kind that is only shown inside other pages, like a form, says false: it has no address (see
     * {@see getUrl()}), no place in the sitemap and no settings for search engines.
     */
    public static function isPublic(): bool
    {
    }

    /**
     * Whether the pages of this kind travel in the demo content of a theme (see `themes\Demo`): a kind whose
     * page is wholly in its row, its content and the values of its meta, like a page or a form. A kind with
     * records of its own elsewhere (the variants of a product) or tied to the people of a site (the author of a
     * post) says false.
     */
    public static function inDemo(): bool
    {
    }

    /**
     * The kinds of pages of the site, the class of each by its type: the plain page and the kinds the enabled
     * modules bring (see `modules\Module::pageTypes()`).
     *
     * @return array<string, class-string<Page>>
     */
    public static function types(): array
    {
    }

    /**
     * The groups of blocks a page of this kind is built from, in the order the page editor lists them: the
     * generated block first, then every group of content for a page, a post and any kind that reads like one.
     * A kind built from blocks of its own, like a form, names its own group and the ones it shares (see
     * `frontend\blocks\BlockGroup`); every kind edited in the page editor takes the generated block.
     *
     * @return list<BlockGroup>
     */
    public static function blockGroups(): array
    {
    }

    /**
     * The blocks a page of this kind may be built from, by name: those of `params['blocks']` (the blocks of the
     * platform and of the enabled modules) in the groups of {@see blockGroups()}. The page editor offers these
     * and the assistant refuses any other.
     *
     * @return array<string, class-string<Block>>
     */
    public static function blockClasses(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * Finds the pages of the type of this class only. The condition sits in the `on` part of the query like
     * the site condition (see `SiteQuery`), so it survives a `where()` on the query.
     */
    public static function find(): ActiveQuery
    {
    }

    /**
     * Finds the pages of every kind, each as the class of its kind (see {@see instantiate()}): for what works on
     * any page, like the page editor and the preview, which are given the id of a page and nothing else.
     */
    public static function findAnyType(): ActiveQuery
    {
    }

    /**
     * {@inheritdoc}
     *
     * A row becomes the class of its type (see {@see types()}); a row of a kind the site does not have right
     * now, e.g. of a module that was disabled, becomes a plain page.
     */
    public static function instantiate($row): static
    {
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * The text of the blocks is kept for the search whenever the content changes.
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * The text of blocks as one plain text, a line per value, for the search of the site: every value a block
     * edits as text (the `text` and `textarea` controls, see `Block::controls()`, and the fields of those
     * kinds of the items of a repeater), without its HTML. A block the site does not have, and anything else a
     * block holds (code, an address, a picture), is left out.
     *
     * @param list<array{name: string, values: array}> $blocks
     */
    public static function searchText(array $blocks): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints(): array
    {
    }

    /**
     * The label of each status, keyed by status.
     *
     * @return array<int, string>
     */
    public static function statusLabels(): array
    {
    }

    public function isPublished(): bool
    {
    }

    /**
     * The address of the page on the site, e.g. `https://my-shop.kupisa.shop/about-us`. Only a published page
     * answers there. Built from the route by the `frontendUrlManager`, so it follows the URL rules of the site.
     * Null for a kind of page that is not public (see {@see isPublic()}): whatever links to a page leaves such
     * a page out.
     */
    public function getUrl(): string|null
    {
    }

    /**
     * Where the page is edited in the administration, as a route: the page editor leads back there. A kind of
     * page brought by a module names the page of its module.
     *
     * @return array{0: string, id: int}
     */
    public function getEditUrl(): array
    {
    }

    /**
     * The address of the page as it is being edited, on the site itself, whatever its status, for the frame of
     * the page editor; who works on the pages may open it (see `frontend\controllers\PageController::actionPreview()`).
     * A kind of page with a look of its own on the site names its own preview, rendered by its module in that
     * look (see the posts of the blog).
     */
    public function getPreviewUrl(): string
    {
    }

    /**
     * The blocks of the content as a list of `['name' => 'paragraph', 'values' => ['text' => '...']]`.
     *
     * Content that is not valid JSON, and entries without a name, yield no blocks.
     *
     * @return list<array{name: string, values: array, settings: array}>
     */
    public function getBlocks(): array
    {
    }

    /**
     * Replaces the content with these blocks, each `['name' => 'paragraph', 'values' => ['text' => '...']]`,
     * e.g. content the assistant wrote; stored when the page is saved. The preview is dropped, so the page
     * editor opens on the new content.
     *
     * @param array<array{name: string, values: array}> $blocks
     */
    public function setBlocks(array $blocks): void
    {
    }

    /**
     * The blocks of the preview: what the page editor holds right now, or the content while the page was
     * never edited.
     *
     * @return list<array{name: string, values: array, settings: array}>
     */
    public function getPreviewBlocks(): array
    {
    }
}
