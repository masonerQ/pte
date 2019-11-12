<?php

    use common\widgets\JsBlock;

?>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form" method="post">
            <div class="layui-form-item">
                <label for="L_title" class="layui-form-label">
                    <span class="x-red">*</span>标题
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_title" name="title" required="" lay-verify="title" autocomplete="off" class="layui-input"
                           value="<?= $model->title; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>
                </div>
            </div>


            <div class="layui-form-item">
                <label for="L_url" class="layui-form-label">
                    <span class="x-red">*</span>链接
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_url" name="url" required="" lay-verify="url" autocomplete="off" class="layui-input"
                           value="<?= $model->url; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>
                </div>
            </div>


            <div class="layui-form-item">
                <label for="L_img_link" class="layui-form-label">
                    <span class="x-red">*</span>图片
                </label>
                <div class="layui-input-inline" style="width: 250px;">
                    <input readonly type="text" id="L_img_link" name="img_link" required="" lay-verify="img_link" autocomplete="off" class="layui-input"
                           value="<?= $model->img_link ?>"/>
                </div>
                <div class="layui-input-inline">
                    <div class="layui-upload">
                        <button type="button" class="layui-btn" id="img_link">图片上传</button>
                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            <div class="layui-upload-list" id="img_link_preview">
                                <img width="115" height="100" src="<?= $model->img_link; ?>" alt="" class="layui-upload-img"/>
                            </div>
                        </blockquote>
                    </div>
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

    layui.use(['upload', 'form', 'layer', 'jquery', 'layedit'],
        function () {
            let $ = layui.jquery
                , form = layui.form
                , layer = layui.layer
                , layedit = layui.layedit
                , upload = layui.upload;

            let data = {"<?=Yii::$app->request->csrfParam;?>": "<?=Yii::$app->request->csrfToken;?>"};
            layedit.set({
                height: '90%'
                , tool: [
                    'html', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'colorpicker', 'face'
                    , '|', 'left', 'center', 'right', '|', 'link', 'unlink','image_alt', 'video', 'anchors'
                    , '|','table', 'fullScreen'
                ]
                ,uploadImage: {
                    url: 'upload.html'
                    , type: 'post'
                    , data: data
                    , multiple: false
                    , accept: 'images'
                    , acceptMime: 'image/jpg, image/jpeg, image/png'
                    , field: 'file'
                    , exts: "jpg|jpeg|png"
                }
            });

            //多图片上传
            upload.render({
                elem: '#img_link'
                , url: 'upload.html'
                , multiple: false
                , accept: 'images'
                , acceptMime: 'image/jpg, image/jpeg, image/png'
                , exts: 'jpg|jpeg|png'
                , field: 'file'
                , data: data
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#img_link_preview').html('<img width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                    });
                }
                , done: function (res) {
                    //上传完毕
                    console.log(res);
                    if (res.code === 200) {
                        console.log(res.filename);
                        $("input[name='img_link']").val(res.filename);
                    }
                }
            });

            //自定义验证规则
            form.verify({
                title: function (value) {
                    if (value.length < 2) {
                        return '标题至少得2个字符啊';
                    }
                },
                url: function (value) {

                },
                img_link: function (value) {
                    console.log(value.length);
                    console.log(value.search('.jpg'));
                    console.log(value.search('.jpeg'));
                    console.log(value.search('.png'));
                    if (value.length <= 0 && value.search('.jpg') === -1 && value.search('.jpeg') === -1 && value.search('.png') === -1) {
                        return '请上传图片';
                    }
                }
            });

            //监听提交
            form.on('submit(edit)',
                function (data) {
                    console.log(data);
                    $.ajax({
                        url: "/banner/edit.html?id=<?=$model->id;?>",
                        method: "POST",
                        data: data.field,
                        success: function (res) {
                            console.log(res);
                            if (res.code === 200) {
                                //发异步，把数据提交给php
                                layer.alert("更新成功", {icon: 6}, () => {
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
