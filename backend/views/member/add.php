<?php

    use common\widgets\JsBlock;

    // echo "<pre>";
    // print_r($model->getErrors());
    // echo "</pre>";

?>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="email" required="" lay-verify="email" autocomplete="off" class="layui-input"></div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>将会成为您唯一的登入名
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_username" class="layui-form-label">
                        <span class="x-red">*</span>昵称</label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_username" name="username" required="" lay-verify="nikename" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                        <span class="x-red">*</span>密码</label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_pass" name="password" required="" lay-verify="pass" autocomplete="off" class="layui-input"></div>
                    <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                        <span class="x-red">*</span>确认密码</label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="repassword" required="" lay-verify="repass" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>"/>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="add" lay-submit="">增加</button>
                </div>
            </form>
        </div>
    </div>

<?php JsBlock::begin(); ?>
    <script>layui.use(['form', 'layer', 'jquery'],
            function () {
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer;

                //自定义验证规则
                form.verify({
                    nikename: function (value) {
                        if (value.length < 5) {
                            return '昵称至少得5个字符啊';
                        }
                    },
                    pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    repass: function (value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });

                //监听提交
                form.on('submit(add)',
                    function (data) {
                        console.log(data);
                        $.ajax({
                            url: "/member/add.html",
                            method: "POST",
                            data: data.field,
                            success: function (res) {
                                console.log(res);
                                if (res.code === 200) {
                                    //发异步，把数据提交给php
                                    layer.alert("增加成功", {
                                            icon: 6
                                        },
                                        function () {
                                            //关闭当前frame
                                            xadmin.close();

                                            // 可以对父窗口进行刷新
                                            xadmin.father_reload();
                                        }
                                    );
                                }else{
                                    layer.alert("增加失败", {
                                            icon: 5
                                        },
                                        function () {
                                            //关闭当前frame
                                            xadmin.close();
                                            // 可以对父窗口进行刷新
                                            xadmin.father_reload();
                                        }
                                    );
                                }
                            }
                        });
                        return false;
                    });

            });</script>
<?php JsBlock::end(); ?>