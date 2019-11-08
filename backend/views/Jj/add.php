<?php

    use common\widgets\JsBlock;

    /**
     * @var $option
     */

?>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" method="post" id="layui-form">


                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>年份</label>
                    <div class="layui-input-inline">
                        <select name="year" lay-filter="year" lay-verify="year">
                            <option value=0></option>
                            <?=$option;?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_video_title" class="layui-form-label">
                        <span class="x-red">*</span>机经名称
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_video_title" name="video_title" required="" lay-filter="video_title" lay-verify="video_title" autocomplete="off" class="layui-input"
                               value="<?= $model->video_title; ?>"/>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_video_cover" class="layui-form-label">
                        <span class="x-red">*</span>视频封面
                    </label>
                    <div class="layui-input-inline" style="width: 250px;">
                        <input readonly type="text" id="L_video_cover" name="video_cover" required="" lay-verify="video_cover" autocomplete="off" class="layui-input"
                               value="<?= $model->video_cover; ?>"/>
                    </div>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="video_cover">封面上传</button>
                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                <div class="layui-upload-list" id="video_cover_preview">
                                    <img width="115" height="100" src="<?= $model->video_cover; ?>" alt="" class="layui-upload-img"/>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_video_link" class="layui-form-label">
                        <span class="x-red">*</span>视频链接
                    </label>
                    <div class="layui-input-inline" style="width: 250px;">
                        <input type="text" id="L_video_link" name="video_link" required="" lay-filter="video_link" lay-verify="video_link" autocomplete="off" class="layui-input"
                               value="<?= $model->video_link; ?>"/>
                    </div>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="video_link">视频上传</button>
                            <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                <div class="layui-upload-list" id="video_link_preview">
                                    <video controls="controls" width="115" height="100" src="<?= $model->video_link; ?>" alt="" class="layui-upload-img"/>
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

                let editIndex = layedit.build('L_article_content');


                //视频上传
                upload.render({
                    elem: '#video_link'
                    , url: 'upload.html'
                    , multiple: false
                    , accept: 'video'
                    , field: 'file'
                    , data: data
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#preview').html('<video controls="controls" width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                        });
                    }
                    , done: function (res) {
                        //上传完毕
                        console.log(res);
                        if (res.code === 200) {
                            console.log(res.filename);
                            $("input[name='video_link']").val(res.filename);
                        }
                    }
                });

                //图片上传
                upload.render({
                    elem: '#video_cover'
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
                            $('#video_cover_preview').html('<img width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                        });
                    }
                    , done: function (res) {
                        //上传完毕
                        console.log(res);
                        if (res.code === 200) {
                            console.log(res.filename);
                            $("input[name='video_cover']").val(res.filename);
                        }
                    }
                });

                //自定义验证规则
                form.verify({
                    year: function (value) {
                        if (value == 0) {
                            return '请选择年份';
                        }
                    },
                    goods_title: function (value) {
                        if (value.length < 2) {
                            return '请输入课程标题';
                        }
                    },
                    goods_link: function (value) {
                        if (value.length < 2) {
                            return '请输入课程链接';
                        }
                    },
                    goods_price: function (value) {
                        if (value.length < 2) {
                            return '请输入课程价格';
                        }
                    },
                    goods_cover: function (value) {
                        if (value.length < 2) {
                            return '请上传封面';
                        }
                    },
                });

                //监听提交
                form.on('submit(edit)',
                    function (data) {
                        console.log(data);
                        $.ajax({
                            url: "/jj/add.html",
                            method: "POST",
                            data: data.field,
                            success: function (res) {
                                console.log(res);
                                if (res.code === 200) {
                                    //发异步，把数据提交给php
                                    layer.alert("添加成功", {icon: 6}, () => {
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