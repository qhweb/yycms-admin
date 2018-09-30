<style>.multi dl dd.layui-this{background-color:#fff;border:1px solid #ccc}</style>
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" lay-verify="required" autocomplete="off" placeholder="请输入用户名" class="layui-input" value="" name="user_name" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵称</label>
        <div class="layui-input-block">
            <input type="text" autocomplete="off" placeholder="请输入昵称" class="layui-input" value="" name="user_nicename" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="text" autocomplete="off" placeholder="请输入邮箱" class="layui-input" value="" name="user_email" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" autocomplete="off" placeholder="请输入密码" class="layui-input" value="" name="user_password" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">角色</label>
        <div class="layui-input-block">
            <select class="layui-input" name="role">
               <?php echo $role;?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" checked name="user_status" lay-skin="switch" lay-filter="checkbox" lay-text="开启|禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a class="layui-btn" lay-submit="" lay-filter="submit">保存提交</a>
            <a class="layui-btn layui-btn-normal" yycms-event="back">返回</a>
        </div>
    </div>
    <div id="aa"></div>
</form>