<?php

    use common\widgets\JsBlock;

    /**
     * @var $option
     */

?>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" method="post">


                <div class="layui-form-item">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-inline">
                        <select name="cate_id" lay-filter="cate_id" lay-verify="cate_id">
                            <option value=""></option>
                            <?=$option;?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_article_name" class="layui-form-label">
                        <span class="x-red">*</span>标题
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_article_name" name="article_name" required="" lay-verify="article_name" autocomplete="off" class="layui-input"
                               value="<?= $model->article_name; ?>"/>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
                </div>


                <div class="layui-form-item layui-form-text">
                    <label for="L_article_content" class="layui-form-label">
                        <span class="x-red">*</span>内容
                    </label>
                    <div class="layui-input-block">
                        <textarea type="text" id="L_article_content" name="article_content" required="" lay-verify="article_content" autocomplete="off"
                                  class="layui-textarea">
                            <?= $model->article_content; ?>
                        </textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">精选</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="status" lay-skin="switch" lay-text="是|否" value="1">
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

               let editIndex = layedit.build('L_article_content');

                //多图片上传
                upload.render({
                    elem: '#avatar'
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

                            $('#preview').html('<img width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                        });
                    }
                    , done: function (res) {
                        //上传完毕
                        console.log(res);
                        if (res.code === 200) {
                            console.log(res.filename);
                            $("input[name='avatar']").val(res.filename);
                        }
                    }
                });

                //自定义验证规则
                form.verify({
                    cate_id: function (value) {
                        if (value == 0) {
                            return '请选择一个分类';
                        }
                    },
                    article_name: function (value) {
                        if (value.length < 2) {
                            return '文章标题至少2个字符';
                        }
                    },
                    article_content: function (value) {
                        layedit.sync(editIndex);
                        let content = layedit.getContent(editIndex);
                        if ($.trim(content).length < 10){
                            return '文章内容至少10个字符';
                        }
                    },
                });

                //监听提交
                form.on('submit(edit)',
                    function (data) {
                        console.log(data);
                        $.ajax({
                            url: "/article/edit.html?id=<?=$model->id;?>",
                            method: "POST",
                            data: data.field,
                            success: function (res) {
                                console.log(res);
                                if (res.code === 200) {
                                    //发异步，把数据提交给php
                                    layer.alert("编辑成功", {icon: 6}, () => {
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