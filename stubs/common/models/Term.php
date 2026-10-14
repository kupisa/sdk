<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveQuery;

/**
 * An entry of a taxonomy of the current site: a category, a tag, an attribute, ... Records of any table are put
 * into terms through `common\behaviors\TermsBehavior`.
 *
 * Every taxonomy is a subclass that names its {@see type()}, usually brought by a module: the categories of the
 * blog, the tags of the blog, the categories of the shop. A class finds the terms of its own type only, the
 * slug is unique within a type, and a parent is always a term of the same type. A taxonomy without a hierarchy,
 * like tags, says so in {@see isHierarchical()} and takes no parent. The values a taxonomy needs beyond the
 * columns are its public properties, kept in the meta by `MetaBehavior`, like the values of a kind of page. The
 * administration of a taxonomy is a controller extending `backend\controllers\TermController`.
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property string $type The name of the taxonomy, see {@see type()}.
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug Unique within the site and the type. Created from the name when left empty.
 * @property int $position The place among the terms of the type, lowest first; terms of the same place come by name.
 * @property int $status Whether the term is on the site: `STATUS_VISIBLE` or `STATUS_HIDDEN`, see {@see isVisible()}.
 * @property string|null $description
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @property-read static|null $parent
 * @property-read static[] $children
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
abstract class Term extends SiteRecord
{
    /**
     * A hidden term is nowhere on the site: not in its lists, without a page, left out of the menus. What is in
     * it (a post, a product) stays where it is.
     */
    public const STATUS_HIDDEN = 0;
    public const STATUS_VISIBLE = 10;

    /**
     * The name of the taxonomy, stored in `type`, e.g. `blog_category`. Prefixed with the name of the module
     * that brings it, like a site setting, so the taxonomies of two modules never meet.
     */
    abstract public static function type(): string;

    /**
     * The name of the taxonomy for one term as shown to the user, e.g. `Category`.
     */
    abstract public static function label(): string;

    /**
     * The name of the taxonomy for its terms as shown to the user, e.g. `Categories`: the title of its list.
     */
    abstract public static function labelPlural(): string;

    /**
     * Whether a term of this taxonomy has a page of its own on the site, where a visitor opens it (a category, a
     * tag). A taxonomy that is only used on the site, never opened (the attributes of the shop, which the
     * filters of the catalogue offer), says false: its terms have no address (see {@see getUrl()}), so the form
     * shows no slug and the list no address; a hidden term of it is left out of where it is used.
     */
    public static function isPublic(): bool
    {
    }

    /**
     * The address of the term on the site, built from the URL rules of its module; a taxonomy that is public
     * gives it. Null for one that is not (see {@see isPublic()}).
     */
    public function getUrl(): string|null
    {
    }

    /**
     * How the terms of this taxonomy relate to one another (see {@see Hierarchy}): a tree unless the taxonomy
     * says otherwise. Tags say `Hierarchy::None`; the attributes of the shop and their values `Hierarchy::Levels`.
     */
    public static function hierarchy(): Hierarchy
    {
    }

    /**
     * Whether a term of this taxonomy may have a parent.
     */
    final public static function isHierarchical(): bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
    }

    /**
     * The taxonomies of the site, the class of each by its type: those the enabled modules bring (see
     * `modules\Module::taxonomies()`).
     *
     * @return array<string, class-string<Term>>
     */
    final public static function types(): array
    {
    }

    /**
     * The values of the taxonomy that hold the id of a record of the site, and what kind of record each names
     * (`media`, `page`, `menu`, `term` or `user`), by the name of the value: `['image' => 'media']`. A value may
     * hold one id or a list of them. The content of a site travels with them as references (see
     * `common\content\Content`).
     *
     * @return array<string, string>
     */
    public static function references(): array
    {
    }

    /**
     * {@inheritdoc}
     *
     * Finds the terms of the type of this class only. The condition sits in the `on` part of the query like
     * the site condition (see `SiteQuery`), so it survives a `where()` on the query.
     */
    public static function find(): ActiveQuery
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
     * A taxonomy without a hierarchy takes no parent, a term is never below itself, and in a taxonomy of two
     * levels a term goes right under one at the top, never under a value.
     */
    public function validateParent(string $attribute): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * {@inheritdoc}
     *
     * The terms below the deleted one move up to its parent, and the records that had it lose it. In a taxonomy
     * of two levels a value cannot stand at the top, so the values of a deleted term go with it, each deleted
     * on its own so the records that had them lose them too.
     */
    public function beforeDelete(): bool
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
     * The term this one is below, of the same type.
     */
    public function getParent(): ActiveQuery
    {
    }

    /**
     * The terms right below this one, in their order (see {@see order()}).
     */
    public function getChildren(): ActiveQuery
    {
    }

    /**
     * The order terms are listed in, for `orderBy()`: by position, and by name among the same position.
     *
     * @return array<string, int>
     */
    public static function order(): array
    {
    }

    /**
     * The terms above this one, from the root down, e.g. for a breadcrumb. Empty for a root term.
     *
     * @return list<static>
     */
    public function getAncestors(): array
    {
    }

    /**
     * The terms below this one at any depth, each followed by the ones below it, in their order: e.g. for the
     * posts of a category and of its sub-categories. Empty for a term with nothing below it.
     *
     * @return list<static>
     */
    public function getDescendants(): array
    {
    }

    /**
     * The name of the term after the names of the terms above it, e.g. `Sport / Football`: tells a term
     * apart from one of the same name elsewhere in the hierarchy.
     */
    public function getPath(): string
    {
    }

    /**
     * The terms that may be the parent of this one, their path by their id, for a drop-down list: every term
     * of the kind except the term itself and the ones below it; in a taxonomy of two levels only the terms at
     * the top.
     *
     * @return array<int, string>
     */
    public function parentOptions(): array
    {
    }

    /**
     * The statuses with their labels, by value.
     *
     * @return array<int, string>
     */
    public static function statusLabels(): array
    {
    }

    /**
     * Whether the term is on the site: visible itself, and below visible terms only. What the site lists or
     * links asks this, never the status alone.
     */
    public function isVisible(): bool
    {
    }

    /**
     * What the list of the administration shows in front of the name of the term, as a small circle: a colour
     * (`['color' => '#ff0000']`) or a picture of the media library (`['image' => $media]`), or null for nothing.
     * The values of an attribute of the shop shown as colours or pictures give theirs.
     *
     * @return array{color: string}|array{image: Media}|null
     */
    public function swatch(): array|null
    {
    }

    /**
     * Whether the term is at the top of its taxonomy, without a parent.
     */
    public function isRoot(): bool
    {
    }

    /**
     * Whether this term is the given one or somewhere below it.
     */
    public function isBelow(self $term): bool
    {
    }
}
