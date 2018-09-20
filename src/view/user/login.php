<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <title>后台登录 - YYCMS</title>
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
</head>
<body>
  <div class="login-bg">
  <header>
  	<i class="backicon layui-icon layui-icon-left" layadmin-event="back" ></i>
    <div class="login-header">登录</div>
  </header>
  <section>
      <div class="login-con layui-form">
        <input name="username" id="username" lay-verify="nickname" autocomplete="off" type="text" placeholder="管理员用户名">
        <input name="password" id="password" lay-verify="pass" autocomplete="off" type="password" placeholder="管理员密码" class="login_password">
        <?php if($errnum >= 3){?>
        <div class="login-con-code">
          <input name="captcha" type="text" placeholder="验证码" autocomplete="off" lay-verify="captcha">
          <img src="/captcha/1"  id="getvercode" class="cursor" />
        </div>
        <?php } ?>
        <div style="overflow:hidden;">
          <div class="pull-left">
            <input type="checkbox" name="remember" title="保存登录信息" lay-skin="primary"> 
          </div>
          <span class="pull-right">
          <script type="text/html" template>
         	  {{# if(layui.setter.user.reg){ }}
          	<a lay-href="{:url('reg')}">没有账号?</a> &nbsp; 
          	{{# } }}
        	</script>
          	<a lay-href="{:url('forget')}">忘记密码?</a></span>
        </div>
        <button class="layui-btn ect-btn-login ect-clear" lay-submit lay-filter="login">进入管理中心</button>
      </div>
      <input type="hidden" name="act" value="signin" />
  </section>
</div>
<footer>
  <div class="login-footer"></div>
</footer>
<div class="passport-bg"></div>
<script src="/static/layui/layui.js"></script>
<script>layui.config({base: '/yycms/yyadmin/',version: '1.0.0'}).use('user');</script>
</body>
</html>
