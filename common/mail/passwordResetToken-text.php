<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->username ?>, 欢迎注册无界教育

下面是您的找回密码 验证码:

<?= $user->password_reset_token; ?>
