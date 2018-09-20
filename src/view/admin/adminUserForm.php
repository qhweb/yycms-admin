<div class="layui-fluid">
	<div class="layui-row layui-col-space15 yycms-form">
		<div class="layui-col-md10 layui-col-xs">
			<form class="layui-form" action="">
			  	<div class="layui-form-item">
				    <label class="layui-form-label">用户名</label>
				    <div class="layui-input-block">
				    	<input <?php echo isset($info['user_email'])?'disabled':''?> type="text" lay-verify="required" autocomplete="off" placeholder="请输入用户名" class="layui-input" value="<?php echo isset($info['user_name'])?$info['user_name']:''?>" name="user_name" >
				    </div>
				</div>
				<div class="layui-form-item">
				    <label class="layui-form-label">昵称</label>
				    <div class="layui-input-block">
				    	<input type="text" autocomplete="off" placeholder="请输入昵称" class="layui-input" value="<?php echo isset($info['user_nicename'])?$info['user_nicename']:''?>" name="user_nicename" >
				    </div>
			  	</div>
			  	<div class="layui-form-item">
				    <label class="layui-form-label">邮箱</label>
				    <div class="layui-input-block">
				    	<input type="text" autocomplete="off" placeholder="请输入邮箱" class="layui-input" value="<?php echo isset($info['user_email'])?$info['user_email']:''?>" name="user_email" >
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
				      	<select class="layui-input" multiple="multiple" id="multi-select" name="role[]">
		                {$info.role ?: ''}
		            	</select>
				    </div>
				</div>
				<div class="layui-form-item">
				    <label class="layui-form-label">状态</label>
				    <div class="layui-input-block">
				      <input type="checkbox" <?php echo isset($info['user_status']) && $info['user_status']==1 ? 'checked=""' :''?> name="user_status" lay-skin="switch" lay-filter="checkbox" lay-text="开启|禁用">
				    </div>
				</div>
				<div class="layui-form-item">
				    <div class="layui-input-block">
				    	<input type="hidden" name="id" id="id" value="<?php echo isset($info['id'])?$info['id']:''?>" />
				      <button class="layui-btn" lay-submit="" lay-filter="dialog" data-action="<?php echo url('') ?>">保存提交</button>
				      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
				    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>layui.use('form', function(exports){layui.form.render();});</script>