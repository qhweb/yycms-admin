<script type="text/html" template>
  <link rel="stylesheet" href="{{ layui.setter.base }}style/login.css?v={{ layui.admin.v }}-1" media="all">
</script>
<div class="login-bg reg">
  <header>
  	<i class="backicon layui-icon layui-icon-left" layadmin-event="back" ></i>
    <div class="login-header"> 注册 </div>
  </header>
  <section>
      <div class="login-con layui-form">
        <input name="username" id="username" lay-verify="nickname" autocomplete="off" type="text" placeholder="用户名">
        <input name="password" id="password" lay-verify="pass" autocomplete="off" type="password" placeholder="密码" class="login_password">
        <input name="repass" id="repass" lay-verify="pass" autocomplete="off" type="password" placeholder="确认密码" class="login_password">
        <input name="email" id="email" lay-verify="email" autocomplete="off" type="text" placeholder="邮箱地址，密码找回用">
        <input name="nickname" id="nickname" lay-verify="nickname" autocomplete="off" type="text" placeholder="昵称">
        <div style="overflow:hidden;">
          <div class="pull-left">
            <input type="checkbox" name="agreed" title="同意用户协议" lay-skin="primary"> 
          </div>
          <span class="pull-right"><a lay-href="{:url('user/login')}">已有账号？</a></span>
        </div>
        <button class="layui-btn ect-btn-login ect-clear" lay-submit lay-filter="login">注册</button>
      </div>
      <input type="hidden" name="act" value="signin" />
  </section>
</div>
<footer>
  <div class="login-footer"></div>
</footer>
<div class="passport-bg"></div>


<script>
layui.use('user', layui.factory('user'));
</script>
<!--<script>
layui.use(['admin', 'form', 'user'], function(){
  var $ = layui.$
  ,setter = layui.setter
  ,admin = layui.admin
  ,form = layui.form
  ,router = layui.router();

  form.render();
  
  //发送短信验证码
  admin.sendAuthCode({
    elem: '#LAY-user-reg-getsmscode'
    ,elemPhone: '#LAY-user-login-cellphone'
    ,elemVercode: '#LAY-user-login-vercode'
    ,ajax: {
      url: './json/user/sms.js' //实际使用请改成服务端真实接口
    }
  });

  //提交
  form.on('submit(LAY-user-reg-submit)', function(obj){
    var field = obj.field;
    
    //确认密码
    if(field.password !== field.repass){
      return layer.msg('两次密码输入不一致');
    }
    
    //是否同意用户协议
    if(!field.agreement){
      return layer.msg('你必须同意用户协议才能注册');
    }
    
    //请求接口
    admin.req({
      url: './json/user/reg.js' //实际使用请改成服务端真实接口
      ,data: field
      ,done: function(res){        
        layer.msg('注册成功', {
          offset: '15px'
          ,icon: 1
          ,time: 1000
        }, function(){
          location.hash = '/user/login'; //跳转到登入页
        });
      }
    });
    
    return false;
  });
});
</script>-->