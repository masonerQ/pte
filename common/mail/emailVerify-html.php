<?php
use yii\helpers\Html;
    use yii\helpers\Url;

    /* @var $this yii\web\View */
/* @var $user common\models\User */

    Yii::$app->urlManager->setBaseUrl(Yii::$app->params['VerifyEmailBaseUrl']);
    $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
    // Html::a(Html::encode($verifyLink), $verifyLink);
?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->username) ?>, 欢迎注册无界教育</p>

    <p>下面是您的激活码:</p>

    <h3><?=$user->verification_token;?></h3>
</div>
