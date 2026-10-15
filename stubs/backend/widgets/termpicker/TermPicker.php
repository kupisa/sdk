<?php

declare(strict_types=1);

namespace backend\widgets\termpicker;

use common\models\Term;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * The picker of the terms of a taxonomy on a form: the categories of a post, its tags, the categories of a
 * product.
 *
 * Every term is a tick, ticked when the record has it, sent as `<name>[]`; the controller gives the ticked ids
 * to the record (`$post->setTerms(Category::class, $request->post('categories', []))`). The terms of a
 * hierarchy are shown as a tree, the ones below a term in a nested list under it: picking a term picks the terms
 * above it, unpicking one unpicks the terms below it; a taxonomy without a hierarchy is one flat list. The list
 * keeps the height of about eight terms and scrolls, the search above it narrows the list as one types, and the
 * terms picked stand as chips above the search, each with the way to unpick it. Given the `addUrl` of the
 * taxonomy, a term searched for and not found is added right there (all of it in `public/js/src/term-picker.js`).
 * In a form:
 * `TermPicker::widget(['termClass' => Category::class, 'name' => 'categories', 'selected' => $post->getTerms(Category::class)])`.
 */
class TermPicker extends Widget
{
    /**
     * @var class-string<Term> The taxonomy whose terms are picked.
     */
    public string $termClass;

    /**
     * @var string The name the ticked ids are sent as (`categories` sends `categories[]`).
     */
    public string $name;

    /**
     * @var list<int|Term> The terms the record has, by id or as terms.
     */
    public array $selected = [];

    /**
     * @var array|string|null The `add` action of the controller of the taxonomy (`['/blog/tag/add']`, see
     * `backend\controllers\TermController::actionAdd()`). Given, the picker offers to add the term one searched for
     * and did not find, right there on the form.
     */
    public array|string|null $addUrl = null;

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
