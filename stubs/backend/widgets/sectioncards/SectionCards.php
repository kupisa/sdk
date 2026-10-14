<?php

declare(strict_types=1);

namespace backend\widgets\sectioncards;

use backend\models\FormSection;
use yii\base\Widget;
use yii\bootstrap5\ActiveForm;

/**
 * Renders the sections the modules added to a form (see `backend\models\FormSection`) as a card each, headed
 * by the name of the section and what it is about, with its fields inside. Placed in a form after the cards of
 * the record itself:
 *
 * ```php
 * <?= SectionCards::widget(['form' => $form, 'sections' => $sections]) ?>
 * ```
 */
class SectionCards extends Widget
{
    /**
     * The form the fields belong to.
     */
    public ActiveForm $form;

    /**
     * The sections to render, in their order.
     *
     * @var iterable<FormSection>
     */
    public iterable $sections = [];

    /**
     * {@inheritdoc}
     */
    public function run(): string
    {
    }
}
