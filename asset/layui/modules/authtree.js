// 节点树
layui.define(['jquery', 'form', 'admin'], function(exports){
	$ = layui.jquery;
	form = layui.form;
	admin = layui.admin;

	obj = {
		// 渲染 + 绑定事件
		/**
		 * 渲染DOM并绑定事件
		 * @param  {[type]} dst       [目标ID，如：#test1]
		 * @param  {[type]} trees     [数据，格式：{}]
		 * @param  {[type]} inputname [上传表单名]
		 * @param  {[type]} layfilter [lay-filter的值]
		 * @param  {[type]} openall [默认展开全部]
		 * @return {[type]}           [description]
		 */
		render:function(opt){
			var data = '';
			if(typeof opt.nodes === 'object'){
	          obj.renderData(opt,opt.nodes[0]);
	        }else{
	        	admin.req({
		        	url:opt.url,
		        	type:'post',
		        	success:function(res){
		        		if(res.code==1){
			                obj.renderData(opt,res.data);
			            }else{
			                layer.msg(res.msg,{time:1000,icon:2});
			            }
		        	}
		        });
	        }
		}
		,renderData: function(opt,trees){
			var dst = opt.elem ? opt.elem : '#LAY_tree_auth';
			var inputname = opt.inputname ? opt.inputname : 'menuids[]';
			var layfilter = opt.layfilter ? opt.layfilter : 'checkauth';
			var openall = opt.openall ? opt.openall : false;
//			console.log(trees);
			$(dst).html(obj.renderAuth(trees, 0, {inputname: inputname, layfilter: layfilter, openall: openall}));
			form.render();
			// 备注：如果使用form.on('checkbox()')，外部就无法使用form.on()监听同样的元素了（LAYUI不支持重复监听了）。
			form.on('checkbox('+layfilter+')', function(data){
			 	/*属下所有权限状态跟随，如果选中，往上走全部选中*/
			 	var childs = $(data.elem).parent().next().find('input[type="checkbox"]').prop('checked', data.elem.checked);
			 	if(data.elem.checked){
			 		/*查找child的前边一个元素，并将里边的checkbox选中状态改为true。*/
			 		$(data.elem).parents('.auth-child').prev().find('input[type="checkbox"]').prop('checked', true);
			 	}
			 	/*console.log(childs);*/
			 	form.render('checkbox');
			});
			$(dst).find('.auth-single:first').unbind('click').on('click', '.layui-form-checkbox', function(){
				var elem = $(this).prev();
				var checked = elem.is(':checked');
				var childs = elem.parent().next().find('input[type="checkbox"]').prop('checked', checked);
				if(checked){
					/*查找child的前边一个元素，并将里边的checkbox选中状态改为true。*/
					elem.parents('.auth-child').prev().find('input[type="checkbox"]').prop('checked', true);
				}
				/*console.log(childs);*/
				form.render('checkbox');
			});

			/*动态绑定展开事件*/
			$(dst).unbind('click').on('click', '.auth-icon', function(){
				var origin = $(this);
				var child = origin.parent().parent().find('.auth-child:first');
				if(origin.is('.active')){
					/*收起*/
					origin.removeClass('active').html('+');
					child.slideUp('fast');
				} else {
					/*展开*/
					origin.addClass('active').html('-');
					child.slideDown('fast');
				}
				return false;
			})
		},
		// 递归创建格式
		renderAuth: function(tree, dept, opt){
			var inputname = opt.inputname;
			var layfilter = opt.layfilter;
			var openall = opt.openall;
			var str = '<div class="auth-single" style="background:#fff;">';
			layui.each(tree, function(index, item){
				var hasChild = item.children.length>0 ? 1 : 0;
				var classname = openall ? 'active' : '';
				var icon = openall ? '-' : '+';
				// 注意：递归调用时，this的环境会改变！
				var append = hasChild ? obj.renderAuth(item.children, dept+1, opt) : '';
			
				// '+new Array(dept * 4).join('&nbsp;')+'
				str += '<div style="border-left:1px solid #ccc;">';
				str += '<div class="auth-status" style="margin-left:-10px;padding-top:-20px;'+(dept>0 ? 'padding-top:5px;' : '')+'">';
				if(hasChild){
					styleStr = 'cursor:pointer;font-size:20px;line-height:16px;width:16px;height:16px;text-align:center;background:#fff;display:inline-block;border:1px solid #ccc;position: relative;top:3px';
					str += '<i class="layui-icon auth-icon '+classname+'" style="'+styleStr+'">'+icon+'</i>';
				}else{
					styleStr = 'border-top:1px solid #ccc;width:9px;display:inline-block;height:4px;margin-left:9px;';
					str += '<i class="layui-icon auth-leaf" style="'+styleStr+'"></i>';
				}
				styleStr = 'border-top:1px solid #ccc;width:8px;display:inline-block;height:4px;background:#fff;border-left:#1px solid #ccc;';
				str +='<i class="layui-icon" style="'+styleStr+'"></i>';
				str +='<input type="checkbox" name="'+inputname+'" title="'+item.name+'" value="'+item.id+'" lay-skin="primary" lay-filter="'+layfilter+'" '+(item.checked?'checked="checked"':'')+'>';
				str +='</div>';
				str +='<div class="auth-child" style="'+(openall?'':'display:none;')+'padding-left:25px"> '+append+'</div>';//子节点
				str +='</div>';
			});
			str += '</div>';
			return str;
		},
		// 获取选中叶子结点
		getLeaf: function(dst){
			var leafs = $(dst).find('.auth-leaf').parent().find('input[type="checkbox"]:checked');
			var data = [];
			layui.each(leafs, function(index, item){
				// console.log(item);
				data.push(item.value);
			});
			// console.log(data);
			return data;
		}
	}
	exports('authtree', obj);
});