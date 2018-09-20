<title>角色管理</title>
<div class="layui-fluid">
  	<div class="layui-row">
    	<div class="layui-col-xs12">
	      	<div class="layui-card">
	      		<div class="layui-card-header">角色管理</div>
				<div class="layui-card-body">
					<table class="layui-hidden" id="treeTable" lay-filter="treeTable"></table>
				</div> 
			</div>
		</div>
	</div>
</div>
<script>
var editObj=null,ptable=null,treeGrid=null,tableId='treeTable',layer=null,ptable;
    layui.use(['yycms','treeGrid','form'], function(){
        var $ = layui.$,yycms = layui.yycms,treeGrid=layui.treeGrid,form=layui.form;
        var tdata ={};
        var i=1000;
        ptable=treeGrid.render({
	          id:tableId
	          ,elem: '#'+tableId
	          ,idField:'id'
	          ,url:"{:url('index')}"
	          ,where:{
	              action:'tree'
	          }
	          ,cellMinWidth: 100
	          ,treeId:'id'//树形id字段名称
	          ,treeUpId:'parent_id'//树形父id字段名称
	          ,treeShowName:'name'//以树形式显示的字段
	          ,height:'100%'
	          ,toolbar: '#toolbarTpl'
	          ,isFilter:false
	          ,iconOpen:true//是否显示图标【默认显示】
	          ,isOpenDefault:true//节点默认是展开还是折叠【默认展开】
	          ,cols: [[
	              	  {type:'numbers', title: '序号'}
	              	  ,{field: 'icon', title: '图标', width: 60, align:'center',templet: function(d){
		                      return '<i class="'+d.icon+'"></i>';
		                  }
		              }
				      ,{field: 'name', title: '菜单名称', minWidth: 300, edit:true}
				      ,{field: 'app', title: '应用', width: 80, edit:true,align:'center'}
				      ,{field: 'model', title: '控制器', width: 80, edit:true,align:'center'}
				      ,{field: 'action', title: '方法', width: 110, edit:true,align:'center'}
				      ,{field: 'request', title: '日志请求', width: 110, edit:true,align:'center'}
				      ,{field: 'list_order', title: '排序', width: 80, edit:true,align:'center'}
				      ,{field: 'status', title: '状态', width: 100, templet: '#tplStatus',align:'center', templet: function(d){
	                        return '<input type="checkbox" name="status" value="'+d.id+'" lay-skin="switch" lay-text="正常|禁用" lay-filter="switch"'+function() {
	                                    return d.status == 1 ? 'checked' : '';
	                                }()+'>';
	                        }
	                    }
	              	  ,{width:220,title: '操作', align:'center',templet: function(d){
		                      var html='';
		                      var iconBtn='<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="icon">设置图标</a>';
		                      var addBtn='<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="add">添加子项</a>';
		                      var delBtn='<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';
		                      return iconBtn+addBtn+delBtn;
		                  }
		              }
	          ]]
	          ,parseData:function (res) {
	          //数据加载后回调
	              tdata = res.data;
	          }
      	});

      treeGrid.on('tool('+tableId+')',function (obj) {
          if(obj.event === 'del'){
          //删除行
              del(obj);
          }else if(obj.event==="add"){
          //添加行
              var pdata=obj?obj.data:null;
              var param={};
              param.name='水果'+Math.random();
              param.id=++i;
              param.pId=pdata?pdata.id:null;
              treeGrid.addRow(tableId,pdata?pdata[treeGrid.config.indexName]+1:0,param);
          }
      });
      //头工具栏事件
      treeGrid.on('toolbar('+tableId+')', function(obj){
          var checkStatus = treeGrid.checkStatus(obj.config.id);
          switch(obj.event){
              case 'clear':
                  layer.confirm('您确定要清空吗？', function(index){
					layer.close(index);
		            var loading = layer.load(1, {shade: [0.1, '#fff']});
		            admin.req({
			        	url:"{:url('auth/cache')}"
			        	,type:'post'
			        	,success:function(res){
			        		layer.close(loading);
			        		if(res.code==1){
				                layer.msg(res.msg,{time:1000,icon:1});
				            }
			        	}
			        });
		            loading ? layer.close(loading) :'';
		        });
                  break;
              case 'add':
                   var pdata=obj?obj.data:null;
	              var param={};
	              param.name='新增'+Math.random();
	              param.id=++i;
	              param.pId=pdata?pdata.id:null;
	              treeGrid.addRow(tableId,pdata?pdata[treeGrid.config.indexName]+1:0,param);
                  break;
              case 'isAll':
                  yycms.msg(checkStatus.isAll ? '全选': '未全选');
                  break;
          };
      });
      	treeGrid.on('edit('+tableId+')', function(obj){
          var val = obj.value,data = obj.data,field = obj.field,posturl = "{:url('menuEdit')}";
          data[field] = val;
          yycms.req({
              url:posturl,
              type:'post',
              data:data
          })
      	});
      	treeGrid.on('tool('+tableId+')', function(obj){
      		
      		switch(obj.event){
      			case 'del':
                  layer.confirm("你确定删除数据吗？如果存在下级节点则一并删除，此操作不能撤销！", {icon: 3, title:'提示'},
			          function(index){
			          //确定回调
			              obj.del();
			              layer.close(index);
			          },function (index) {
			          //取消回调
			              layer.close(index);
			          }
			      );
                  break;
                case 'icon':
                	var url= "{:url('public/icon')}?id="+obj.data.id
                  	yycms.dialog_iframe('<div data-height="90%" data-width="760" data-href="'+url+'"></div>');
                  break;
                case 'add':
                  var pdata=obj?obj.data:null;
	              var param={};
	              param.name='新增'+Math.random();
	              param.id=++i;
	              param.pId=pdata?pdata.id:null;
	              treeGrid.addRow(tableId,pdata?pdata[treeGrid.config.indexName]+1:0,param);
                  break;
      		}
      	});
		/**
       * switch表单事件
       */
      form.on('switch(switch)', function(obj){

          var posturl = "{:url('menuEdit')}",value = obj.elem.checked===true?1:0,id = $(this).attr('value'),data={};
          data = {
          	id:id,
          	field:true,
          	status:value
          };
          yycms.req({
              url:posturl,
              type:'post',
              data:data
          })
      });
    });
</script>

<script type="text/html" id="toolbarTpl">
	<div class="layui-btn-group" id="authtool">
    	<a class="layui-btn layui-btn-xs layui-btn-normal " lay-event="add" data-href="{:url('menuAdd')}">添加 </a>
    	<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="clear" data-href="{:url('clear')}">清空日志记录 </a>
	</div>
</script>

