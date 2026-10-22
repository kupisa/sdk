<?php

declare(strict_types=1);

namespace modules\shop\frontend;

use modules\shop\common\models\Cart;
use Yii;
use yii\web\Cookie;
use yii\web\UserEvent;

/**
 * The cart of the visitor of the site: found by the token in their `cart` cookie, or, for a logged-in user
 * without one, the cart they left on another device. A new cart is made when they put something in it for the
 * first time, and its token goes into the cookie for {@see Cart::DAYS_KEPT} days; the old carts of the site are
 * dropped then.
 *
 * When a user logs in, the cart they had before is taken into the one in their cookie (see {@see onLogin()},
 * attached to the `user` component by the module), so nothing they picked is lost.
 */
final class CurrentCart
{
    /**
     * The name of the cookie holding the token of the cart.
     */
    public const COOKIE = 'cart';

    /**
     * The cart of the visitor, or null while they have none.
     */
    public static function find(): Cart|null
    {
    }

    /**
     * The cart of the visitor, a new one when they have none: saved, with its token in their cookie.
     */
    public static function get(): Cart
    {
    }

    /**
     * On login: the cart of the cookie becomes the user's, and the cart they had before, on another device or
     * before their cookie was gone, joins it.
     *
     * @param UserEvent $event The login event of the `user` component.
     */
    public static function onLogin(UserEvent $event): void
    {
    }
}
