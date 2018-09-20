/**
 @Name：用户登入和注册等   
*/
layui.define(['yycms','upload','form','api'], function(exports){
  var $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,view = layui.view
  ,admin = layui.yycms
  ,form = layui.form
  ,router = layui.router()
  ,search = router.search
  ,upload = layui.upload
  ,urlApi		=	layui.api;

  form.render();
  var $body = $('body');
  
  //自定义验证
  form.verify({
    nickname: function(value, item){ //value：表单的值、item：表单的DOM对象
      if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
        return '用户名不能为空或不能有特殊字符';
      }
      if(/(^\_)|(\__)|(\_+$)/.test(value)){
        return '用户名首尾不能出现下划线\'_\'';
      }
      if(/^\d+\d+\d$/.test(value)){
        return '用户名不能全为数字';
      }
    }
    ,pass: [
	    /^[\S]{5,15}$/
	    ,'密码必须5到15位，且不能出现空格'
	] 
	,captcha: function(value, item){ //value：表单的值、item：表单的DOM对象
		if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
        	return '验证码不能为空或不能有特殊字符';
      	}
      	if(/^[\S]{0,3}$/.test(value)){
        	return '验证码必须4位';
      	}
   }
  });

  console.log(search);
  	
	//登录提交
	form.on('submit(login)', function(obj){
	    //请求登入接口
	    admin.req({
	      url: '' //实际使用请改成服务端真实接口
	      ,data: obj.field
	      ,method:'post'
	      ,done: function(res){
	      	if(res.code == 1){
	      		
		        if(res.errnum){
		        	if(res.errnum>=3){
		        		var code = $(".login-con-code");
		      			layer.msg(res.msg,function(){
		      				if (code.length<1) {
		      					$(".login_password").after('<div class="login-con-code"><input name="captcha" type="text" placeholder="验证码" autocomplete="off" lay-verify="captcha"><img src="https://www.oschina.net/action/user/captcha"  id="getvercode" class="cursor" /></div>');
		      				};
		      			});
		      		}else{
		      			layer.msg(res.msg);
		      		}
		        }else{
		        	//请求成功后，没有错误的时候写入 access_token
			        layui.data('yycms_data', {
			          key: 'access_token',
			          value: res.data.access_token
			        });
		        	//登入成功的提示与跳转
			        layer.msg('登入成功',{time:1000},function(){
			          location.href = search.redirect ? decodeURIComponent(search.redirect) : '/admin';
			        });
		        }
		        
	      	}
	      }
	    });
	    return false;
	});
	//找回密码下一步
	form.on('submit(forget)', function(obj){
	    var field = obj.field;
	    //请求接口
	    admin.req({
	      url: urlApi.user.forget //实际使用请改成服务端真实接口
	      ,data: field
	      ,method:'post'
	      ,done: function(res){        
	        if(res.code==1){
	        	location.hash = res.url;
	        }else{
	        	layer.msg(res.msg);
	        }
	      }
	    });
	    return false;
	});
	//重置密码
	form.on('submit(resetpass)', function(obj){
	    var field = obj.field;
	    //确认密码
	    if(field.password !== field.repass){
	      return layer.msg('两次密码输入不一致',{icon:2,time:1000});
	    }
	    //请求接口
	    admin.req({
	      url: urlApi.user.resetpass //实际使用请改成服务端真实接口
	      ,data: field
	      ,method:'post'
	      ,done: function(res){        
	        if(res.code == 1){
	        	layer.msg('密码已成功重置',{icon:1,time:1000}, function(){
		          location.hash = urlApi.user.login; //跳转到登入页
		        });
	        }else{
	        	layer.msg(res.msg,{icon:2,time:1000},function(){
	        		location.hash = res.url;
	        	});
	        }
	      }
	    });
	    
	    return false;
	});
	//表单提交
	form.on('submit(*)', function(obj){
		var posturl = $(this).data('url')
	    admin.req({
	      url: posturl
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
	//========================================================自定义事件=======================================
	//退出
	admin.events.logout = function(){
	    //执行退出接口
	    admin.req({
	      url: urlApi.user.logout
	      ,type: 'post'
	      ,data: {}
	      ,done: function(res){ 
	        //清空本地记录的 token，并跳转到登入页
	        admin.exit();
	      }
	    });
	};
	//================================================上传事件========================================================
	//头像上传
	upload.render({
        url: '/admin/user/upface',
        elem:"#avatarUpload",
        exts:'jpg|png|gif',
        done: function(res) {
            true == res.code ? $("#user_avatar").val(res.url) : layer.msg(res.info, {
                icon: 5
            })
        }
    });
	//上传
	var picUploadUrl ='',fileUploadUrl='';
  	upload.render({
        url: picUploadUrl||urlApi.upload.image||'/admin/Api/upImage',
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
        url: fileUploadUrl||urlApi.upload.file||'/admin/Api/upFile',
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
	    var src = $("#"+elem).val();
	    if(!/^http(s*):\/\//.test(src)){
	    	src = 'http://'+location.host+src;
	    }
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
	//更换图形验证码
  	$body.on('click', '#getvercode', function(){
    	var othis = $(this);
    	this.src = '/captcha/'+ new Date().getTime()
  	});
	//初始主体结构
	layui.link(
	    layui.cache.base+'skin/login.css?v='+ (admin.v + '-1'),function(){
	      	$("#getvercode").click();
	    },'login'
	);
	//================================================================对外暴露的接口===================================================
	exports('user', {});
});