<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */

    /* @var $model \common\models\LoginForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;

    $this->title                   = 'Login';
    $this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="site-login">-->
<!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--    <p>Please fill out the following fields to login:</p>-->
<!---->
<!--    <div class="row">-->
<!--        <div class="col-lg-5">-->
<!--            --><?php //$form = ActiveForm::begin(['id' => 'login-form']); ?>
<!---->
<!--                --><? //= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
<!---->
<!--                --><? //= $form->field($model, 'password')->passwordInput() ?>
<!---->
<!--                --><? //= $form->field($model, 'rememberMe')->checkbox() ?>
<!---->
<!--                <div class="form-group">-->
<!--                    --><? //= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
<!--                </div>-->
<!---->
<!--            --><?php //ActiveForm::end(); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录-X-admin2.2</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/X-admin/css/font.css">
    <link rel="stylesheet" href="/X-admin/css/login.css">
    <link rel="stylesheet" href="/X-admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/X-admin/lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">管理登录</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form">
        <input type="hidden" name="<?=Yii::$app->request->csrfParam;?>" value="<?=Yii::$app->request->csrfToken;?>" class="layui-input">
        <input name="LoginForm[username]" placeholder="用户名" type="text" lay-verify="required" class="layui-input">
        <hr class="hr15">
        <input name="LoginForm[password]" lay-verify="required" placeholder="密码" type="password" class="layui-input">
        <!--<textarea name="" id="" cols="30" rows="10">-->
            <?php $error = $model->getErrors(); //var_dump($error);?>
        <!--</textarea>-->
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20">
    </form>
</div>

<script>
    //$(function () {
    //    layui.use('form', function () {
    //        var form = layui.form;
    //        // layer.msg('玩命卖萌中', function(){
    //        //   //关闭后的操作
    //        //   });
    //        //监听提交
    //        form.on('submit(login)', function (data) {
    //            // alert(888)
    //            console.log(data);
    //            $.ajax({
    //                url: "<?//=Url::to(['/site/login'])?>//",
    //                data: data.field,
    //                type: 'POST',
    //                dataType: 'json',
    //                success: function (res) {
    //                    console.log(res);
    //                    layer.msg(JSON.stringify(res), function () {
    //                        // location.href='index.html'
    //                    });
    //                },
    //                error: function (err) {
    //                    console.log(err);
    //                }
    //            });
    //            return false;
    //        });
    //    });
    //})
</script>
<!-- 底部结束 -->
</body>
</html>
