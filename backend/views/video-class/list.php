<?php

    use common\widgets\JsBlock;
    use yii\widgets\LinkPager;

    /*  @var $list */
    /* @var $pages */

?>

    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="javascript:void(0);">精品课程</a>
            <a>
              <cite>课程列表</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5" method="get" action="/video-class/list.html">
                            <!--<div class="layui-inline layui-show-xs-block">-->
                            <!--    <input class="layui-input" autocomplete="off" placeholder="开始日" name="start" id="start">-->
                            <!--</div>-->
                            <!--<div class="layui-inline layui-show-xs-block">-->
                            <!--    <input class="layui-input" autocomplete="off" placeholder="截止日" name="end" id="end">-->
                            <!--</div>-->
                            <!--<div class="layui-inline layui-show-xs-block">-->
                            <!--    <input type="text" name="username" value="-->
                            <? //=$keywords;?><!--" placeholder="请输入用户名" autocomplete="off" class="layui-input">-->
                            <!--</div>-->
                            <!--<div class="layui-inline layui-show-xs-block">-->
                            <!--    <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>-->
                            <!--</div>-->
                        </form>
                    </div>
                    <div class="layui-card-header">

                        <button class="layui-btn layui-btn-danger" onclick="video_operation_all(2)"><i class="layui-icon"></i>批量下架</button>
                        <button class="layui-btn layui-btn-success" onclick="video_operation_all(1)"><i class="layui-icon"></i>批量上架</button>

                        <button class="layui-btn" onclick="xadmin.open('添加课程','add.html')"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body layui-table-body layui-table-main">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" lay-filter="checkall" name="" lay-skin="primary">
                                </th>
                                <th>ID</th>
                                <th>课程名称</th>
                                <th>课程封面</th>
                                <th>课程分类</th>
                                <th>课程链接</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list as $key => $value): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="id" value="<?= $value->id; ?>" lay-skin="primary">
                                    </td>
                                    <td><?= $value->id; ?></td>
                                    <td><?= $value->video_title; ?></td>
                                    <td><?= $value->video_cover; ?></td>
                                    <td><?= $value->cate->cate_name; ?></td>
                                    <td><?= $value->video_link; ?></td>
                                    <td class="td-status">
                                        <?php if ($value->status == 1): ?>
                                            <span class="layui-btn layui-btn-normal layui-btn-mini">已上架</span>
                                        <?php elseif ($value->status == 2): ?>
                                            <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已下架</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="td-manage">
                                        <!--<a onclick="member_stop(this,'10001')" href="javascript:;" title="启用">-->
                                        <!--    <i class="layui-icon">&#xe601;</i>-->
                                        <!--</a>-->
                                        <a title="编辑" onclick="xadmin.open('编辑','edit.html?id=<?= $value->id; ?>')" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <!--<a onclick="xadmin.open('修改密码','member-password.html',600,400)" title="修改密码" href="javascript:;">-->
                                        <!--    <i class="layui-icon">&#xe631;</i>-->
                                        <!--</a>-->
                                        <? if ($value->status == 1): ?>
                                            <a title="下架" onclick="video_operation(this,'<?= $value->id; ?>', 2)" href="javascript:void(0);">
                                                <i class="layui-icon">&#xe640;</i>
                                            </a>
                                        <?php elseif ($value->status == 2): ?>
                                            <a title="上架" onclick="video_operation(this,'<?= $value->id; ?>', 1)" href="javascript:void(0);">
                                                <i class="layui-icon">&#xe669;</i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">

                        <div class="page">
                            <?= LinkPager::widget(
                                [
                                    'pagination'         => $pages,
                                    'activePageCssClass' => 'current'
                                ]
                            ); ?>
                            <!--<div>-->
                            <!--    <a class="prev" href="">&lt;&lt;</a>-->
                            <!--    <a class="num" href="">1</a>-->
                            <!--    <span class="current">2</span>-->
                            <!--    <a class="num" href="">3</a>-->
                            <!--    <a class="num" href="">489</a>-->
                            <!--    <a class="next" href="">&gt;&gt;</a>-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .page li.current a {
            display: inline-block;
            background: #009688;
            color: #fff;
            padding: 5px;
            min-width: 15px;
            border: 1px solid #009688;
        }
    </style>

<?php JsBlock::begin(); ?>
    <script>
        layui.use(['laydate', 'form'], function () {
            var laydate = layui.laydate;
            var form = layui.form;
            // 监听全选
            form.on('checkbox(checkall)', function (data) {

                if (data.elem.checked) {
                    $('tbody input').prop('checked', true);
                } else {
                    $('tbody input').prop('checked', false);
                }
                form.render('checkbox');
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });


        });

        /*用户-停用*/
        function member_stop(obj, id) {
            layer.confirm('确认要停用吗？', function (index) {

                if ($(obj).attr('title') == '启用') {

                    //发异步把用户状态进行更改
                    $(obj).attr('title', '停用')
                    $(obj).find('i').html('&#xe62f;');

                    $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                    layer.msg('已停用!', {icon: 5, time: 1000});

                } else {
                    $(obj).attr('title', '启用')
                    $(obj).find('i').html('&#xe601;');

                    $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                    layer.msg('已启用!', {icon: 5, time: 1000});
                }

            });
        }

        /*课程上下架操作*/
        function video_operation(_this, id, type) {
            if ($.inArray(type, [1, 2]) <= -1) {
                return false;
            }
            let msg,
                url = null;
            if (type === 1) {
                msg = '上架';
                url = "/video-class/start.html";
            } else if (type === 2) {
                msg = '下架';
                url = "/video-class/del.html";
            } else {
                return false;
            }
            layer.confirm('确认要' + msg + '吗?', function (index) {
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {id: [id], "<?=Yii::$app->request->csrfParam;?>": "<?=Yii::$app->request->csrfToken;?>"},
                    success: function (res) {
                        console.log(res);
                        if (res.code === 200) {
                            //发异步删除数据
                            // $(obj).parents("tr").remove();
                            let obj = $(_this).parents("tr").find(".td-status").find('span');
                            if (type === 1) {
                                obj.removeClass('layui-btn-disabled')
                            } else if (type === 2) {
                                obj.addClass('layui-btn-disabled')
                            }
                            obj.html('已' + msg);
                            layer.msg(msg + '成功!', {icon: 1, time: 1000}, function () {
                                xadmin.father_reload();
                            });
                        } else {
                            layer.msg(res.msg, {
                                icon: 2
                                , time: 8 * 1000,
                                shift: 6
                            });
                        }
                    }
                });


            });
        }


        function video_operation_all(type) {
            if ($.inArray(type, [1, 2]) <= -1) {
                return false;
            }
            let msg,
                url = null;
            if (type === 1) {
                msg = '上架';
                url = "/video-class/start.html";
            } else if (type === 2) {
                msg = '下架';
                url = "/video-class/del.html";
            } else {
                return false;
            }

            let ids = [];
            // 获取选中的id
            $('tbody input').each(function (index, el) {
                if ($(this).prop('checked')) {
                    ids.push($(this).val())
                }
            });

            if (ids.length <= 0) {
                layer.msg('请选择要' + msg + '的课程!', {icon: 5, time: 1000});
                return false;
            }

            layer.confirm('确认要' + msg + '吗?' + ids.toString(), function (index) {
                //捉到所有被选中的，发异步进行删除
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {id: ids, "<?=Yii::$app->request->csrfParam;?>": "<?=Yii::$app->request->csrfToken;?>"},
                    success: function (res) {
                        console.log(res);
                        if (res.code === 200) {
                            //发异步删除数据
                            // $(obj).parents("tr").remove();
                            let obj = $(".layui-form-checked").not('.header').parents('tr').find(".td-status").find('span');
                            if (type === 1) {
                                obj.removeClass('layui-btn-disabled');
                            } else if (type === 2) {
                                obj.addClass('layui-btn-disabled');
                            }
                            obj.html('已' + msg);
                            layer.msg(msg + '成功!', {icon: 1, time: 1000}, function () {
                                xadmin.father_reload();
                            });
                        } else {
                            layer.msg(res.msg, {
                                icon: 2
                                , time: 8 * 1000,
                                shift: 6
                            });
                        }
                    }
                });
                // layer.msg('停用成功', {icon: 1});
                // $(".layui-form-checked").not('.header').parents('tr').remove();
            });
        }
    </script>
<?php JsBlock::end(); ?>