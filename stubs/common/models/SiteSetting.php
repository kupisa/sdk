<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;

/**
 * A setting of the current site: a name/value pair such as `email` => `shop@example.com`.
 *
 * Code reads settings with {@see get()} and writes them with {@see set()}. Forms in the backend group
 * several settings into one page (see `backend\models\GeneralSettingsForm`). A secret of the site (the secret
 * key of a payment provider) is kept encrypted with {@see setSecret()} and read with {@see getSecret()}, so the
 * database alone never gives it away.
 *
 * @property int $id
 * @property int $site_id
 * @property string $name
 * @property string|null $value
 * @property int $created
 * @property int $updated
 */
class SiteSetting extends SiteRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
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
     * Returns the value of a setting of the current site, or `$default` when the site does not have it.
     */
    public static function get(string $name, string|null $default = null): string|null
    {
    }

    /**
     * Stores the value of a setting of the current site, creating the setting on first use.
     */
    public static function set(string $name, string|null $value): void
    {
    }

    /**
     * Returns a secret of the current site, decrypted, or `$default` when the site does not have it or it cannot
     * be decrypted (it was encrypted with another key).
     */
    public static function getSecret(string $name, string|null $default = null): string|null
    {
    }

    /**
     * Stores a secret of the current site, encrypted with the key of the platform (`SECRETS_KEY` in `.env`);
     * an empty value is stored as it is.
     */
    public static function setSecret(string $name, string|null $value): void
    {
    }
}
