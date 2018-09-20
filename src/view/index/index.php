<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YYAdmin框架</title>
    <meta name="keywords" content="" />
    <meta name="description" content="LarryCMS Version:1.09" />
    <meta name="Author" content="larry" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="Shortcut Icon" href="/favicon.ico" />
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="http://cdn.yyinfos.com/font/yyicon.css" media="all">
</head>
<body class="layui-layout-body">
	<div class="layui-layout layui-layout-admin">
	  <div class="layui-header">
	    <div class="layui-main">
	      <div class="site-icon-mobile layui-hide-md"><i class="layui-icon">&#xe66b;</i></div>
	      <div class="layui-logo">YYAdmin框架后台</div>
	      <ul class="layui-nav layui-layout-right">
	      	<li class="layui-nav-item">
				<a href="javascript:;" yycms-event="lock">
					<i class="fa fa-lock" aria-hidden="true" style="padding-right: 3px;padding-left: 1px;"></i> 锁屏 (Alt+L)
				</a>
			</li>
	        <li class="layui-nav-item">
	          <a href="javascript:;">
	            <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
	            贤心
	          </a>
	          <dl class="layui-nav-child">
	            <dd><a href="">基本资料</a></dd>
	            <dd><a href="">安全设置</a></dd>
	            <dd><a href="">退出</a></dd>
	          </dl>
	        </li>
	      </ul>
	    </div>
	  </div>
	  
	  <div class="layui-side layui-bg-black layui-side-menu">
	    <div class="layui-side-scroll" id="nav" lay-filter="nav"></div>
	  </div>
	  <div class="site-yycms-title layui-breadcrumb" id="YY_HEADER">
	      <a yycms-href="/admin/index/main">控制台</a>
	      <a><cite></cite></a>
	  </div>
	  <div class="layui-body site-yycms-body"  id="YY_BODY"></div>
	  <div class="layui-footer site-yycms-footer">
	    © yyinfos.com - 青海云音信息技术有限公司
	  </div>
	</div>
	<div class="site-tree-mobile layui-hide">
	    <i class="layui-icon">&#xe602;</i>
	</div>
	<div class="site-mobile-shade"></div>
	<!--锁屏模板 start-->
	<script type="text/template" id="lock-temp">
		<div class="admin-header-lock" id="lock-box">
			<div class="admin-header-lock-img">
				<img src="http://t.cn/RCzsdCq"/>
			</div>
			<div class="admin-header-lock-name" id="lockUserName">beginner</div>
			<div class="layui-inline"><input type="text" class="admin-header-lock-input" value="输入密码解锁.." name="lockPwd" id="lockPwd" /></div>
			<button class="layui-btn admin-header-lock-btn" id="unlock">解锁</button>
		</div>
	</script>
	<!--锁屏模板 end -->
</body>
<script src="/static/layui/layui.js"></script>
<script>layui.config({base: '<?php echo get_file()?>',version: '1.0.0'}).use('yycms',function(){
	var yycms = layui.yycms;
	var navbar = yycms.navbar({
        elem: '#nav',
        url: '/admin/menu/menuData',
        cached:true
    });
});
</script>
</html>