<?php

    use common\widgets\JsBlock;
    use yii\widgets\LinkPager;

    /*  @var $list */
    /* @var $pages */

?>

    <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">精品课程</a>
                <a>
                    <cite>课程分类</cite></a>
            </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>


    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <!--<div class="layui-card-body ">-->
                    <!--    <form class="layui-form layui-col-space5">-->
                    <!--        <div class="layui-input-inline layui-show-xs-block">-->
                    <!--            <input class="layui-input" placeholder="分类名" name="cate_name"/>-->
                    <!--        </div>-->
                    <!--        <div class="layui-input-inline layui-show-xs-block">-->
                    <!--            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加</button>-->
                    <!--        </div>-->
                    <!--    </form>-->
                    <!--    <hr>-->
                    <!--</div>-->
                    <!--<div class="layui-card-header">-->
                    <!--    <button class="layui-btn layui-btn-danger" onclick="delAll()">-->
                    <!--        <i class="layui-icon"></i>批量删除-->
                    <!--    </button>-->
                    <!--</div>-->
                    <div class="layui-card-header">
                        <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量停用</button>-->
                        <button class="layui-btn" onclick="xadmin.open('添加分类','cate-add.html',600,400)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <!--<th width="20">-->
                                <!--    <input type="checkbox" name="" lay-skin="primary">-->
                                <!--</th>-->
                                <th width="70">ID</th>
                                <th>栏目名</th>
                                <!--<th width="50">排序</th>-->
                                <!--<th width="80">状态</th>-->
                                <th width="250">操作</th>
                            </thead>
                            <tbody class="x-cate">
                            <?php foreach ($list as $key => $value): ?>
                                <tr cate-id='<?= $value['id']; ?>' fid='<?= $value['parent_id']; ?>'>
                                    <!--<td>-->
                                    <!--    <input type="checkbox" name="" lay-skin="primary">-->
                                    <!--</td>-->
                                    <td><?= $value['id']; ?></td>
                                    <td>
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                        <?= $value['cate_name']; ?>
                                    </td>
                                    <!--<td><input type="text" class="layui-input x-sort" name="order" value="--><?//=$value['id'];?><!--"></td>-->
                                    <!--<td>-->
                                    <!--    <input type="checkbox" name="switch" lay-text="开启|停用" checked="" lay-skin="switch">-->
                                    <!--</td>-->
                                    <td class="td-manage">
                                        <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','cate-edit.html?id=<?=$value['id'];?>', 600, 400)">
                                            <i class="layui-icon">&#xe642;</i>编辑
                                        </button>
                                        <!--<button class="layui-btn layui-btn-warm layui-btn-xs" onclick="xadmin.open('编辑','admin-edit.html')">-->
                                        <!--    <i class="layui-icon">&#xe642;</i>添加子栏目-->
                                        <!--</button>-->
                                        <!--<button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,'要删除的id')" href="javascript:;">-->
                                        <!--    <i class="layui-icon">&#xe640;</i>删除-->
                                        <!--</button>-->
                                    </td>
                                </tr>
                                <?php if ($value['child']): ?>
                                    <?php foreach ($value['child'] as $ke => $val): ?>
                                        <tr cate-id='<?= $val['id']; ?>' fid='<?= $val['parent_id']; ?>'>
                                            <!--<td>-->
                                            <!--    <input type="checkbox" name="" lay-skin="primary">-->
                                            <!--</td>-->
                                            <td><?= $val['id']; ?></td>
                                            <td>
                                                &nbsp;&nbsp;&nbsp;&nbsp; |-<?= $val['cate_name']; ?>
                                            </td>
                                            <!--<td><input type="text" class="layui-input x-sort" name="order" value="0"></td>-->
                                            <!--<td>-->
                                            <!--    <input type="checkbox" name="switch" lay-text="开启|停用" checked="" lay-skin="switch">-->
                                            <!--</td>-->
                                            <td class="td-manage">
                                                <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','cate-edit.html?id=<?=$val['id'];?>', 600, 400)">
                                                    <i class="layui-icon">&#xe642;</i>编辑
                                                </button>
                                                <!--<button class="layui-btn layui-btn-warm layui-btn-xs" onclick="xadmin.open('编辑','admin-edit.html')">-->
                                                <!--    <i class="layui-icon">&#xe642;</i>添加子栏目-->
                                                <!--</button>-->
                                                <!--<button class="layui-btn-danger layui-btn layui-btn-xs" onclick="member_del(this,'要删除的id')" href="javascript:;">-->
                                                <!--    <i class="layui-icon">&#xe640;</i>删除-->
                                                <!--</button>-->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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

        layui.use(['form'], function () {
            let form = layui.form;
            let $ = layui.jquery;

            // 分类展开收起的分类的逻辑
            $(function () {
                $("tbody.x-cate tr[fid!='0']").hide();
                // 栏目多级显示效果
                $('.x-show').click(function () {
                    let cateId = null;
                    if ($(this).attr('status') === 'true') {
                        $(this).html('&#xe625;');
                        $(this).attr('status', 'false');
                        cateId = $(this).parents('tr').attr('cate-id');
                        $("tbody tr[fid=" + cateId + "]").show();
                    } else {
                        cateIds = [];
                        $(this).html('&#xe623;');
                        $(this).attr('status', 'true');
                        cateId = $(this).parents('tr').attr('cate-id');
                        getCateId(cateId);
                        for (let i in cateIds) {
                            $("tbody tr[cate-id=" + cateIds[i] + "]").hide().find('.x-show').html('&#xe623;').attr('status', 'true');
                        }
                    }
                })
            });

            let cateIds = [];

            function getCateId(cateId) {
                $("tbody tr[fid=" + cateId + "]").each(function (index, el) {
                    let id = $(el).attr('cate-id');
                    cateIds.push(id);
                    getCateId(id);
                });
            }
        });


        /////////////////////////////////////////////////////////////
        layui.use(['laydate', 'form'], function () {
            let laydate = layui.laydate;
            let form = layui.form;
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

        /*用户-删除*/
        function member_del(obj, id) {
            layer.confirm('确认要停用吗？', function (index) {
                $.ajax({
                    url: "/goods/del.html",
                    method: "POST",
                    data: {id: [id], "<?=Yii::$app->request->csrfParam;?>": "<?=Yii::$app->request->csrfToken;?>"},
                    success: function (res) {
                        console.log(res);
                        if (res.code === 200) {
                            //发异步删除数据
                            // $(obj).parents("tr").remove();
                            $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                            layer.msg('停用成功!', {icon: 1, time: 1000});
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


        function delAll(argument) {
            let ids = [];

            // 获取选中的id
            $('tbody input').each(function (index, el) {
                if ($(this).prop('checked')) {
                    ids.push($(this).val())
                }
            });

            if (ids.length <= 0) {
                layer.msg('请选择要停用的用户!', {icon: 5, time: 1000});
                return false;
            }

            layer.confirm('确认要停用吗?' + ids.toString(), function (index) {
                //捉到所有被选中的，发异步进行删除
                $.ajax({
                    url: "/goods/del.html",
                    method: "POST",
                    data: {id: ids, "<?=Yii::$app->request->csrfParam;?>": "<?=Yii::$app->request->csrfToken;?>"},
                    success: function (res) {
                        console.log(res);
                        if (res.code === 200) {
                            //发异步删除数据
                            // $(obj).parents("tr").remove();
                            $(".layui-form-checked").not('.header').parents('tr').find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                            layer.msg('停用成功!', {icon: 1, time: 1000}, function () {
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