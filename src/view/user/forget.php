<div class="login-bg login-bg-mini">
  <header>
  	<i class="backicon layui-icon layui-icon-left" layadmin-event="back" ></i>
  	<?php if($resetpass){?>
  	<div class="login-header"> 管理密码重置 </div>
  	<?php } else { ?>
    <div class="login-header"> 管理密码找回 </div>
    <?php } ?>
    </script>
  </header>
  <section>
      <div class="login-con layui-form">
      	<script type="text/html" template>
      	<?php if($resetpass){?>
        <input name="password" id="password" lay-verify="pass" autocomplete="off" type="text" placeholder="新密码" class="login_password">
        <input name="repass" id="repass" lay-verify="pass" autocomplete="off" type="text" placeholder="确认密码" class="login_password">
        <button class="layui-btn ect-btn-login ect-clear" lay-submit lay-filter="resetpass">重置新密码</button>
        <?php } else { ?>
        <input name="username" id="username" lay-verify="nickname" autocomplete="off" type="text" placeholder="管理员用户名">
        <input name="email" id="email" lay-verify="email" autocomplete="off" type="text" placeholder="Email地址" class="text-email">

        <button class="layui-btn ect-btn-login ect-clear" lay-submit lay-filter="forget">找回密码</button>
        <?php } ?>
        </script>
      </div>
  </section>
</div>
<footer>
  <div class="login-footer"></div>
</footer>
<div class="passport-bg"></div>
<script type="text/html" template>
  <link rel="stylesheet" href="{{ layui.setter.base }}style/login.css?v={{ layui.admin.v }}-1" media="all">
</script>
<script>layui.use('cms', layui.factory('cms'));</script>