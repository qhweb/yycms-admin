<title>角色管理</title>
<div class="layui-fluid">
  	<div class="layui-row">
    	<div class="layui-col-xs12">
	      	<div class="layui-card">
	      		<div class="layui-card-header">角色管理</div>
				<div class="layui-card-body">
					<div id="yycms-table"></div>
				</div> 
			</div>
		</div>
	</div>
</div>
<script>
    layui.use(['yycms'], function(){
        var $ = layui.$,yycms = layui.yycms;
        var tableIns = yycms.table({
            config:{
                addurl: "{:url('roleAdd')}", // 数据操作添加地址
                editurl: "{:url('roleEdit')}", //数据操作修改地址
                delurl: "{:url('roleDelete')}"//数据操作删除地址
            }
            ,table:{
                url: "{:url('index')}",
                where:{
                	key:''
                },
                cols: [[
                    {field: 'id', title: 'ID', width: 60, sort:true,align:'center'}
                    ,{field: 'name', title: '角色名称', width: 150, edit:true}
                    ,{field: 'remark', title: '角色描述', minWidth: 300, edit:true}
                    ,{field: 'status', title: '状态', width: 100,align:'center', templet: function(d){
                        return '<input type="checkbox" name="status" value="'+d.id+'" lay-skin="switch" lay-text="正常|禁用" lay-filter="switch"'+function() {
                                    return d.status == 1 ? 'checked' : '';
                                }()+'>';
                        }
                    }
                    ,{title: '操作', width: 220, align:'center',templet:function(d){
                        if (d.id != 1) {
                            return  ['<a class="layui-btn layui-btn-sm layui-btn-normal " lay-event="edit" data-type="dialog" data-width="380" data-height="600" data-href="{:url('authorize')}/id/'+d.id+'">权限设置</a>'
                                ,'<a class="layui-btn layui-btn-sm"  lay-event="edit" data-type="dialog" data-width="550" data-height="350">编辑</a>'
                                ,'<a class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del">删除</a>'].join('');
                        }else{
                            return '';
                        }
                    }
                    }
                ]]
            }
            ,toolbar:[
                {type:'search'},
                { event:'adddialog',name:'添加',href:'',class:'layui-btn-normal',height:350,width:550},
            ]
        });
    });
</script>