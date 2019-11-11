<?php

    use common\widgets\JsBlock;

    /**
     * @var $option
     * @var $Cate
     * @var $answerOptionList
     */

?>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" method="post" id="layui-form">

                <div class="layui-form-item">
                    <label for="L_cate_id" class="layui-form-label">
                        <span class="x-red">*</span>题目分类
                    </label>
                    <div class="layui-input-inline">
                        <input readonly type="text" id="L_cate_id" name="cate_id" required="" lay-filter="cate_id" lay-verify="cate_id"
                               autocomplete="off" class="layui-input"
                               value="<?= $model->cate->cate_name; ?>"/>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red"></span>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label for="L_title" class="layui-form-label">
                        <span class="x-red">*</span>题目标题
                    </label>
                    <div class="layui-input-block">
                        <textarea type="text" id="L_title" name="title" required="" lay-filter="title" lay-verify="title" autocomplete="off"
                                  class="layui-textarea"><?= $model->title; ?></textarea>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label for="L_content" class="layui-form-label">
                        <span class="x-red">*</span>题目内容
                    </label>
                    <div class="layui-input-block">
                        <textarea type="text" id="L_content" name="content" required="" lay-filter="content" lay-verify="content" autocomplete="off"
                                  class="layui-textarea"><?= $model->content; ?></textarea>
                    </div>
                    <div class="layui-form-mid layui-word-aux" style="margin-left: 110px;">
                        <span class="x-red"></span>题目当中的占位符请用#wj#
                    </div>
                </div>

                <?php if (in_array($model->cate_id, [18])): ?>
                    <div class="layui-form-item">
                        <label for="L_img_link" class="layui-form-label">
                            <span class="x-red">*</span>题目图片
                        </label>
                        <div class="layui-input-inline" style="width: 250px;">
                            <input readonly type="text" id="L_img_link" name="img_link" required="" lay-verify="img_link" autocomplete="off"
                                   class="layui-input"
                                   value="<?= $model->img_link; ?>"/>
                        </div>
                        <div class="layui-input-inline">
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="img_link">上传</button>
                                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                    <div class="layui-upload-list" id="img_link_preview">
                                        <img width="115" height="100" src="<?= $model->img_link; ?>" alt="" class="layui-upload-img"/>
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (in_array($model->cate_id, [5, 7, 8, 14, 15, 19])): ?>
                    <div class="layui-form-item">
                        <label for="L_audio_link" class="layui-form-label">
                            <span class="x-red">*</span>题目音频
                        </label>
                        <div class="layui-input-inline" style="width: 250px;">
                            <input readonly type="text" id="L_audio_link" name="audio_link" required="" lay-filter="audio_link"
                                   lay-verify="audio_link" autocomplete="off" class="layui-input"
                                   value="<?= $model->audio_link; ?>"/>
                        </div>
                        <div class="layui-input-inline">
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="audio_link">上传</button>
                                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                    <div class="layui-upload-list" id="audio_link_preview">
                                        <video controls="controls" width="115" height="100" src="" alt="" class="layui-upload-img"></video>
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (in_array($model->cate_id, [13])): ?>
                    <div class="layui-form-item">
                        <label for="L_answer_option" class="layui-form-label">
                            <span class="x-red">*</span>答案选项
                        </label>
                        <div class="layui-input-block">
                            <textarea id="L_answer_option" name="answer_option" required="" lay-filter="answer_option" lay-verify="cate_id"
                                      autocomplete="off" class="layui-textarea"><?= $answerOptionList; ?></textarea>
                        </div>
                        <div class="layui-form-mid layui-word-aux" style="margin-left: 110px;">
                            <span class="x-red"></span>每组选项请用英文|隔开(xxx,ttt|bbb,ccc|ee,gg,xxx|ttt,rrr), 选项当中的每项用英文逗号隔开, 逗号请一定输入英文的逗号
                        </div>
                    </div>
                <?php endif; ?>
                <div class="layui-form-item layui-form-text">
                    <label for="L_answer_content" class="layui-form-label">
                        <span class="x-red">*</span> <?php if (in_array($model->cate_id, [11, 12, 13])): ?>答案列表<?php else: ?>文字答案<?php endif; ?>
                    </label>
                    <div class="layui-input-block">
                        <textarea type="text" id="L_answer_content" name="answer_content" required="" lay-filter="answer_content"
                                  lay-verify="answer_content" autocomplete="off"
                                  class="layui-textarea"><?= $model->answer ? ($model->answer)[0]->content : ''; ?></textarea>
                    </div>
                    <div class="layui-form-mid layui-word-aux" style="margin-left: 110px;">
                        <span class="x-red"></span>答案请用英文逗号隔开(xxx,ttt,bbb,ccc)    当中的逗号请一定输入英文的逗号
                    </div>
                </div>

                <?php if (in_array($model->cate_id, [5, 7, 8])): ?>
                    <div class="layui-form-item">
                        <label for="L_answer_audio_link" class="layui-form-label">
                            <span class="x-red">*</span>音频答案
                        </label>
                        <div class="layui-input-inline" style="width: 250px;">
                            <input readonly type="text" id="L_answer_audio_link" name="answer_audio_link" required="" lay-filter="answer_audio_link"
                                   lay-verify="answer_audio_link" autocomplete="off" class="layui-input"
                                   value="<?= $model->answer ? ($model->answer)[0]->audio_link : ''; ?>"/>
                        </div>
                        <div class="layui-input-inline">
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="answer_audio_link">上传</button>
                                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                    <div class="layui-upload-list" id="answer_audio_link_preview">
                                        <video controls="controls" width="115" height="100" src="" alt="" class="layui-upload-img"></video>
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="cid" value="<?= $model->cate_id; ?>"/>
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
                        , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'image_alt', 'video', 'anchors'
                        , '|', 'table', 'fullScreen'
                    ]
                    , uploadImage: {
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

                let L_title = layedit.build('L_title');
                let L_content = layedit.build('L_content');
                // let L_answer_content = layedit.build('L_answer_content');


                //音频上传
                upload.render({
                    elem: '#audio_link'
                    , url: 'upload.html'
                    , multiple: false
                    , accept: 'video'
                    , field: 'file'
                    , data: data
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#audio_link_preview').html('<video controls="controls" width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                        });
                    }
                    , done: function (res) {
                        //上传完毕
                        console.log(res);
                        if (res.code === 200) {
                            console.log(res.filename);
                            $("input[name='audio_link']").val(res.filename);
                        }
                    }
                });

                //音频答案上传
                upload.render({
                    elem: '#answer_audio_link'
                    , url: 'upload.html'
                    , multiple: false
                    , accept: 'video'
                    , field: 'file'
                    , data: data
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#answer_audio_link_preview').html('<video controls="controls" width="115" height="100" src="' + result + '" alt="' + file.name + '" class="layui-upload-img">')
                        });
                    }
                    , done: function (res) {
                        //上传完毕
                        console.log(res);
                        if (res.code === 200) {
                            console.log(res.filename);
                            $("input[name='answer_audio_link']").val(res.filename);
                        }
                    }
                });

                //题目图片上传
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
                    cate_id: function (value) {
                        if (value == 0) {
                            return '课程分类有误';
                        }
                    },
                    title: function (value) {
                        layedit.sync(L_title);
                        let content = layedit.getContent(L_title);
                        if ($.trim(content).length < 10) {
                            return '题目标题至少10个字符';
                        }
                    },
                    content: function (value) {
                        layedit.sync(L_content);
                        let content = layedit.getContent(L_content);
                        if ($.trim(content).length < 10) {
                            return '题目内容至少10个字符';
                        }
                    },
                    audio_link: function (value) {
                        if ($.trim(value).length < 26) {
                            return '题目音频至少26个字符';
                        }
                    },
                    answer_content: function (value) {
                        // layedit.sync(L_answer_content);
                        // let content = layedit.getContent(L_answer_content);
                        if ($.trim(value).length < 10) {
                            return '请填写文字答案, 不少于10个字符';
                        }
                    },
                    answer_audio_link: function (value) {
                        if ($.trim(value).length < 26) {
                            return '请上传音频答案';
                        }
                    },
                });

                //监听提交
                form.on('submit(edit)',
                    function (data) {
                        console.log(data);
                        $.ajax({
                            url: "/exercise/edit.html?id=<?=$model->id;?>",
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