<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

    Yii::$app->urlManager->setBaseUrl(Yii::$app->params['VerifyEmailBaseUrl']);
    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hello <?= $user->username ?>, 欢迎注册无界教育

下面是您的激活链接:

<?= $verifyLink ?>
