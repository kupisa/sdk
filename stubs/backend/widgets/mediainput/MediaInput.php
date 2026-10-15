<?php

declare(strict_types=1);

namespace backend\widgets\mediainput;

use backend\assets\MediaAsset;
use common\models\Media;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * A form field that holds a file from the media library, e.g. the logo of the site, or several of them, e.g.
 * the pictures of a product.
 *
 * The id of the file goes in a hidden input. Next to it the field shows the chosen file and the buttons that
 * open the library or clear the field (see public/js/media/input.js). In a form:
 * `$form->field($model, 'logo')->widget(MediaInput::class, ['type' => 'image'])`.
 *
 * A field for several files (`'multiple' => true`) holds a list of ids, sent as `name[]` in the order shown:
 * the files stand in a row, each with the way to remove it, and are dragged into the order wanted; the first
 * one is the main one. An empty hidden input sends the field without any file, the way a checkbox list does.
 */
class MediaInput extends InputWidget
{
    /**
     * The kind of file the field takes: `image`, `document`, or empty for any file.
     */
    public string $type = '';

    /**
     * Whether the field holds several files, as a list of ids.
     */
    public bool $multiple = false;

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
