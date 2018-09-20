<div class="layui-fluid">
  	<div class="layui-row layui-col-space15">
    	<div class="layui-col-md12">
	      	<div class="layui-card">
	      		<div class="layui-card-header">
	      			<span class="yycms-left">管理员管理</span> 
	      			<span class="yycms-right">
	      				<button type="button" yycms-event="dialog" data-href="{:url('auth/adminUserAdd')}" data-width="450" data-height="500" class="layui-btn layui-btn-xs">添加管理员</button>
	      			</span>
	      		</div>
				<div class="layui-card-body">
					<div class="layui-inline">
				       <input class="layui-input" style="height:30px;line-height:30px" id="LAY_keyword" placeholder="请输入搜索关键字">
				    </div>
				    <div class="layui-btn-group">
					    <button class="layui-btn layui-btn-sm" yycms-event="search">搜索</button>
					    <button class="layui-btn layui-btn-sm" yycms-event="searchall">全部</button>
				    </div>
					<table id="list" lay-filter="list"></table>
				</div> 
			</div>
		</div>
	</div>
</div>
<script type="text/html" id="toolbar">
	<div class="layui-btn-group" id="authtool">
		{{# if(d.id == 1){ }}
			
        {{# }else{ }}
        	<a class="layui-btn layui-btn-xs layui-btn-normal " yycms-event="dialog" data-width="350" data-height="600" data-href="{:url('auth/adminAuthorize')}/id/{{d.id}}">独立权限 </a>
        	<a class="layui-btn layui-btn-xs"  yycms-event="dialog" data-width="450" data-height="500" data-href="{:url('auth/adminuserEdit')}/id/{{d.id}}">编辑 </a>
        	<a class="layui-btn layui-btn-xs layui-btn-danger" yycms-event="delete" data-href="{:url('auth/adminuserDelete')}/id/{{d.id}}">删除 </a>
        {{# } }}
	</div>
</script>

<script>
layui.use(['admin','table','form'], function(){
	var $ = layui.$,admin = layui.admin,table=layui.table,form=layui.form;
	var statusTpl = function(d){
		checked = d.user_status == 1 ? 'checked' : '';
	    return '<input type="checkbox" name="user_status" value="'+d.id+'" lay-skin="switch" lay-text="启用|禁用" lay-filter="status" '+checked+'>';
	}
	var tableid = table.render({
		elem:"#list"
		,url: "{:url('adminUserData')}" //模拟接口
		,page: true
		,height:'full-292'
	    ,cols: [[
	      {field: 'id', title: 'ID', width: 60, sort:true,align:'center'}
	      ,{field: 'user_name', title: '用户名', width: 100}
	      ,{field: 'user_nicename', title: '昵称'}
	      ,{field: 'user_email', title: '邮箱', width: 180}
	      ,{field: 'last_login_ip', title: '最后登录IP', width: 140}
	      ,{field: 'last_login_time', title: '最后登录时间', width: 180}
	      ,{field: 'user_status', title: '状态', width: 100, templet: statusTpl,align:'center'}
	      ,{title: '操作', width: 160, toolbar: "#toolbar",align:'center'}
	    ]]
	    ,limit:15
	    ,limits:[15, 30, 45, 60, 100]
 	});
 	form.render();
 	form.on('submit(dialog)', function (obj) {
    	var that = $(this);
		var href = that.data('action');
        admin.req({
        	url:href||''
        	,type:'post'
        	,data:obj.field
        	,success:function(res){
        		if(res.code){
	                layer.msg(res.msg,{time:1000,icon:1});
	                layer.closeAll();
	                tableid ? tableid.reload():layui.index.render();
	            }
        	}
        });
        return false;
    });
    var active = {
		search : function(){
    		var key = $('#LAY_keyword').val();
	        if ($.trim(key) === '') {
	            layer.msg('请输入搜索关键字', {icon: 0});
	            return;
	        }
	        tableid.reload({
	            where: {key: key}
	        });
	    }
		,searchall : function(){
	    	$('#LAY_keyword').val('');
	        tableid.reload({
	            where: ''
	        });
	    }
	}
    $('body').on('click','*[yycms-event]',function(){
	    var othis = $(this)
	    	,type = othis.attr('yycms-event');
	    active[type] ? active[type].call(this) : '';
	});
});
</script>