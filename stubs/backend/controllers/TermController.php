<?php

declare(strict_types=1);

namespace backend\controllers;

use backend\models\FormSections;
use backend\models\TermSearch;
use common\models\Term;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * The administration of a taxonomy (see {@see Term}): the list, the form and the deletion of the terms of one
 * kind. A module gets it for a taxonomy of its own by extending this class and naming the kind:
 *
 * ```php
 * class CategoryController extends TermController
 * {
 *     public string $modelClass = Category::class;
 * }
 * ```
 *
 * Like any controller of a module, it asks for the capability of the module in its `behaviors()` (see
 * `backend\controllers\Controller`). The pages are the ones under `views/term` of the backend. The values the kind declares beyond the columns
 * (its public properties) are shown in the form when the module puts their fields in `_fields.php` in the
 * view directory of its controller (`backend/views/category/_fields.php`), with `$form` and `$term` at hand.
 */
abstract class TermController extends Controller
{
    /**
     * The class of the kind of term this controller administers.
     *
     * @var class-string<Term>
     */
    public string $modelClass;

    /**
     * {@inheritdoc}
     */
    public function init(): void
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
     *
     * The pages of every taxonomy are the ones of the backend.
     */
    public function getViewPath(): string
    {
    }

    /**
     * The view with the fields of the values the kind declares beyond the columns, when the module has one:
     * `_fields.php` in the view directory the controller would have by default.
     */
    public function fieldsView(): string|null
    {
    }

    /**
     * The list of the terms, as the hierarchy of the kind has it (see `backend\models\TermSearch`); in a taxonomy
     * of two levels, `parent` opens the values of a term at the top.
     */
    public function actionIndex(): string
    {
    }

    /**
     * A new term; in a taxonomy of two levels, `parent` presets the term at the top a value goes under.
     */
    public function actionCreate(): string|Response
    {
    }

    public function actionUpdate(int $id): string|Response
    {
    }

    /**
     * Adds a term by its name alone, for the picker of the terms on a form (`backend\widgets\termpicker\TermPicker`):
     * a tag thought of while writing a post. Answers with the `id` and `name` of the new term, or with the
     * errors when the name is not valid; a term of a hierarchy is added at the root, or under the term sent as
     * `parent_id` (a value of an attribute of the shop under its attribute), and the form of the term moves it.
     */
    public function actionAdd(): Response
    {
    }

    public function actionDelete(int $id): Response
    {
    }
}
