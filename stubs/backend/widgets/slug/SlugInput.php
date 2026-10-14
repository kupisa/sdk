<?php

declare(strict_types=1);

namespace backend\widgets\slug;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * The field of the slug of anything that has an address on the site: a page, a post, a category.
 *
 * The field is always open for editing: the input for the slug sits between the parts of the address around
 * it, e.g. `https://my-shop.kupisa.shop/` before it, so the whole address is read at a glance; next to it a
 * button opens the address on the site in a new tab (greyed out while the record has no slug yet). The label
 * and the hint come from the model like for any field. In a form:
 * `$form->field($page, 'slug')->widget(SlugInput::class)`.
 *
 * The address comes from the model itself: the widget gives a copy of it a stand-in slug and asks its
 * `getUrl()`, so it follows the URL rules of the site without knowing them. A model without `getUrl()`
 * gets a plain text input.
 */
class SlugInput extends InputWidget
{
    /**
     * Whether the button that opens the address on the site stands next to the field; the SEO section, which shows
     * the address in the search result next to it, leaves it out.
     */
    public bool $openLink = true;

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

    /**
     * The parts of the address of a model before and after its slug, e.g. `['https://my-shop.kupisa.shop/', '']`
     * for a page or `['https://my-shop.kupisa.shop/blog/category/', '']` for a category of the blog, so the
     * address can be put together again for another slug (the preview of a search result of the SEO module does,
     * as the slug is typed). Null when the model has no address, or its address does not hold the slug.
     *
     * @return array{0: string, 1: string}|null
     */
    public static function addressAround(Model $model, string $attribute = 'slug'): array|null
    {
    }
}
