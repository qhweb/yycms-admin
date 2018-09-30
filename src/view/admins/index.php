<title>管理员管理</title>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">
            <div class="layui-card">
                <div class="layui-card-header">管理员管理</div>
                <div class="layui-card-body">
                    <div id="yycms-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="toolbar">
    <div class="layui-btn-group" id="authtool">
        {{# if(d.id == 1){ }}
        {{# }else{ }}
        <a class="layui-btn layui-btn-sm layui-btn-normal " lay-event="edit" data-type="dialog" data-width="390" data-height="600" data-href="{:url('authorize')}?id={{d.id}}">独立权限 </a>
        <a class="layui-btn layui-btn-sm"  lay-event="edit" data-type="dialog" data-width="400" data-height="450">编辑 </a>
        <a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del">删除 </a>
        {{# } }}
    </div>
</script>
<script>
    layui.use(['yycms'], function(){
        var $ = layui.$,yycms = layui.yycms;
        var statusTpl = function(d){
            checked = d.user_status == 1 ? 'checked' : '';
            return '<input type="checkbox" name="user_status" value="'+d.id+'" lay-skin="switch" lay-text="启用|禁用" lay-filter="switch" '+checked+'>';
        }
        var tableIns = yycms.table({
            config:{
                addurl: "{:url('add')}", // 数据操作添加地址
                editurl: "{:url('edit')}", //数据操作修改地址
                delurl: "{:url('del')}"//数据操作删除地址
            }
            ,table:{
                url: "{:url('index')}",
                pk:"id",
                token:'{:session("user_sign")}',
                editurl:"{:url('edit')}",
                toolbar:[
                    {type:'search'},
                    {type:'dialog',title:'添加',url:"{:url('add')}",class:'layui-btn-normal',width:400,height:450},
                ],
                cols: [[
                    {field: 'id', title: 'ID', width: 60, sort:true,align:'center'}
                    ,{field: 'user_name', title: '用户名', width: 100}
                    ,{field: 'user_nicename', title: '昵称', width: 100,edit:true}
                    ,{field: 'rolename', title: '角色'}
                    ,{field: 'user_email', title: '邮箱', width: 180,edit:true}
                    ,{field: 'last_login_ip', title: '最后登录IP', width: 140}
                    ,{field: 'last_login_time', title: '最后登录时间', width: 180}
                    ,{field: 'user_status', title: '状态', width: 120,align:'center',switchTpl:"启用|禁用"}
                    ,{title: '操作', width: 220, align:'center',templet:"#toolbar",toolbar:[
                        {type:'dialog',url:"{:url('authorize')}",title:"独立授权",width:400,height:600,class:'layui-btn-normal'},
                        {type:'dialog',url:"{:url('edit')}",title:"编辑",width:400,height:450},
                        {type:'del',url:"{:url('del')}",title:"删除"}
                    ]}
                ]]
            }
        });
    });
</script>