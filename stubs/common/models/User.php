<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;
use yii\web\IdentityInterface;

/**
 * User model. Users belong to a site; the email address is unique within the site.
 *
 * The role of the user (`admin`, `editor`, `user`, or one a module brings) says what the user may do in the
 * administration, see `common\components\Roles`.
 *
 * The picture of the user is kept in the meta as the property {@see $avatar}; a module keeps what it knows about
 * a user beyond the columns there too, under a key prefixed with its name (`shop.phone`), see `MetaBehavior`.
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property string|null $account_id What the Kupiša account of the user is known by to the accounts of the platform, for a user who logs in with it (see `common\components\Accounts`).
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $login_token The token of a link that logs the user in, once and for a short while.
 * @property string $verification_token
 * @property string $email
 * @property string|null $pending_email The email address the user changed theirs to and has not confirmed yet.
 * @property string|null $pending_email_token The token of the link that confirms it, valid as long as a password reset token.
 * @property string $name The full name of the user; empty when unknown.
 * @property string $username The name of the user in an address on the site (`/blog/author/<username>`), unique
 * within the site. Made from the name, or from the email address when the name is empty, when left empty.
 * @property string $auth_key
 * @property int $status
 * @property string $role The name of the role, see `common\components\Roles`.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`.
 * @property int $created
 * @property int $updated
 * @property string $password write-only password
 *
 * @method mixed getMeta(string $name, mixed $default = null) See `MetaBehavior::getMeta()`.
 * @method void setMeta(string $name, mixed $value) See `MetaBehavior::setMeta()`.
 */
class User extends SiteRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    /** The roles of the platform, see `common\components\Roles`; a module may bring more. */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_USER = 'user';

    /**
     * The id of the picture of the user in the media library (`common\models\Media`), or null without one.
     */
    public int|null $avatar = null;

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
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * The statuses a user is given by name; a deleted user is not offered.
     *
     * @return array<int, string>
     */
    public static function statusLabels(): array
    {
    }

    /**
     * The roles by name, those of the platform and those of the modules (see `common\components\Roles`).
     *
     * @return array<string, string>
     */
    public static function roleLabels(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): User|null
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): never
    {
    }

    /**
     * Finds an active user by email address
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): User|null
    {
    }

    /**
     * The active user whose link confirming a new email address carries the token, while the link is still
     * valid (the token is made like a password reset token and lasts as long).
     */
    public static function findByPendingEmailToken(string $token): User|null
    {
    }

    /**
     * Keeps a new email address as pending until the link that confirms it is opened (see
     * {@see confirmPendingEmail()}); null forgets a pending one.
     */
    public function setPendingEmail(string|null $email): void
    {
    }

    /**
     * Makes the pending email address the email address of the user and forgets the link.
     */
    public function confirmPendingEmail(): void
    {
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): User|null
    {
    }

    /**
     * The active user a login link with the token logs in, while the link is still good (see
     * {@see generateLoginToken()}).
     */
    public static function findByLoginToken(string $token): User|null
    {
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken(string $token): User|null
    {
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string|null $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid(string|null $token): bool
    {
    }

    /**
     * What the user is called where they are shown: the full name, or the email address when there is none (a
     * user who signed up gave only an email address).
     */
    public function getDisplayName(): string
    {
    }

    /**
     * The picture of the user from the media library, or null when the user has none (or it was deleted).
     */
    public function getAvatarMedia(): Media|null
    {
    }

    /**
     * Whether the user is an admin, who may do everything in the administration.
     */
    public function isAdmin(): bool
    {
    }

    /**
     * Whether the role of the user opens the administration (see `common\components\Roles::administrative()`):
     * the site shows such a user the admin bar.
     */
    public function isAdministrative(): bool
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
    }

    /**
     * Whether the password is the one of the user. A user without a password (a customer of the shop who did not
     * create an account) has an empty hash, and no password is theirs until they set one.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
    }

    /**
     * Whether the user has a password at all: one made by a checkout without an account has none until they set
     * one (through the password reset, or on their account page without giving the current one).
     */
    public function hasPassword(): bool
    {
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken(): void
    {
    }

    /**
     * Makes the token of a link that logs the user in without a password (`/login/<token>` on the site, see
     * `frontend\controllers\SiteController::actionTokenLogin()`). The link works once, and only for
     * `params['user.loginTokenExpire']` seconds.
     */
    public function generateLoginToken(): void
    {
    }

    /**
     * Forgets the login link, so it does not work again.
     */
    public function removeLoginToken(): void
    {
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken(): void
    {
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
    }
}
