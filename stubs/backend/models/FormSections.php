<?php

declare(strict_types=1);

namespace backend\models;

use ArrayIterator;
use common\helpers\Hook;
use IteratorAggregate;
use Traversable;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * The sections the modules added to the form of a record (see {@see FormSection}), gathered through the hook
 * of the form: `user.form` for a user, `page.form` for a page of any kind, `term.form` for a term of any
 * taxonomy, each given the record. A controller loads them next to the record and applies them before the
 * record is stored; the form renders them with `backend\widgets\sectioncards\SectionCards`:
 *
 * ```php
 * $sections = new FormSections('page.form', $page);
 * $sections->load(Yii::$app->request->post());
 * if ($page->load(Yii::$app->request->post()) && $sections->apply() && $page->save()) {
 *     ...
 * }
 * ```
 *
 * @implements IteratorAggregate<int, FormSection>
 */
final class FormSections implements IteratorAggregate
{
    public function __construct(string $hook, ActiveRecord $record)
    {
    }

    /**
     * @return list<FormSection>
     */
    public function all(): array
    {
    }

    /**
     * Whether a section edits the attribute of the record among its own fields (see
     * `FormSection::recordAttributes()`), so the form of the record leaves its field out:
     * `<?php if (!$sections->edits('slug')): ?>`.
     */
    public function edits(string $attribute): bool
    {
    }

    /**
     * Loads every section from the data sent, each from the data under its own form name.
     */
    public function load(array $data): void
    {
    }

    /**
     * Validates the sections and, when every one is valid, puts their values on the record. False with the
     * errors left on the sections, for the form to show.
     */
    public function apply(): bool
    {
    }

    public function getIterator(): Traversable
    {
    }
}
