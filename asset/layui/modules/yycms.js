layui.define(['element','admin','navbar','form'], function(exports){
  	var element = layui.element
  		,$=layui.$
  		,admin = layui.admin
  		,APP_BODY = "YY_BODY"
  		,navbar = layui.navbar()
  		,device = layui.device();
  	//阻止IE7以下访问
	if(device.ie && device.ie < 8){
	    layer.alert('系统最低支持ie8，您当前使用的是古老的 IE'+ device.ie + '，请更新你的IE浏览器到最新版本！');
	}
	//入口页面
	var entryPage = function(fn){
	    var container = admin.view(APP_BODY)
	    ,router = layui.router()
    	,path = router.path;
    	//默认读取主页
	    if(!path.length) path = [''];
	    
	    //如果最后一项为空字符，则读取默认文件
	    if(path[path.length - 1] === ''){
	      	path[path.length - 1] = 'index';
	    }
	    pathURL = admin.correctRouter(path.join('/'));
      	container.render(pathURL).done(function(){
      		renderPage();
	        layui.element.render(); 
	        // layui.form.render(); 
	        if(admin.screen() < 2){
	            admin.sideFlexible();
	        }
	        

	    }); 
	}
	,renderPage = function(){
		$("#"+APP_BODY).scrollTop(0);
		var elemTemp = $('.layui-side').find('a');
        elemTemp.removeClass('layui-this');
        for(var i = elemTemp.length; i > 0; i--){
    		var dataElem = elemTemp.eq(i - 1);
    		url = dataElem.attr('lay-href');
    		if (url == pathURL) {
    			dataElem.addClass('layui-this');
    		};
        }
	}
	//初始主体结构
	layui.link(
	    layui.cache.base+'skin/default.css?v='+ (admin.v + '-1'),function(){
	      	entryPage()
	    },'yycmsAdmin'
	);
	navbar.set({
        elem: '#nav',
        url: '/admin/auth/menuData',
        cached:false
    });
    navbar.render();
    navbar.on('click(nav)', function(data) {
    	if (data.field.href) {
    		$('body').removeClass('site-mobile');
    		location.hash = admin.correctRouter(data.field.href);
    	};
       
    });
    //手机设备的简单适配
	var treeMobile = $('.site-tree-mobile,.site-icon-mobile'),shadeMobile = $('.site-mobile-shade');

	treeMobile.on('click', function(){
	    $('body').addClass('site-mobile');
	});

	shadeMobile.on('click', function(){
	    $('body').removeClass('site-mobile');
	});
	$(".usericon").on('click',function(){
		$(".layui-layout-right .username").click();
	})
	//监听Hash改变
	window.onhashchange = function(){
	    entryPage();
	};
	exports('yycms',{})
});