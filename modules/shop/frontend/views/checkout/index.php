<?php

/**
 * The checkout: one form in sections, the contact (the email address and the phone, the way to log in for a
 * guest), the delivery address and the section of the invoice (`_billing.php`: the company, the billing address
 * behind "Same as the delivery address"), or, while the shop asks for the billing address first, that section
 * first and the delivery address behind "Same as the billing address", the ways of delivery
 * with what each costs, the ways of payment with what each costs and the pictures of what each takes (the brands
 * of the cards, _payment-logos.php), each with its body under it that opens while
 * it is chosen (the fields of a card, see `PaymentMethod::checkoutView()`), a note, the account a guest may or has to
 * create, the terms and the privacy policy named above the button, the button that places the order and under
 * it the pages of the site a customer may need (the terms, the privacy policy, the contact page), in place of
 * the footer the page has none of; next
 * to it the summary of the cart (`_summary.php`) with the discounts, the delivery, the fee of the payment when
 * it has one and the total of the ways chosen, and the discount code while the shop takes them, in the summary
 * next to the form only (on a phone the one above the button, never the folded one at the top), its field in
 * a form of its own (`shop-discount-code`) after the form of the checkout. On a phone the summary stands folded at the top, the total on its bar, and open
 * again right above the button; the button with the policies and the links is the foot of the form
 * (`shop-checkout-foot`), so it comes after the summary there. The form is marked with `data-checkout` and the parts that follow what is filled in with
 * `data-checkout-part`: `public/js/shop.js` posts the form on any change in it, so the cart remembers what was
 * filled in, and swaps those parts in from the answer (see CheckoutController); a part whose
 * `data-checkout-version` did not change stays as it is, so what the visitor typed into the body of a way of
 * payment (a card) stays while the ways offered are the same. Without a way
 * of delivery or of payment the shop cannot take orders and says so. A template a theme replaces: everything
 * shown comes from the controller (see CheckoutController::checkoutParams()).
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $title */
/** @var modules\shop\frontend\models\CheckoutForm $form */
/** @var bool $canOrder Whether a way of delivery and a way of payment are offered. */
/** @var array<string, string> $shippingCountries The countries of the delivery address, their names by code. */
/** @var array<string, string> $billingCountries The countries of the billing address, their names by code. */
/** @var bool $billingFirst Whether the checkout asks for the billing address first, the delivery address behind "Same as the billing address". */
/** @var bool $offersBilling Whether the checkout asks for a billing address. */
/** @var string $company Whether the checkout asks for a company, one of the `Module::COMPANY_*` constants. */
/** @var string $billingTitle The title of the section of the invoice. */
/** @var array<string, string> $companyFields The fields of the company asked for, their label by name. */
/** @var string $companyVersion What the fields of the company are, as a fingerprint: the refresh leaves the part as it is while it stays the same. */
/** @var modules\shop\common\models\CartLine[] $lines */
/** @var string $subtotalLabel */
/** @var list<array{label: string, amount: string}> $discounts The discounts, as the visitor reads them; empty for none. */
/** @var bool $takesCodes Whether the shop takes discount codes. */
/** @var string $code The discount code applied; empty for none. */
/** @var string $codeInput The code the visitor typed, while it did not apply. */
/** @var string $codeError What was wrong with the code; empty for nothing. */
/** @var string $shippingLabel What the delivery costs with the way chosen, as the visitor reads it. */
/** @var string $paymentFeeLabel What the way of payment chosen costs; empty for nothing. */
/** @var string $totalLabel What the order comes to with the ways chosen, VAT included. */
/** @var string $total The same as a number ("59.80"), for the script of a way of payment. */
/** @var string $currency The ISO 4217 code of the currency of the order. */
/** @var list<array{label: string, amount: string}> $taxLines The VAT by rate, as the visitor reads it; empty for none. */
/** @var bool $taxesIncluded Whether the amounts include the VAT (see _summary.php). */
/** @var list<array{key: string, name: string, description: string, price: float, priceLabel: string}> $shippingMethods */
/** @var list<array{name: string, label: string, description: string, priceLabel: string, logos: list<array{label: string, svg: string}>, moreLogos: string, moreLogosTitle: string, view: string|null, details: yii\base\Model|null}> $paymentMethods The fee as the visitor reads it, or empty; the view of what the way shows while it is chosen and the model of what the customer gives for it, or null. */
/** @var string $paymentVersion What the ways of payment offered are, as a fingerprint: the refresh leaves the part as it is while it stays the same. */
/** @var string $accounts One of the `Module::ACCOUNTS_*` constants. */
/** @var array|string $loginUrl */
/** @var array<string, common\models\Page> $policies The terms of use and the privacy policy, those the site has. */
/** @var list<array{label: string, url: string}> $links The pages of the site shown under the button, those the site has. */

use modules\shop\frontend\assets\ShopAsset;
use modules\shop\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

ShopAsset::register($this);

$formatter = Yii::$app->formatter;
$phoneHint = '<span class="input-hint">{input}'
    . Html::button(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>',
        ['class' => 'input-hint-button', 'aria-label' => Yii::t('shop', 'Why we ask for it'), 'aria-describedby' => 'checkout-phone-hint'],
    )
    . Html::tag('span', Yii::t('shop', 'We need your phone number in case we have to reach you about your order.'), ['class' => 'input-hint-text', 'role' => 'tooltip', 'id' => 'checkout-phone-hint'])
    . '</span>';
$summary = [
    'lines'           => $lines,
    'subtotalLabel'   => $subtotalLabel,
    'discounts'       => $discounts,
    'shippingLabel'   => $shippingLabel,
    'paymentFeeLabel' => $paymentFeeLabel,
    'totalLabel'      => $totalLabel,
    'taxLines'        => $taxLines,
    'taxesIncluded'   => $taxesIncluded,
    'takesCodes'      => $takesCodes,
    'code'            => $code,
    'codeInput'       => $codeInput,
    'codeError'       => $codeError,
];
$billing = [
    'form'           => $form,
    'title'          => $billingTitle,
    'billingFirst'   => $billingFirst,
    'offersBilling'  => $offersBilling,
    'company'        => $company,
    'companyFields'  => $companyFields,
    'companyVersion' => $companyVersion,
    'countries'      => $billingCountries,
];
$policyLinks = [];
foreach ($policies as $purpose => $page) {
    $policyLinks[$purpose] = Html::a($purpose === 'terms' ? Yii::t('shop', 'terms of use') : Yii::t('shop', 'privacy policy'), $page->getUrl(), ['target' => '_blank']);
}
$policySentence = match (array_keys($policies)) {
    ['terms', 'privacy'] => Yii::t('shop', 'By placing the order you accept the {terms} and the {privacy}.', $policyLinks),
    ['terms'] => Yii::t('shop', 'By placing the order you accept the {terms}.', $policyLinks),
    ['privacy'] => Yii::t('shop', 'By placing the order you accept the {privacy}.', $policyLinks),
    default => '',
};
?>
<div class="shop-checkout container">
    <?php if (!$canOrder): ?>
        <h1 class="mt-5"><?= Html::encode($title) ?></h1>
        <p><?= Yii::t('shop', 'The shop cannot take orders at the moment.') ?></p>
        <?= Html::a(Yii::t('shop', 'Back to the cart'), ['/shop/cart/index'], ['class' => 'btn btn-outline-secondary mb-5']) ?>
    <?php else: ?>
        <?php $activeForm = ActiveForm::begin(['id' => 'shop-checkout-form', 'options' => ['class' => 'shop-checkout-layout', 'data-checkout' => true]]) ?>
            <details class="shop-checkout-top" data-checkout-part="summary-top">
                <summary>
                    <span class="shop-checkout-top-label">
                        <?= Yii::t('shop', 'Total') ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                    <span class="shop-checkout-top-total" data-checkout-total><?= Html::encode($totalLabel) ?></span>
                </summary>
                <div class="shop-checkout-top-body">
                    <?= $this->render('_summary', $summary + ['withCode' => false]) ?>
                </div>
            </details>

            <div class="shop-checkout-main">
                <h1><?= Html::encode($title) ?></h1>
                <?= $activeForm->errorSummary($form, ['class' => 'alert alert-danger', 'header' => '']) ?>

                <section class="shop-checkout-section">
                    <div class="shop-checkout-section-head">
                        <h2 class="h4"><?= Yii::t('shop', 'Contact') ?></h2>
                        <?php if ($form->isGuest()): ?>
                            <span class="small"><?= Yii::t('shop', 'Already have an account?') ?> <?= Html::a(Yii::t('shop', 'Log in'), $loginUrl) ?></span>
                        <?php endif ?>
                    </div>
                    <?= $activeForm->field($form, 'email')->textInput(['type' => 'email', 'autocomplete' => 'email', 'readonly' => !$form->isGuest()]) ?>
                    <?= $activeForm->field($form, 'phone', ['template' => "{label}\n$phoneHint\n{hint}\n{error}"])->textInput(['type' => 'tel', 'autocomplete' => 'tel']) ?>
                </section>

                <?php if ($billingFirst): ?>
                    <?= $this->render('_billing', $billing + ['activeForm' => $activeForm]) ?>
                <?php endif ?>

                <section class="shop-checkout-section">
                    <h2 class="h4"><?= Yii::t('shop', 'Delivery address') ?></h2>
                    <?php if ($billingFirst): ?>
                        <?= $activeForm->field($form, 'billing_same', ['options' => ['class' => 'form-group checkbox']])->checkbox([
                            'data-checkout-toggle'      => 'delivery',
                            'data-checkout-toggle-when' => 'unchecked',
                        ]) ?>
                        <div data-checkout-section="delivery">
                            <?= $this->render('_address', ['activeForm' => $activeForm, 'form' => $form, 'prefix' => '', 'countries' => $shippingCountries]) ?>
                        </div>
                    <?php else: ?>
                        <?= $this->render('_address', ['activeForm' => $activeForm, 'form' => $form, 'prefix' => '', 'countries' => $shippingCountries]) ?>
                    <?php endif ?>
                </section>

                <?php if (!$billingFirst && ($offersBilling || $company !== Module::COMPANY_DISABLED)): ?>
                    <?= $this->render('_billing', $billing + ['activeForm' => $activeForm]) ?>
                <?php endif ?>

                <section class="shop-checkout-section" data-checkout-part="shipping">
                    <h2 class="h4"><?= Yii::t('shop', 'Delivery') ?></h2>
                    <?php if ($shippingMethods === []): ?>
                        <p class="text-muted"><?= Yii::t('shop', 'We do not deliver to this country.') ?></p>
                    <?php endif ?>
                    <?= $activeForm->field($form, 'shipping_method')->label(false)->radioList(
                        array_column($shippingMethods, 'name', 'key'),
                        [
                            'class' => 'shop-checkout-methods',
                            'item' => static function (int $index, string $label, string $name, bool $checked, string $value) use ($shippingMethods): string {
                                $method = $shippingMethods[$index];

                                return Html::beginTag('label', ['class' => 'shop-checkout-method'])
                                    . Html::radio($name, $checked, ['value' => $value])
                                    . Html::tag('span', Html::encode($label), ['class' => 'shop-checkout-method-name'])
                                    . ($method['description'] === '' ? '' : Html::tag('span', Html::encode($method['description']), ['class' => 'shop-checkout-method-description']))
                                    . Html::tag('span', Html::encode($method['priceLabel']), ['class' => 'shop-checkout-method-price'])
                                    . Html::endTag('label');
                            },
                        ],
                    ) ?>
                </section>

                <section class="shop-checkout-section">
                    <h2 class="h4"><?= Yii::t('shop', 'Payment') ?></h2>
                    <div data-checkout-part="payment" data-checkout-version="<?= Html::encode($paymentVersion) ?>">
                        <?php if ($paymentMethods === [] && $shippingMethods !== []): ?>
                            <p class="text-muted"><?= Yii::t('shop', 'No payment method goes with this delivery.') ?></p>
                        <?php endif ?>
                        <?= $activeForm->field($form, 'payment_method')->label(false)->radioList(
                            array_column($paymentMethods, 'label', 'name'),
                            [
                                'class' => 'shop-checkout-methods',
                                'item'  => function (int $index, string $label, string $name, bool $checked, string $value) use ($paymentMethods, $activeForm): string {
                                    $method = $paymentMethods[$index];

                                    return Html::beginTag('div', ['class' => 'shop-checkout-option'])
                                        . Html::beginTag('label', ['class' => 'shop-checkout-method'])
                                        . Html::radio($name, $checked, ['value' => $value])
                                        . Html::tag('span', Html::encode($label), ['class' => 'shop-checkout-method-name'])
                                        . ($method['description'] === '' ? '' : Html::tag('span', Html::encode($method['description']), ['class' => 'shop-checkout-method-description']))
                                        . Html::beginTag('span', ['class' => 'shop-checkout-method-end'])
                                        . $this->render('_payment-logos', ['method' => $method])
                                        . ($method['priceLabel'] === '' ? '' : Html::tag('span', Html::encode($method['priceLabel']), ['class' => 'shop-checkout-method-price']))
                                        . Html::endTag('span')
                                        . Html::endTag('label')
                                        . ($method['view'] === null ? '' : Html::tag(
                                            'div',
                                            $this->render($method['view'], ['form' => $activeForm, 'model' => $method['details']]),
                                            ['class' => 'shop-checkout-method-body'],
                                        ))
                                        . Html::endTag('div');
                                },
                            ],
                        ) ?>
                    </div>
                </section>

                <section class="shop-checkout-section">
                    <h2 class="h4"><?= Yii::t('shop', 'Note') ?></h2>
                    <?= $activeForm->field($form, 'note')->label(false)->textarea(['rows' => 3, 'placeholder' => Yii::t('shop', 'Anything we should know about your order')]) ?>
                </section>

                <?php if ($form->isGuest() && $accounts !== Module::ACCOUNTS_DISABLED): ?>
                    <section class="shop-checkout-section">
                        <h2 class="h4"><?= Yii::t('shop', 'Account') ?></h2>
                        <?php if ($accounts === Module::ACCOUNTS_REQUIRED): ?>
                            <p class="small text-muted"><?= Yii::t('shop', 'Choose a password to create your account with this order.') ?></p>
                            <?= $activeForm->field($form, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
                        <?php else: ?>
                            <?= $activeForm->field($form, 'create_account', ['options' => ['class' => 'form-group checkbox']])->checkbox(['data-checkout-toggle' => 'account']) ?>
                            <div data-checkout-section="account">
                                <?= $activeForm->field($form, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
                            </div>
                        <?php endif ?>
                    </section>
                <?php endif ?>

            </div>

            <aside class="shop-checkout-summary" data-checkout-part="summary" data-checkout-amount="<?= Html::encode($total) ?>" data-checkout-currency="<?= Html::encode($currency) ?>">
                <div class="shop-checkout-summary-inner">
                    <h2 class="h4"><?= Yii::t('shop', 'Your order') ?></h2>
                    <?= $this->render('_summary', $summary + ['withCode' => true]) ?>
                </div>
            </aside>

            <div class="shop-checkout-foot">
                <?php if ($policySentence !== ''): ?>
                    <p class="shop-checkout-policies small text-muted"><?= $policySentence ?></p>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('shop', 'Place order'), ['class' => 'btn btn-primary btn-lg shop-checkout-submit']) ?>
                <?php if ($links !== []): ?>
                    <nav class="shop-checkout-links" aria-label="<?= Html::encode(Yii::t('shop', 'Pages of the shop')) ?>">
                        <?php foreach ($links as $link): ?>
                            <?= Html::a(Html::encode($link['label']), $link['url'], ['target' => '_blank']) ?>
                        <?php endforeach ?>
                    </nav>
                <?php endif ?>
            </div>
        <?php ActiveForm::end() ?>

        <?php if ($takesCodes): ?>
            <?= Html::beginForm('', 'post', ['id' => 'shop-discount-code', 'data-discount-code-form' => true]) ?>
            <?= Html::endForm() ?>
        <?php endif ?>
    <?php endif ?>
</div>
