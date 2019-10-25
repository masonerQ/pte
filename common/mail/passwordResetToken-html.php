<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
    Html::a(Html::encode($resetLink), $resetLink)
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>, 欢迎注册无界教育</p>

    <p>下面是您的找回密码 验证码:</p>

    <h3><?= $user->password_reset_token; ?></h3>

</div>
