<?php
use yii\helpers\Html;
    use yii\helpers\Url;

    /* @var $this yii\web\View */
/* @var $user common\models\User */

    Yii::$app->urlManager->setBaseUrl(Yii::$app->params['VerifyEmailBaseUrl']);
    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->username) ?>, 欢迎注册无界教育</p>

    <p>下面是您的激活链接:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
