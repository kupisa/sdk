<?php

declare(strict_types=1);

namespace backend\models;

use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * A section a module adds to the form of a record of the platform, a user, a page of any kind or a term of
 * any taxonomy: a card with the fields of what the module keeps about the record, e.g. the biography of an
 * author or the title a page has for search engines. The module adds it through the hook of the form
 * (`user.form`, `page.form`, `term.form`, see {@see FormSections}), so it is on the form of every such record,
 * new or existing, while the module is enabled:
 *
 * ```php
 * Hook::on('user.form', static function (array $sections, User $user): array {
 *     $sections[] = new AuthorSection($user);
 *
 *     return $sections;
 * });
 * ```
 *
 * A section is a model like any other: its public properties are its fields, {@see rules()} validates them.
 * The form loads and validates every section with its own fields and, when all is well, lets each section
 * put its values on the record before the record is stored (see {@see apply()}). The section reads its values
 * from the record in {@see init()}, usually from its meta.
 */
abstract class FormSection extends Model
{
    public function __construct(public readonly ActiveRecord $record, array $config = [])
    {
    }

    /**
     * The name of the section, the heading of its card, e.g. `Yii::t('blog', 'Author')`.
     */
    abstract public function label(): string;

    /**
     * A short sentence under the heading of the card on what the section is about, e.g. "The biography of the
     * author, shown on the site next to their posts."
     */
    abstract public function description(): string;

    /**
     * The view that renders the fields of the section inside its card, e.g. `@modules/blog/backend/views/user/_author`.
     * It receives the ActiveForm as `$activeForm` and the section as `$section`.
     */
    abstract public function view(): string;

    /**
     * The attributes of the record the section edits among its own fields, which the form of the record then leaves
     * out, e.g. `['slug']` for the SEO section, which edits the address next to the search result it makes. Their
     * fields stay the fields of the record (`Page[slug]`), so they are loaded, checked and stored with it as before.
     * None by default.
     *
     * @return list<string>
     */
    public function recordAttributes(): array
    {
    }

    /**
     * Puts the values of the section on the record, usually in the meta (`$this->record->setMeta('blog.bio', $this->bio)`).
     * The form stores the record afterwards.
     */
    abstract public function apply(): void;
}
