<title>模型管理</title>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">
            <div class="layui-card">
                <div class="layui-card-header">模型管理</div>
                <div class="layui-card-body">
                    <div id="yycms-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['yycms','form'], function(){
        var $ = layui.$,yycms = layui.yycms,form=layui.form;
        var tableIns = yycms.table({
            config:{

            }
            ,table: {
                url: "{:url('admin/module/index')}",
                pk:'id',
                editurl: "{:url('edit')}", //数据操作修改地址
                token:'{:session("user_sign")}',
                toolbar:[
                    {type:'search'},
                    {type:'delall',url:"{:url('admin/module/del')}"},
                    {type:'dialog',title:'添加',class:'layui-btn-normal',url:"{:url('admin/module/add')}"},
                ],
                cols: [[
                    {type:'checkbox'},
                    {type: 'numbers', title: '序号', align: 'center', width: 80},
                    {field: 'title', title: '模型名称', width: 150, edit: true},
                    {field: 'name', title: '数据表名', width: 150},
                    {field: 'description', title: '描述', minWidth: 200, edit: true},
                    {field: 'listorder', title: '排序', edit: true, width: 80, align: 'center'},
                    {field: 'status', title: '状态', width: 100, align: 'center',switchTpl:"正常|禁用"},
                    {title: '操作', width: 190, align: 'center', toolbar: [
                            {type: 'del', url: "{:url('admin/module/del')}"},
                            {type: 'dialog', title: '编辑', url: "{:url('admin/module/edit')}"},
                            {type: 'link', title: '设置', url: "{:url('admin/module/set')}", class: 'layui-btn-normal'},
                        ]
                    }
                ]],
            }
        });

    });
</script>