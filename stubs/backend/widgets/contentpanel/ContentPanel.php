<?php

declare(strict_types=1);

namespace backend\widgets\contentpanel;

use common\models\Page;
use frontend\blocks\Block;
use Yii;
use yii\base\Widget;

/**
 * The panel of the content of a page (of any kind, e.g. a post) in its form, under the "Content" label: that
 * the content is edited in the visual editor, what the page is built of, when it was last saved and whether
 * the page editor holds changes that are not saved yet, with the button that opens the editor. In a form: `ContentPanel::widget(['page' => $page])`, once the page is saved.
 */
class ContentPanel extends Widget
{
    public Page $page;

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
