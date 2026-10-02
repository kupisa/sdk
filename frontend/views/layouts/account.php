<?php

/**
 * The layout of the account area of the site, inside the main layout: the title, then the navigation of the
 * sections (see frontend\widgets\accountnav\AccountNav) next to the content of the section shown, one under the
 * other on a phone. A controller of the account area picks it with `$layout = 'account'` (`'//account'` from a
 * module), and a theme replaces it like any layout.
 */

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use frontend\widgets\accountnav\AccountNav;

?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>
<div class="account container py-5">
    <h1 class="account-title"><?= Yii::t('app', 'My account') ?></h1>
    <div class="account-layout">
        <?= AccountNav::widget() ?>
        <div class="account-content">
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endContent() ?>
