<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入角色名称" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">角色描述</label>
        <div class="layui-input-block">
            <textarea name="remark"  class="layui-textarea" placeholder="请输入角色描述"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否开启</label>
        <div class="layui-input-block">
            <input type="checkbox"  name="status" lay-skin="switch" lay-filter="checkbox" lay-text="开启|禁用">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a class="layui-btn" lay-submit="" lay-filter="submit">保存提交</a>
            <a class="layui-btn layui-btn-normal" yycms-event="back" lay-close="">返回</a>
        </div>
    </div>
</form>