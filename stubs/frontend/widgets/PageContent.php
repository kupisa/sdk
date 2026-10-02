<?php

declare(strict_types=1);

namespace frontend\widgets;

use common\models\Page;
use frontend\assets\PagePreviewAsset;
use frontend\blocks\Block;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Renders the content of a page: each block in order, by the {@see Block} class registered under the block name
 * in `params['blocks']`, inside a root element that carries the settings of the block (see {@see rootOptions()}).
 * Blocks with an unknown name are skipped, and so are values the block class does not
 * declare (a field the block no longer has), so old content never breaks the page.
 */
class PageContent extends Widget
{
    public Page $page;

    /**
     * Whether the content is rendered for the page editor (see {@see run()}): as the view says, unless given.
     * A block that renders the content of another page inside its own (the form block shows a form) says
     * false, so the blocks of that page are not marked as blocks of the one being edited.
     */
    public bool|null $preview = null;

    /**
     * {@inheritdoc}
     *
     * In the preview for the frame of the page editor (the preview action of the controller sets the view
     * parameter `preview`, see `frontend\controllers\PageController::actionPreview()`), the content is rendered as
     * it is being edited instead of the saved content, every block is wrapped in an element marked with its
     * place, so the editor can point at it, and the script and style of the preview are loaded (see
     * public/js/page-preview.js). The header and the footer read the same parameter (see {@see AreaContent}),
     * so a view renders the same way on the site and in the editor.
     */
    public function run(): string
    {
    }
}
