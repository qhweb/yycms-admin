<?php
namespace yycms\model;


class AuthRole extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'yycms_role';

    //初始化属性
    protected function initialize()
    {

    }

    //一对多 权限授权
    public function authAccess()
    {
        return $this->hasMany('AuthAccess','role_id','id');
    }
    //一对多 权限用户
    public function adminUser()
    {
        return $this->hasMany('AdminUser','role','id');
    }
    /**
     * 关联删除 AuthAccess
     * 判断是否有用户使用此角色,如果有返回使用角色数量
     * 否则删除角色数据,调用authAccess方法如果有数据删除关联AuthAccess模型数据
     *
     * @return bool
     */
    public function authRoleDelete()
    {
        $roleCount = $this->adminUser->count();
        if($roleCount > 0){
            return "已有{$roleCount}用户在是有此角色不可删除<br>请先更改用户角色";
        }
        if($this->delete()){
            if($this->authAccess){
                $this->authAccess()->where(['type'=>'admin_url'])->delete();
            }
            return true;
        }
        return false;
    }
}
?>