<?php
namespace yycms\model;

class AdminUser extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'yycms_user';
    //非int时间字段转换
	protected $autoWriteTimestamp = 'datetime';
    //初始化属性
    protected function initialize()
    {
    }
    //一对一 角色查询
    public function authRole()
    {
        return $this->hasOne('AuthRole','id','role');
    }
    //一对多 权限授权
    public function authAccess()
    {
        return $this->hasMany('AuthAccess','role_id','id');
    }
    //一对多 删除用户
    public function delUser()
    {
        if($this->id==1){
            $this->error('超级管理员不可删除');
            return false;
        }else {
            $access = $this->authAccess()->where(['type'=>'admin'])->delete();
            if ($this->delete() && $access) {
                return true;
            } else {
                $this->error('删除失败');
                return false;
            }
        }
    }
}
?>