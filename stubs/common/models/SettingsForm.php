<?php

declare(strict_types=1);

namespace common\models;

use yii\base\Model;

/**
 * Base class of a form that edits settings of the current site: every attribute of the form is stored as a
 * {@see SiteSetting} of the same name, under the prefix of the form.
 *
 * A form declares its attributes as public properties with their defaults and validates them in `rules()` as any
 * model; a controller fills it with {@see loadSettings()} and stores it with {@see save()}. The general settings
 * of the site have no prefix (see `backend\models\GeneralSettingsForm`); a module prefixes its settings with
 * its name, e.g. `example.`, so they never clash with those of another module. The attributes named in
 * {@see secrets()} are kept encrypted (see `SiteSetting::setSecret()`).
 */
abstract class SettingsForm extends Model
{
    /**
     * The text put before the name of every attribute to form the name of its setting, e.g. `example.`.
     */
    public function prefix(): string
    {
    }

    /**
     * The attributes that hold a secret of the site (the secret key of a payment provider): stored encrypted and
     * read decrypted, so the database alone never gives them away.
     *
     * @return list<string>
     */
    public function secrets(): array
    {
    }

    /**
     * Fills the form with the stored settings of the current site.
     */
    public function loadSettings(): void
    {
    }

    /**
     * Validates the form and stores its values as settings of the current site.
     */
    public function save(): bool
    {
    }
}
