<div class="layui-fluid">
  	<div class="layui-row">
      	<div class="layui-card">
      		<div class="layui-card-header">
      			<span class="yycms-left">日志列表</span>
      			<span class="yycms-right"><button class="layui-btn layui-btn-sm" yycms-event="clear"><i class="layui-icon" aria-hidden="true">&#xe640;</i>清空</button></span>
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
<script type="text/html" id="toolbar">
    <a data-href="{:url('auth/viewlog')}/id/{{d.id}}" class="layui-btn layui-btn-sm" yycms-event="dialog" data-width="500" data-height="400">日志详情</a>
</script>

<script>
layui.use(['admin','table','util'], function(){
	var $ = layui.$,admin = layui.admin,layer=layui.layer,table=layui.table;
	var format_time = function(d){
		return  layui.util.timeAgo(d.create_time);
	}
	var tableInd = table.render({
		elem:'#list'
		,url: "{:url('logData')}" //模拟接口
		,page: true
		,height:'full-292'
		,limit:15
	    ,cols: [[
	      {field: 'id', title: 'ID', width: 60, sort:true,align:'center'}
	      ,{field: 'title', title: '标题', width: 150,align:'center'}
	      ,{field: 'username', title: '用户', width: 100}
	      ,{field: 'log_url', title: '执行地址', align:'center'}
	      ,{field: 'action_ip', title: 'IP', width: 140, align:'center'}
	      ,{field: 'create_time', title: '执行时间', width: 180, align:'center',templet:format_time}
	      ,{title: '操作', width: 120, toolbar: "#toolbar",align:'center'}
	    ]]
	    ,limits:[15, 30, 45, 60, 100]
	});
	var active = {
		search : function(){
    		var key = $('#LAY_keyword').val();
	        if ($.trim(key) === '') {
	            layer.msg('请输入搜索关键字', {icon: 0});
	            return;
	        }
	        tableInd.reload({
	            where: {key: key}
	        });
	    }
		,searchall : function(){
	    	$('#LAY_keyword').val('');
	        tableInd.reload({
	            where: ''
	        });
	    }
	    ,clear : function(){
	    	layer.confirm('您确定要清空吗？', function(index){
				layer.close(index);
	            var loading = layer.load(1, {shade: [0.1, '#fff']});
	            admin.req({
		        	url:"{:url('auth/clear')}"
		        	,type:'post'
		        	,success:function(res){
		        		layer.close(loading);
		        		if(res.code==1){
			                layer.msg(res.msg,{time:1000,icon:1});
			                tableInd.reload();
			            }
		        	}
		        });
	            loading ? layer.close(loading) :'';
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