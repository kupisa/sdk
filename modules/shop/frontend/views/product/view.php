<?php

/**
 * One product on the site: its gallery on the left (the main picture large, the others small under it, each shown
 * large when clicked; a click on the large one, which says so with the magnifier in its corner, opens them all in
 * the lightbox of the site, see `frontend\widgets\lightbox\Lightbox`, on the one shown), its tags shown as badges, its title, what the modules add under it (see Product::summaryExtras()), price, availability, short description (HTML, cleaned
 * as it was saved), the form that puts it in the cart (with the options of the product to choose the variant by,
 * see `_option.php`, and the quantities the product is ordered in while they are not any: its minimum and its
 * step, which the quantity with − and + next to it keeps to) and its SKU on the right, then its sections (its
 * description from the page editor, its specifications and what the modules add; in tabs or one under the other,
 * see `_sections-tabs.php` and `_sections-stacked.php`) and the products offered with it ("You may also like",
 * the cards of the catalogue). Every variant goes along as JSON, so the script of the shop
 * (`public/js/shop.js`) shows the one the visitor picks: the elements it changes are named with `data-`
 * attributes. A template a theme replaces: everything shown comes from the controller (see
 * ProductController::renderProduct()).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var modules\shop\common\models\Product $product */
/** @var modules\shop\common\models\Variant $variant The variant shown: the one asked for, or the first that can be bought. */
/** @var array $options The options of the product, each its `attribute` with the `values` to choose from and the `view` that shows it. */
/** @var int[] $selectedValueIds The ids of the values of the variant shown. */
/** @var array $variants Every variant of the product as the script needs it (see ProductController::describeVariant()). */
/** @var common\models\Media[] $images The pictures of the gallery, the main one first; the stand-in when there are none. */
/** @var array $sections What there is to read about the product, each section with its `name`, `label` and `content` (see ProductController::sections()). */
/** @var string $sectionsView The template that shows the sections: `_sections-tabs` or `_sections-stacked`. */
/** @var bool $sectionTitles Whether the sections stand under their titles; false for a section alone. */
/** @var bool $canBuy Whether the form that puts the product in the cart is shown. */
/** @var bool $preview Whether the page is the preview of the editor, where the form is shown but cannot be sent. */
/** @var modules\shop\common\models\Product[] $related The products offered under it, see Product::getRelated(). */
/** @var string $taxNote The words under the price that say it has no VAT in it ("excl. VAT"); empty for a price with VAT. */

use frontend\widgets\lightbox\Lightbox;
use modules\shop\frontend\assets\ShopAsset;
use yii\helpers\Html;
use yii\helpers\Json;

ShopAsset::register($this);
?>
<article class="shop-product container mt-4 mb-5" data-shop-product data-text-unavailable="<?= Yii::t('shop', 'Unavailable') ?>">
    <div class="row">
        <div class="col-12 col-md-6">
            <?php Lightbox::begin(['options' => ['class' => 'shop-gallery', 'data-gallery' => true]]) ?>
                <button type="button" class="shop-gallery-main" data-lightbox-open="0" aria-label="<?= Yii::t('shop', 'View the pictures') ?>">
                    <?= $images[0]->img(['alt' => $images[0]->alt ?: $product->title, 'loading' => 'eager', 'data-gallery-main' => true]) ?>
                    <span class="shop-gallery-zoom">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="M15.5 15.5 20 20M10.5 8v5M8 10.5h5"/></svg>
                    </span>
                </button>
                <div class="shop-gallery-thumbs">
                    <?php foreach ($images as $index => $image): ?>
                        <?= Html::button($image->img(['thumbnail' => true, 'alt' => '']), [
                            'class'              => $index === 0 ? 'active' : '',
                            'data-gallery-thumb' => true,
                            'data-lightbox-item' => true,
                            'data-src'           => $image->url,
                            'data-alt'           => $image->alt ?: $product->title,
                            'aria-label'         => Yii::t('shop', 'Picture {number}', ['number' => $index + 1]),
                        ]) ?>
                    <?php endforeach ?>
                </div>
            <?php Lightbox::end() ?>
        </div>

        <div class="col-12 col-md-6">
            <?php if ($product->getBadges() !== []): ?>
                <div class="shop-product-badges">
                    <?php foreach ($product->getBadges() as $tag): ?>
                        <span class="badge shop-badge" style="<?= Html::encode($tag->badgeStyle()) ?>"><?= Html::encode($tag->name) ?></span>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            <h1><?= Html::encode($product->title) ?></h1>
            <?= $product->summaryExtras() ?>
            <div class="mb-3">
                <div class="shop-product-price" data-product-price>
                    <?= $this->render('_price', ['variant' => $variant, 'from' => false]) ?>
                </div>
                <?php if ($taxNote !== ''): ?>
                    <p class="shop-price-note"><?= Html::encode($taxNote) ?></p>
                <?php endif ?>
            </div>
            <p class="shop-product-availability shop-availability-<?= $variant->availabilityState() ?> mb-3" data-product-availability><?= Html::encode($variant->availabilityLabel()) ?></p>

            <?php if ($product->short_description !== ''): ?>
                <div class="shop-product-short-description"><?= $product->short_description ?></div>
            <?php endif ?>

            <?php if ($canBuy): ?>
                <?= Html::beginForm(['/shop/cart/add'], 'post', ['id' => 'shop-add-to-cart', 'data-variant' => $variant->id]) ?>
                    <?= Html::hiddenInput('product_id', (string) $product->id) ?>
                    <?php foreach ($options as $option): ?>
                        <?= $this->render($option['view'], [
                            'attribute' => $option['attribute'],
                            'values' => $option['values'],
                            'selectedValueIds' => $selectedValueIds,
                        ]) ?>
                    <?php endforeach ?>
                    <?= Html::beginTag('fieldset', ['class' => 'shop-add-to-cart', 'disabled' => $preview]) ?>
                        <div class="shop-stepper">
                            <?= Html::button('−', ['class' => 'shop-stepper-button', 'data-quantity-step' => -1, 'aria-label' => Yii::t('shop', 'One less')]) ?>
                            <?= Html::input('number', 'quantity', (string) $product->minQuantity(), [
                                'class'         => 'form-control',
                                'min'           => $product->minQuantity(),
                                'max'           => $variant->maxQuantity(),
                                'step'          => $product->quantityStep(),
                                'data-quantity' => true,
                                'data-min'      => $product->minQuantity(),
                                'data-step'     => $product->quantityStep(),
                                'aria-label'    => Yii::t('shop', 'Quantity'),
                            ]) ?>
                            <?= Html::button('+', ['class' => 'shop-stepper-button', 'data-quantity-step' => 1, 'aria-label' => Yii::t('shop', 'One more')]) ?>
                        </div>
                        <?= Html::submitButton(Yii::t('shop', 'Add to cart'), ['class' => 'btn btn-primary', 'disabled' => !$variant->isAvailable()]) ?>
                    <?= Html::endTag('fieldset') ?>
                    <?php if ($product->quantityNote() !== ''): ?>
                        <p class="shop-product-quantity-note"><?= Html::encode($product->quantityNote()) ?></p>
                    <?php endif ?>
                <?= Html::endForm() ?>
            <?php endif ?>

            <p class="shop-product-sku mb-0<?= $variant->sku ? '' : ' d-none' ?>" data-product-sku><?= Yii::t('shop', 'SKU') ?>: <span><?= Html::encode($variant->sku) ?></span></p>
        </div>
    </div>
    <script type="application/json" data-shop-variants><?= Json::htmlEncode($variants) ?></script>
</article>

<?php if ($sections !== []): ?>
    <?= $this->render($sectionsView, ['sections' => $sections, 'titles' => $sectionTitles]) ?>
<?php endif ?>

<?php if ($related !== []): ?>
    <section class="shop-related container mt-5 mb-5">
        <h2><?= Yii::t('shop', 'You may also like') ?></h2>
        <div class="shop-grid shop-grid-related">
            <?php foreach ($related as $item): ?>
                <?= $this->render('_card', ['product' => $item]) ?>
            <?php endforeach ?>
        </div>
    </section>
<?php endif ?>
