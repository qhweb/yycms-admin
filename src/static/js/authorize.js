layui.use(['jstree','yycms'],function(obj){
	var element = layui.element,$=layui.$,admin = layui.admin,form = layui.form;
	var menutree = $('#ruleTree');
	var ruleArray = $("#rules").val().split(',');
	form.render();
	menutree.jstree({
      'core' : {
        'data' : function (obj, callback) {
                var jsonstr="[]";
                var jsonarray = eval('('+jsonstr+')');
                var child = function(arrays){
                	for(var i=0 ; i<arrays.length; i++){
                        var arr = {
                            "id":arrays[i].id,
                            "parent":arrays[i].parent_id=="0"?"#":arrays[i].parent_id,
                            "text":arrays[i].name,
                            "icon":arrays[i].icon||'fa fa-file-text-o',
                            "children":child(arrays[i].children)
                        }
                        jsonarray.push(arr);
                    }
                }
                $.ajax({
                    type: "POST",
                    url:"{:url('menuData')}",
                    dataType:"json",
                    async: false,
                    success:function(result) {
                        var arrays= result.data;
                        for(var i=0 ; i<arrays.length; i++){
                            var arr = {
                                "id":arrays[i].id,
                                "parent":arrays[i].parent_id=="0"?"#":arrays[i].parent_id,
                                "text":arrays[i].name,
                                "icon":arrays[i].icon||'fa fa-file-text-o',
                                "children":child(arrays[i].children)
                            }
                            jsonarray.push(arr);
                        }
                    }

                });
                callback.call(this, jsonarray);
            }
        },
        "checkbox" : {
            "keep_selected_style" : false,
            "three_state":false,
        },
      	"plugins" : [ "search", "checkbox" ]
    }).on('activate_node.jstree', function (e, data) {
    	var index = $.inArray(data.node.id, ruleArray);
    	index == -1 ? ruleArray.push(data.node.id) :  ruleArray.splice(index,1) ;
	}).on("loaded.jstree", function (event, data) {
        menutree.jstree('select_node',[ruleArray],true);
        menutree.jstree().open_all();
    });

    $(".check_all").click(function(){
    	menutree.jstree().select_all();
    	ruleArray=menutree.jstree("get_selected");
    });
    $(".uncheck_all").click(function(){
    	menutree.jstree().deselect_all();
    	ruleArray=[];
    });
	//输入框输入定时自动搜索 
	var to = false; 
	$('#jstree_left_search').keyup(function () { 
	 	if(to) { 
	 		clearTimeout(to); 
	 	} 
	  
	 	to = setTimeout(function () { 
	 		menutree.jstree(true).search($('#jstree_left_search').val()); 
	 	}, 250); 
	});		
	form.on('submit(authorize)', function (obj) {
    	var that = $(this);
		var href = that.data('action');
		obj.field['rules'] = ruleArray.join(',');
        admin.req({
        	url:href||''
        	,type:'post'
        	,data:obj.field
        	,success:function(res){
        		if(res.code){
	                layer.msg(res.msg,{time:1000,icon:1});
	                layer.closeAll();
	            }
        	}
        });
        return false;
    });
})