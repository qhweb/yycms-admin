<form class="layui-form">
    <div class="layui-form-item">
        <label class="layui-form-label">模型名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" value="" autocomplete="off" lay-verify="required" placeholder="必填：模型名称" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">数据表名</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="" autocomplete="off" placeholder="必填：模型表名" lay-verify="required" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-form-text layui-input-5">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-block">
            <textarea name="description" placeholder="请输入内容" class="layui-textarea" autocomplete="off"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button type="button" class="layui-btn" lay-submit="" lay-filter="submit">提交</button>
            <a class="layui-btn layui-btn-normal" lay-close="">返回</a>
        </div>
    </div>
</form>