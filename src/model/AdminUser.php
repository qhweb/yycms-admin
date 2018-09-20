<?php
namespace yycms\model;

class AdminUser extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'admin_user';
    //非int时间字段转换
	protected $autoWriteTimestamp = 'datetime';
    //初始化属性
    protected function initialize()
    {
    }

}
?>