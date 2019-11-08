<?php

    use common\widgets\JsBlock;

?>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label for="L_cate_name" class="layui-form-label">
                        <span class="x-red">*</span>分类名称</label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_cate_name" name="cate_name" required="" lay-verify="cate_name" autocomplete="off" class="layui-input"
                               value="<?= $model->cate_name; ?>"/>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?= $model->id; ?>"/>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>"/>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="edit" lay-submit="">提交</button>
                </div>
            </form>
        </div>
    </div>

<?php JsBlock::begin(); ?>
    <script type="application/javascript">
        layui.use(['form', 'layer', 'jquery'],
            function () {
                let $ = layui.jquery;
                let form = layui.form,
                    layer = layui.layer;

                //自定义验证规则
                form.verify({
                    cate_name: function (value) {
                        if (value.length < 3) {
                            return '昵称至少得3个字符啊';
                        }
                    }
                });

                //监听提交
                form.on('submit(edit)',
                    function (data) {
                        console.log(data);
                        $.ajax({
                            url: "/video-class/cate-edit.html",
                            method: "POST",
                            data: data.field,
                            success: function (res) {
                                console.log(res);
                                if (res.code === 200) {
                                    //发异步，把数据提交给php
                                    layer.alert("编辑成功", {icon: 6}, function (){
                                            //关闭当前frame
                                            xadmin.close();
                                            // 可以对父窗口进行刷新
                                            xadmin.father_reload();
                                        }
                                    );
                                } else {
                                    layer.msg(res.msg, {
                                        icon: 2
                                        , time: 8 * 1000,
                                        shift: 6
                                    });
                                }
                            }
                        });
                        return false;
                    }
                );
            }
        );
    </script>
<?php JsBlock::end(); ?>