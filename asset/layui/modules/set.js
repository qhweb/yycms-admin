/**

 @Name：layuiAdmin 设置
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License: LPPL
    
 */
 
layui.define(['form', 'upload'], function(exports){
  var $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,view = layui.view
  ,admin = layui.admin
  ,form = layui.form
  ,upload = layui.upload;

  var $body = $('body');
  
  form.render();
  
  //自定义验证
  form.verify({
    nickname: function(value, item){ //value：表单的值、item：表单的DOM对象
      if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
        return '用户名不能有特殊字符';
      }
      if(/(^\_)|(\__)|(\_+$)/.test(value)){
        return '用户名首尾不能出现下划线\'_\'';
      }
      if(/^\d+\d+\d$/.test(value)){
        return '用户名不能全为数字';
      }
    }
    
    //我们既支持上述函数式的方式，也支持下述数组的形式
    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
    ,pass: [
      /^[\S]{6,12}$/
      ,'密码必须6到12位，且不能出现空格'
    ]
    
    //确认密码
    ,repass: function(value){
      if(value !== $('#LAY_password').val()){
        return '两次密码输入不一致';
      }
    }
  });
  
  //网站设置
  form.on('submit(set_website)', function(obj){
    layer.msg(JSON.stringify(obj.field));
    
    //提交修改
    /*
    admin.req({
      url: ''
      ,data: obj.field
      ,success: function(){
        
      }
    });
    */
    return false;
  });
  
  //邮件服务
  form.on('submit(set_system_email)', function(obj){
    //layer.msg(JSON.stringify(obj.field));
    
    admin.req({
      url: 'set_email'
      ,method:'post'
      ,data: obj.field
      ,success: function(res){
        if(res.code){
        	layer.msg(res.msg)
        }
      }
    });
    return false;
  });
  
  
  //设置我的资料
  form.on('submit(setmyinfo)', function(obj){
    //layer.msg(JSON.stringify(obj.field));
    
    admin.req({
      url: 'user_info'
      ,method:'post'
      ,data: obj.field
      ,success: function(res){
        if(res.code){
        	layer.msg(res.msg)
        }
      }
    });
    return false;
  });

 	form.on("submit(*)",function(obj) {
  		var data = obj.field;
    	if(setter.isencode){
    		data = encode(JSON.stringify(data))
    	}
    	$.post(post_url,{data},function(res){
            if(res.code==1){
                layer.msg(res.msg,{time:1000,icon:1});
                if(layer.index){
                	layer.close(view.popup.index);
                }else{
                	history.back();
                }
            }else{
                layer.msg(res.msg,{time:1000,icon:2});
            }
        })
        return false;
    });

    //上传
  	upload.render({
        url: setter.picUploadUrl||'uploadImage',
        elem:"#picUpload",
        exts:'jpg|png|gif|bmp',
        before:function(res){
        	elem = $('#picUpload').data('input');
        },
        done: function(res) {
            true == res.code ? $("#"+elem).val(res.url) : layer.msg(res.info, {
                icon: 5
            })
        }
    });
    upload.render({
        url: setter.fileUploadUrl||'uploadFile',
        elem:"#fileUpload",
        accept:'file',
        before:function(res){
        	elem = $('#fileUpload').data('input');
        },
        done: function(res) {
            false == res.code ? $("#"+elem).val(res.url) : layer.msg(res.info, {
                icon: 5
            })
        }
    });
	//查看图片
	admin.events.picPreview = function(othis){
	  	var elem = othis.data('input');
	    var title = othis.text();
	    var src = setter.pubPath + $("#"+elem).val();
	    layer.photos({
	      photos: {
	        "title": title //相册标题
	        ,"data": [{
	          "src": src //原图地址
	        }]
	      }
	      ,shade: 0.01
	      ,closeBtn: 1
	      ,anim: 5
	    });
	};
  	//js字符加密  
	encode = function(str){  
	    var es = [],c='',ec='';  
	    str = str.split('');//10.19补 忘记ie不能下标访问字符串  
	    for(var i=0,length=str.length;i<length;i++){  
	        c = str[i];  
	        ec = encodeURIComponent(c);  
	        if(ec==c){  
	            ec = c.charCodeAt().toString(16);  
	            ec = ('00' + ec).slice(-2);  
	        }  
	        es.push(ec);  
	    }  
	    return es.join('').replace(/%/g,'').toUpperCase();  
	};
  
  
  //设置密码
  form.on('submit(setmypass)', function(obj){
    //提交修改
    admin.req({
      url: 'user_pass'
      ,method:'post'
      ,data: obj.field
      ,success: function(res){
        if(res.code){
        	layer.msg(res.msg)
        }
      }
    });
    return false;
  });
  	
  //对外暴露的接口
  exports('set', {});
});