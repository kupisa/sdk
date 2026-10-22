<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * An entry of a simple listing of a module of the current site, found by one key: a redirect (by the address it
 * starts from), a coupon (by its code), ... All of them live in the one `record` table, so a module needs no
 * table of its own for a listing like that.
 *
 * Every kind of record is a subclass that names its {@see type()}, brought by a module: like a kind of page or a
 * taxonomy, a class finds the records of its own type only, and the key is unique within a type. The values a
 * kind needs beyond the columns are its public properties, kept in the meta by `MetaBehavior`.
 *
 * A regular module keeps all of its data here and has no table of its own: what a record points to is an id in
 * its meta, and a kind whose records are found by a day starts its key with it, so the index on the key finds
 * them (an appointment: `2026-10-21 09:00 3f2b8c1e`). Only a core module may have tables of its own.
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property string $type The name of the kind, see {@see type()}.
 * @property string $key What a record is found by, unique within the site and the type.
 * @property string|null $name What the record is called in a list, when its key does not say it.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
abstract class Record extends SiteRecord
{
    /**
     * The name of the kind, stored in `type`, e.g. `redirect`. Prefixed with the name of the module that brings
     * it when the module brings several, like a taxonomy (`shop_coupon`), so the kinds of two modules never meet.
     */
    abstract public static function type(): string;

    /**
     * The name of the kind for one record as shown to the user, e.g. `Redirect`.
     */
    abstract public static function label(): string;

    /**
     * The name of the kind for its records as shown to the user, e.g. `Redirects`: the title of its list.
     */
    abstract public static function labelPlural(): string;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
    }

    /**
     * {@inheritdoc}
     *
     * Finds the records of the type of this class only. The condition sits in the `on` part of the query like
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
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }
}
