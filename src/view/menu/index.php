<title>菜单管理</title>
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-xs12">
            <div class="layui-card">
                <div class="layui-card-header">菜单管理</div>
                <div class="layui-card-body">
                    <div id="yycms-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use('yycms', function(){
      var $ = layui.$,yycms = layui.yycms;
      var tableIns = yycms.table({
          config:{}
          ,table:{
            token:"{:session('user_sign')}"
            ,pk:'id'
            ,url:"{:url('index')}"
            ,editurl:"{:url('Edit')}"
            ,where:{
                table:'tree'
            }
            ,toolbar:[
              {type:'search'},
              {type:"dialog",url:"{:url('Add')}",class:'layui-btn-normal',title:'添加',width:700,height:600},
              {type:"del",url:"{:url('clear')}",title:'清空日志记录'}
            ]
            ,cols: [[
                    {type:'numbers', title: '序号'}
                    ,{field: 'icon', title: '图标', width: 60, align:'center',templet: function(d){
                          return '<i class="'+d.icon+'"></i>';
                      }
                  }
              ,{field: 'name', title: '菜单名称', minWidth: 300, edit:true,templet:function(d){
                  return d.ltitle
                }
              }
              ,{field: 'app', title: '应用', width: 80, edit:true,align:'center'}
              ,{field: 'model', title: '控制器', width: 80, edit:true,align:'center'}
              ,{field: 'action', title: '方法', width: 110, edit:true,align:'center'}
              ,{field: 'request', title: '日志请求', width: 110, edit:true,align:'center'}
              ,{field: 'list_order', title: '排序', width: 80, edit:true,align:'center'}
              ,{field: 'status', title: '状态', width: 100, templet: '#tplStatus',align:'center', switchTpl:"正常|禁用"}
              ,{width:220,title: '操作', align:'center',toolbar:[
                  {type:"dialog",url:"{:url('Add')}",class:'',title:'添加子项',width:700,height:600},
                  {type:"dialog",url:"{:url('Edit')}",class:'layui-btn-normal',title:'修改',width:700,height:600},
                  {type:"del",url:"{:url('Del')}",title:'删除'}
                ]
              }
            ]]
          }
    	});
    })
</script>

