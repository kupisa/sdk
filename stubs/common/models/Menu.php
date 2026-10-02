<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use common\helpers\Hook;
use Yii;
use yii\db\ActiveQuery;

/**
 * A menu of the current site, or an item of one: both are rows of the `menu` table.
 *
 * A row of type `menu` is a menu, named by its label, the way the header or the footer of the site asks for
 * it (see `frontend\widgets\Nav`). Every other row is an item: it belongs to the menu named by `menu_id`,
 * sits below the menu or below another item (`parent_id`) in the order of its `position`, and points where
 * its type says: to an address given by hand (`url`), to a page of the site of any kind by its id (`page`), or
 * to what a module offers through a source of its own (see {@see sources()}). What an item has beyond the
 * columns lives in the meta (see `MetaBehavior`), like {@see $new_tab}.
 *
 * The menus are managed under Appearance → Menus in the administration, which edits a whole menu at once
 * (see {@see saveItems()}).
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property int|null $menu_id The menu an item belongs to; null for a menu.
 * @property int|null $parent_id The menu or the item an item sits below; null for a menu.
 * @property string $type `menu`, or the type of an item: `url`, `page`, or the type of a source (see {@see sources()}).
 * @property string $label The name of a menu, or the text of an item.
 * @property string|null $target What an item points to: an address, the id of a page, or the target of an
 * item of a source. Null for a menu.
 * @property int $position
 * @property array|null $meta
 * @property int $created
 * @property int $updated
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
class Menu extends SiteRecord
{
    public const TYPE_MENU = 'menu';
    public const TYPE_PAGE = 'page';
    public const TYPE_URL = 'url';

    /**
     * Whether the link of an item opens in a new tab. Kept in the meta.
     */
    public bool $new_tab = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
    }

    /**
     * The menus of the site, by name.
     */
    public static function findMenus(): ActiveQuery
    {
    }

    /**
     * What the items of a menu may point to, grouped as the menu editor offers them: the pages of the site,
     * and whatever the enabled modules add through the `menu.sources` hook (the posts and the categories of
     * the blog, ...). A source is
     *
     * ```php
     * [
     *     'type' => 'blog_category',                   // the type its items are stored with
     *     'label' => Yii::t('blog', 'Categories'),     // the group in the editor
     *     'name' => Yii::t('blog', 'Category'),        // what one item is called
     *     'items' => [['target' => '3', 'label' => 'News', 'published' => true], ...],
     *     'url' => static fn (string $target): string|null => ...,   // where an item points, null for nowhere
     * ]
     * ```
     *
     * A source of type `page` needs no `url`: a page of any kind knows its own address. Items of type `url`
     * belong to no source: they are the links given by hand.
     *
     * @return list<array{type: string, label: string, name: string, items: list<array{target: string, label: string, published: bool}>, url?: callable(string): (string|null)}>
     */
    public static function sources(): array
    {
    }

    /**
     * The types an item may have: `url`, `page`, and the type of every source.
     *
     * @return list<string>
     */
    public static function itemTypes(): array
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
     * A menu stands on its own; an item belongs to a menu, sits below the menu or one of its items, and points
     * to a page that exists or to an address.
     */
    public function validateItem(string $attribute): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    public function isMenu(): bool
    {
    }

    /**
     * The menu an item belongs to.
     */
    public function getMenu(): ActiveQuery
    {
    }

    /**
     * The items right below this menu or item, in their order.
     */
    public function getItems(): ActiveQuery
    {
    }

    /**
     * The page an item of type `page` points to, of any kind; null for any other item, or when the page is gone.
     */
    public function getPage(): Page|null
    {
    }

    /**
     * The address an item points to: the address as it was given, the address of its page while the page is
     * published, or what the source of its type says. Null when there is nothing to point to, so the site
     * leaves the item out.
     */
    public function getUrl(): string|null
    {
    }

    /**
     * The whole menu in one query: its items at the top, each with the items below it (see {@see getChildren()}),
     * all in their order.
     *
     * @return list<Menu>
     */
    public function tree(): array
    {
    }

    /**
     * The items below this one as {@see tree()} loaded them.
     *
     * @return list<Menu>
     */
    public function getChildren(): array
    {
    }

    /**
     * Replaces the items of this menu with the given ones, all at once, as the administration edits a menu:
     * a list of items, each `['id' => <id or null>, 'label' => ..., 'type' => ..., 'target' => ...,
     * 'new_tab' => bool, 'children' => [...]]`. An item with the id of an item of this menu is updated, one
     * without is created, and the items of the menu that are not in the list are deleted. Nothing is stored
     * when an item is not valid; the errors are in {@see getItemErrors()}.
     *
     * @param array<mixed> $items
     */
    public function saveItems(array $items): bool
    {
    }

    /**
     * @return array<string, array<string, list<string>>>
     */
    public function getItemErrors(): array
    {
    }
}
