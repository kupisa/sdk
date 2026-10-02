<?php

declare(strict_types=1);

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Base class for every table that belongs to a site, i.e. has a `site_id` column.
 *
 * Queries only ever return rows of the current site (see {@see SiteQuery}), bulk updates and deletes stay within
 * it, and new rows are stamped with it. Application code never reads or writes `site_id` itself
 * (see `common\components\CurrentSite`).
 *
 * @property int $site_id
 */
abstract class SiteRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     *
     * @return SiteQuery<static>
     */
    public static function find(): ActiveQuery
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function updateAll($attributes, $condition = '', $params = []): int
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function updateAllCounters($counters, $condition = '', $params = []): int
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function deleteAll($condition = null, $params = []): int
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
    }

    /**
     * Condition that limits this table to the current site.
     */
    public static function currentSiteCondition(): array
    {
    }
}
