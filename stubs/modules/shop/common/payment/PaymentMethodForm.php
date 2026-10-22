<?php

declare(strict_types=1);

namespace modules\shop\common\payment;

use common\models\SettingsForm;
use Yii;

/**
 * The form of the page of a way of payment in the administration: whether the checkout offers it, the label and
 * the description the shop gives it in place of the platform's (the label empty for the platform's; the
 * description starts as the platform's and is what the shop saved, empty for none) and what it costs the
 * customer, kept as the settings of the method. A method with settings of its own extends it with their fields (see {@see BankTransferForm})
 * and names it in `PaymentMethod::settingsForm()`.
 */
class PaymentMethodForm extends SettingsForm
{
    /**
     * Whether the checkout offers the way, `1` or `0`; as the way says by default until the shop set it.
     */
    public string $enabled;

    public string $label = '';
    /**
     * The sentence under the name at the checkout; the platform's until the shop saved its own, and none when it
     * saved it empty.
     */
    public string $description;
    public string $price = '0';

    // Private, so the settings form does not take it for a setting like the public attributes.
    public function __construct(private readonly PaymentMethod $method, array $config = [])
    {
    }

    /**
     * {@inheritdoc}
     *
     * The fields of every method go by the same name (`PaymentMethodForm[label]`), whatever class its form is.
     */
    public function formName(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function prefix(): string
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints(): array
    {
    }
}
