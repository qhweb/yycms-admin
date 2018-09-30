<?php
// +----------------------------------------------------------------------
// | 版权所有： 2017~2018 青海云音信息技术有限公司 [ http://www.yyinfos.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://yyadmin.yyinfos.com
// +----------------------------------------------------------------------
// | 开源协议： ( https://mit-license.org )
// +----------------------------------------------------------------------
// | 作者：独角戏（120229231@qq.com）
// +----------------------------------------------------------------------
namespace yycms\model;
class Module extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'yycms_module';
    //非int时间字段转换
	protected $autoWriteTimestamp = 'datetime';
    //初始化属性
    protected function initialize()
    {
    }
    //关联一对多 字段
    public function field()
    {
        return $this->hasMany('Field','module_id','id');
    }
}