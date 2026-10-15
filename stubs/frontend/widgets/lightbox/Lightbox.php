<?php

declare(strict_types=1);

namespace frontend\widgets\lightbox;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Pictures of the site that open one at a time over the whole window: the gallery of a product, the gallery
 * block. A view wraps its pictures in the widget and marks them; the widget adds the lightbox they open.
 *
 * ```php
 * <?php Lightbox::begin(['options' => ['class' => 'block-gallery-grid']]) ?>
 *     <a href="<?= $image->url ?>" data-lightbox-item data-lightbox-open><?= $image->img(['thumbnail' => true]) ?></a>
 * <?php Lightbox::end() ?>
 * ```
 *
 * - `data-lightbox-item` marks a picture of the lightbox: the address of the large picture is its `href` (or
 *   `data-src`), its small picture the `<img>` inside it (or `data-thumb`), its alternative text the one of
 *   that `<img>` (or `data-alt`).
 * - `data-lightbox-open` marks what opens the lightbox with a click: on the picture it is (or is inside), or on
 *   the picture at the place it names (`data-lightbox-open="2"`, the first one 0), for a button that is not a
 *   picture itself, like the large picture of a product.
 *
 * The lightbox is a `<dialog>` over a dark overlay (the `--overlay` token), driven by public/js/src/lightbox.js: the picture as
 * large as the window allows, the small pictures centred under it, a click on one showing it, the buttons to
 * the previous and the next one at its sides (the arrow keys and a swipe too), and the way to close it in the
 * corner (Escape and a click beside the picture too). It opens, closes and changes pictures with a short
 * animation. Without the script a picture that is a link opens on its own.
 */
class Lightbox extends Widget
{
    /**
     * The HTML attributes of the element around the pictures, which lays them out (e.g. its `class`).
     *
     * @var array<string, mixed>
     */
    public array $options = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
