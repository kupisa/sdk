<?php

declare(strict_types=1);

namespace modules\shop\common\models;

use common\behaviors\MetaBehavior;
use common\behaviors\TimestampBehavior;
use common\models\SiteRecord;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * Something that happened to an order, when and by whom: the history the page of the order shows as a timeline
 * (see `Order::getEvents()`). The platform writes the ones of its own as the order goes (see the `TYPE_*`
 * constants and `Order::addEvent()`): it was placed, its status or its payment changed, a note was written by
 * the shop. A module writes its own with a type prefixed by its name (`stripe.paid`, `invoice.created`), the
 * words the timeline shows in {@see $message} and whatever it wants to keep in the meta.
 *
 * @property int $id
 * @property int $order_id
 * @property string $type One of the `TYPE_*` constants, or the type of a module.
 * @property string|null $message The words of a note or of a module; the platform's own types are described by {@see description()}.
 * @property int|null $user_id Who did it: the user logged in then, null for the customer without an account or the system.
 * @property array|null $meta The values beyond the columns, see `MetaBehavior`: `from` and `to` of a change of status.
 * @property int $created
 * @property int $updated
 */
class OrderEvent extends SiteRecord
{
    /**
     * The order was placed.
     */
    public const TYPE_CREATED = 'created';

    /**
     * The status changed; `from` and `to` in the meta.
     */
    public const TYPE_STATUS = 'status';

    /**
     * The status of the payment changed; `from` and `to` in the meta.
     */
    public const TYPE_PAYMENT_STATUS = 'payment_status';

    /**
     * A note the shop wrote about the order, for itself.
     */
    public const TYPE_NOTE = 'note';

    /**
     * The shop changed the customer of the order: the contact or an address (see `OrderCustomerForm`).
     */
    public const TYPE_CUSTOMER = 'customer';

    /**
     * The order was brought in by an import, from a file of another shop.
     */
    public const TYPE_IMPORTED = 'imported';

    /**
     * The order was made as a copy of another (see `Order::duplicate()`); the `number` of that one in the meta.
     */
    public const TYPE_DUPLICATE_OF = 'duplicate_of';

    /**
     * A copy of the order was made (see `Order::duplicate()`); the `number` of the copy in the meta.
     */
    public const TYPE_DUPLICATED = 'duplicated';

    /**
     * The shop refunded the order from its page (see `Order::refund()`).
     */
    public const TYPE_REFUNDED = 'refunded';

    /**
     * The shop changed the order in the administration: its lines, its way of delivery or of payment (see
     * `Order::saveChanges()`); the `changes` in the meta, as that method gives them.
     */
    public const TYPE_CHANGED = 'changed';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
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
     */
    public function rules(): array
    {
    }

    /**
     * What happened, in words, in the language of the administration: the platform's own types are described
     * here, from what the meta keeps, and anything else says what it wrote in its message.
     */
    public function description(): string
    {
    }

    /**
     * Who did it, as the timeline names them: the user, or the customer for an order placed without an account,
     * or the system for anything else nobody did by hand.
     */
    public function byLabel(): string
    {
    }

    /**
     * The user logged in now, for an event written by hand; null on the console or for a visitor.
     */
    public static function currentUserId(): int|null
    {
    }

    /**
     * @return ActiveQuery<Order>
     */
    public function getOrder(): ActiveQuery
    {
    }

    /**
     * @return ActiveQuery<User>
     */
    public function getUser(): ActiveQuery
    {
    }
}
