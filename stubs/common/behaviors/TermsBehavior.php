<?php

declare(strict_types=1);

namespace common\behaviors;

use common\models\SiteRecord;
use common\models\Term;
use common\models\TermRelation;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Puts a record (a page, a product, ...) into terms: the categories, tags or attributes of any taxonomy (see
 * {@see Term}). The record lists the terms of a kind it has with {@see getTerms()} and takes a new set of them
 * with {@see setTerms()}, stored when the record is saved. A query finds the records of a term with
 * `withTerm()` (see `common\models\SiteQuery`).
 *
 * The rows of the `term_relation` table that hold the terms name the record by its table, so the behavior
 * works on any table that has it, and it removes the rows when the record is deleted.
 *
 * @property SiteRecord $owner
 */
class TermsBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events(): array
    {
    }

    /**
     * The terms of a kind the record has, in their order, e.g. `$post->getTerms(Category::class)`: the ones given
     * to {@see setTerms()} while the record is not saved yet (so a form shows what was picked, and what was
     * picked can be checked), the stored ones otherwise.
     *
     * @template T of Term
     * @param class-string<T> $class
     * @return list<T>
     */
    public function getTerms(string $class): array
    {
    }

    /**
     * The terms of a kind the record is to have from now on, by id, in place of the ones of that kind it has;
     * the terms of every other kind stay. An id that is not a term of the kind, or of another site, is skipped.
     * Stored when the record is saved.
     *
     * @param class-string<Term> $class
     * @param list<int|string> $ids
     */
    public function setTerms(string $class, array $ids): void
    {
    }

    /**
     * Stores the terms given to {@see setTerms()} now that the record has an id.
     */
    public function storeTerms(): void
    {
    }

    /**
     * Removes the terms of the record once the record is gone.
     */
    public function dropTerms(): void
    {
    }
}
