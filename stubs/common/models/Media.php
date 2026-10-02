<?php

declare(strict_types=1);

namespace common\models;

use common\behaviors\TimestampBehavior;
use common\behaviors\UidBehavior;
use common\helpers\Image;
use common\jobs\OptimizeImage;
use Yii;
use yii\helpers\Html;

/**
 * A file of the current site, uploaded through the media library: an image or a document.
 *
 * The file lives in the storage (see `common\components\Storage`) under `path` and is served from `url`. The
 * address is stored with the file, so files keep working after a move to another storage. An image also has a
 * thumbnail next to it, the small copy the media library shows (see {@see getThumbnailPath()}).
 *
 * A value of a block that names a picture holds the id of the media, or {@see PLACEHOLDER} for a picture that
 * is not there yet: {@see image()} turns either into a media, the placeholder into a stand-in that is not in
 * the library (see {@see placeholder()}), and {@see img()} renders a media as an `<img>` tag.
 *
 * @property int $id
 * @property int $site_id
 * @property string $uid What the record is known by outside its site, see `UidBehavior`.
 * @property string $name The name shown in the library; the name of the uploaded file to begin with.
 * @property string $path Where the file is in the storage, e.g. `uploads/1/2026/09/Kj8s2xQ1pLm9aBc.jpg`.
 * @property string $url The address the file is served from.
 * @property string $type The MIME type, e.g. `image/jpeg`.
 * @property int $size In bytes.
 * @property int|null $width Of an image, in pixels.
 * @property int|null $height Of an image, in pixels.
 * @property string|null $alt The alternative text of an image.
 * @property string|null $description
 * @property int $created
 * @property int $updated
 */
class Media extends SiteRecord
{
    /**
     * Editing the words that describe a file in the media library; the file itself never changes.
     */
    public const SCENARIO_DESCRIBE = 'describe';

    /**
     * The longer side of the thumbnail of an image, in pixels.
     */
    public const THUMBNAIL_SIZE = 400;

    /**
     * The value of a picture that is not there yet, in place of the id of a media: the presets of a block
     * (see `frontend\blocks\Block::presets()`) put it, and the site shows a grey stand-in for it until the
     * user picks a picture, the way a theme of Shopify does.
     */
    public const string PLACEHOLDER = 'placeholder';

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
    public function scenarios(): array
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
     *
     * What the media library (public/js/media in the backend) gets to know about a file.
     */
    public function fields(): array
    {
    }

    /**
     * Puts a file into the media library from its contents: an upload, or a picture something made for the
     * site. The file goes into the storage under a path of its own, with a thumbnail next to it when it is an
     * image, and the row is saved. An image the queue can make lighter is handed to it (see {@see OptimizeImage}).
     *
     * @param string $name The name shown in the library.
     * @param string $type The MIME type, e.g. `image/png`.
     * @param string $extension The extension of the file in the storage, e.g. `png`.
     * @param string|null $uid The uid the file is known by outside the site, for one an import brings; null for a new one.
     */
    public static function create(string $name, string $contents, string $type, string $extension, string|null $uid = null): self
    {
    }

    /**
     * Puts other contents in place of the file, e.g. a lighter copy of an image, under a path of its own, with
     * a new thumbnail, and saves the row; the old files are removed once the row points to the new ones. The
     * words that describe the file stay.
     *
     * @param string $type The MIME type, e.g. `image/webp`.
     * @param string $extension The extension of the file in the storage, e.g. `webp`.
     */
    public function replace(string $contents, string $type, string $extension): void
    {
    }

    public function isImage(): bool
    {
    }

    /**
     * The picture a value of a block names: the image of the library with that id, the stand-in for
     * {@see PLACEHOLDER}, or null for anything else, an id that is not an image included.
     */
    public static function image(mixed $value): self|null
    {
    }

    /**
     * The stand-in for a picture that is not there yet: the grey picture of the platform
     * (apps/frontend/public/images/placeholder.svg), as a media that is not in the library and is never saved.
     * It is its own thumbnail.
     */
    public static function placeholder(): self
    {
    }

    /**
     * Whether this is the stand-in of {@see placeholder()}.
     */
    public function isPlaceholder(): bool
    {
    }

    /**
     * The image as an `<img>` tag, like `wp_get_attachment_image` of WordPress: its address, its width and
     * height, its alternative text and lazy loading, with the given HTML attributes on top of them (a `class`,
     * an `alt` or a `height` of the view's own wins). With `thumbnail` true, the thumbnail of the image is
     * shown instead, without a width and a height, as its own are smaller.
     */
    public function img(array $options = []): string
    {
    }

    /**
     * Where the thumbnail of an image is in the storage: next to the image, with `-thumb` before the extension.
     * Null for a file that is not an image.
     */
    public function getThumbnailPath(): string|null
    {
    }

    /**
     * The address of the thumbnail of an image, or null for a file that is not an image.
     */
    public function getThumbnailUrl(): string|null
    {
    }

    /**
     * {@inheritdoc}
     *
     * The files go with the row.
     */
    public function afterDelete(): void
    {
    }
}
