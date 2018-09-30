<form class="layui-form" action="">
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" checked title="显示">
            <input type="radio" name="status" value="0" title="隐藏">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="1" checked title="权限认证+菜单">
            <input type="radio" name="type" value="0" title="只作为菜单">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">上级</label>
        <div class="layui-input-inline">
            <select class="form-control text" name="parent_id">
                <option value="0">/</option>
                {volist name='menus' id="v"}
                <option value="{$v.id}">{$v.ltitle}</option>
                {/volist}
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入名称" class="layui-input" value="">
        </div>
        <div class="layui-input-inline">
            <input type="hidden" name="icon" value="">
            <button type="button" class="layui-btn layui-btn-primary" yycms-event="dialog" data-width="800" data-height="700" data-href='{:url("icon")}/input/icon'><i
                        class="layui-icon layui-icon-login-wechat" style="font-size: 30px;"></i></button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">应用</label>
        <div class="layui-input-inline" style="width: 150px;">
            <input type="text" name="app" lay-verify="required" autocomplete="off" placeholder="应用名称" class="layui-input" value="">
        </div>
        <div class="layui-input-inline" style="width: 150px;">
            <input type="text" name="model" lay-verify="required" autocomplete="off" placeholder="控制器名称" class="layui-input" value="">
        </div>
        <div class="layui-input-inline" style="width: 150px;">
            <input type="text" name="action" lay-verify="required" autocomplete="off" placeholder="方法名称" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">验证规则</label>
        <div class="layui-input-inline">
            <input type="text" name="rule_param"  autocomplete="off" placeholder="请输入验证规则 " class="layui-input" value="">
        </div>
        <div class="layui-form-mid layui-word-aux">例:{id}==3 and {cid}==3</div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">日志类型</label>
        <div class="layui-input-inline">
            <select class="form-control text" name="request">
                <option value="">关闭</option>
                <?php
                $type       = ['GET','POST','PUT','PUT','DELETE','Ajax'];
                foreach($type as $v){
                    echo '<option value="'.$v.'">'.$v.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">日志说明</label>
        <div class="layui-input-block">
            <textarea name="log_rule"  class="layui-textarea" placeholder="请输入日志请求类型{id},{name}"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <input type="text" name="remark" autocomplete="off" placeholder="请输入备注" class="layui-input" value="">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="submit">保存提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            <a class="layui-btn layui-btn-normal" yycms-event="back" lay-close="">返回</a>
        </div>
    </div>
</form>