<style>
	.ruleTree{
        height: 392px;
        border: 1px solid #ecebeb;
        padding: 10px;
        border-radius: 2px;
        position: relative;
        overflow: auto;
        background:#fff;
    }
    .jstree-default .jstree-clicked {
        background: #fff;
    }

</style>
<div class="layui-fluid" style="background:#f2f2f2">
	<div class="layui-row layui-col-space15 yycms-form">
		<div class="layui-col-md12">
            <div class="layui-input-inline">
            	<input type="text" class="layui-input" id="jstree_left_search" name="jstree_left_search" placeholder="快速查找...">
            </div>
            <button type="button" class="layui-btn layui-btn-success check_all">全选</button>
            <button type="button" class="layui-btn layui-btn-danger uncheck_all">自定义</button>
		</div>
		<div class="layui-col-md12 layui-form">
			<div id="ruleTree" class="ruleTree"></div>
			<div class="layui-form-item" style="margin-top:20px;">
					<input type="hidden" name="rules" id="rules" value="<?php echo isset($info['rules'])?$info['rules']:''?>" class="layui-input">
			    	<input type="hidden" name="id" id="id" value="<?php echo isset($info['id'])?$info['id']:''?>" />
			      	<button class="layui-btn layui-btn-sm" lay-submit="" lay-filter="authorize" data-action="<?php echo url('') ?>">保存提交</button>
			      	<button type="reset" class="layui-btn layui-btn-primary  layui-btn-sm uncheck_all">重置</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo url('openfile',['file'=>'js_authorize.js'])?>"></script>