
<title>设置我的资料</title>
  
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">设置我的资料</div>
        <div class="layui-card-body" pad15>
          
          <div class="layui-form" lay-filter="">
            
            <div class="layui-form-item">
              <label class="layui-form-label">用户名</label>
              <div class="layui-input-inline">
                <input type="text" name="user_name" value="{$info.user_name}" readonly class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">不可修改。一般用于后台登入名</div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">昵称</label>
              <div class="layui-input-inline">
                <input type="text" name="user_nicename" value="{$info.user_nicename}" lay-verify="nickname" autocomplete="off" placeholder="请输入昵称" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">性别</label>
              <div class="layui-input-block">
                <input type="radio" name="user_sex" value="男" title="男" {$info.user_sex == '男' || $info.user_sex=='' ? 'checked':''}>
                <input type="radio" name="user_sex" value="女" title="女"  {$info.user_sex == '女' ? 'checked':''}>
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">头像</label>
              <div class="layui-input-inline">
                <input name="user_avatar" lay-verify="required" id="user_avatar" placeholder="图片地址" value="{$info.user_avatar}" class="layui-input">
              </div>
              <div class="layui-input-inline layui-btn-container" style="width: auto;">
                <button type="button" class="layui-btn layui-btn-primary" id="avatarUpload" data-input="user_avatar">
                  <i class="layui-icon">&#xe67c;</i>上传图片
                </button>
                <button class="layui-btn layui-btn-primary" layadmin-event="picPreview" data-input="user_avatar">查看图片</button >
              </div>
           </div>
            <div class="layui-form-item">
              <label class="layui-form-label">手机</label>
              <div class="layui-input-inline">
                <input type="text" name="user_phone" value="{$info.user_phone}" lay-verify="phone" autocomplete="off" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label class="layui-form-label">邮箱</label>
              <div class="layui-input-inline">
                <input type="text" name="user_email" value="{$info.user_email}" lay-verify="email" autocomplete="off" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item layui-form-text">
              <label class="layui-form-label">备注</label>
              <div class="layui-input-block">
                <textarea name="remarks" placeholder="请输入内容" class="layui-textarea">{$info.remarks}</textarea>
              </div>
            </div>
            <div class="layui-form-item">
              <div class="layui-input-block">
              	<input type="hidden" name="id" id="id" value="{$info.id}" />
                <button class="layui-btn" lay-submit lay-filter="*" data-url="{:url('user_info')}">确认修改</button>
                <button type="reset" class="layui-btn layui-btn-primary">重新填写</button>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<script>layui.use('cms', layui.factory('cms'));</script>