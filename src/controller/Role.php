<?php 
namespace yycms\controller;


use think\facade\Cache;
use think\facade\Config;
use think\Validate;
use yycms\Admin;
use yycms\model\AuthRole;
use yycms\model\Menu;
use yycms\lib\Tree;
use yycms\model\AdminUser;
/**
* 角色管理
*/
class Role
{
    private $id;
    public $auth;
    
    public function __construct($request,$controller)
    {
        $this->request      = $request;
        $this->param        = $this->request->param();
        $this->post         = $this->request->post();
        $this->controller   = $controller;
        $this->action       = $this->request->action();
        $this->id           = isset($this->param['id'])?intval($this->param['id']):'';
        $this->data         = ['pach'=>VIEW_PATH,'siteid'=>SITEID,'version'=>config('yycms.version'),'wait'=>3];
        $this->auth         = new \yycms\Admin();
        $this->tpl          = VIEW_PATH.$this->controller.'/'.$this->action . '.php';
        
    }
	/**
     * 角色列表
     */
    public function yycms_index(){
    	if ($this->request->isPost()) {
    		$post   = $this->post;
	        $key = isset($post['key']) ? trim($post['key']) : '';
	        $map =[];
	        $limit = isset($post['limit']) ? $post['limit'] : 15;
	        if (!empty($key)) {
	            $map['name'] = array('like', '%'.$key.'%');
	        }
	        $data = AuthRole::where($map)->limit($limit)->select();
	        $count = AuthRole::where($map)->count();
	        return ['code'=>0,'msg'=>'获取成功','data'=>$data,'count'=>$count];
    	}
        return [$this->tpl,[]];
    }
    private static function yycms_roleSelect($roleid = ''){
        $roleid = explode(',',$roleid);

        $role = AuthRole::column('name','id');
        $html = '';
        foreach($role as $k=>$v){
            $selected = in_array($k, $roleid)?'selected':'';

            $html   .= ' <option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }

        return $html;
    }
    
    /**
     * 角色修改
     */
    public function yycms_roleedit()
    {
    	$info   = AuthRole::get($this->id);
        if(empty($info)){
            return false;
        }
        //post 数据处理
        if($this->request->isPost()){
        	$post   = $this->post;
            if (isset($post['status']) && $post['status'] == 'on') {
                $post['status'] = 1;
            }
			if (!$post = access_token($post)) {
                return ['code'=>1002,'msg'=>'access_token匹配不成功，操作失败'];
            }
            
            $validate = new Validate($this->roleValidate);
            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if($info->allowField(true)->isUpdate(true)->save($post)){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }
        
    	
        return [VIEW_PATH.'role/roleForm.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 角色增加
     */
    public function yycms_roleadd()
    {

        //post 数据处理
        if($this->request->isPost()){
            $post   = $this->post;
			$post['status'] = isset($post['status']) ? 1 : 0;

			if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }
            
            //现在数据
            $validate = new Validate($this->roleValidate);
            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if(AuthRole::create($post)){
                return ['code'=>1,'msg'=>'增加成功','url'=>url('auth/role')];
            }else{
                return ['code'=>0,'msg'=>'增加失败'];
            }
        }
        return [VIEW_PATH.'role/roleForm.php',$this->data];
    }

    public function yycms_roleDelete()
    {
        if($this->request->isPost()){
            $result   = AuthRole::get($this->id);

            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if($this->id==1){
                return ['code'=>0,'msg'=>'超级管理员不可删除'];
            }else if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }
            $delete = $result->authRoleDelete();
            if(is_string($delete)){
                return ['code'=>0,'msg'=>$delete];
            }else if($delete === true){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('auth/role')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }
    /**
     * 角色授权
     */
    public function yycms_authorize()
    { 
        //表单处理
        if($this->request->isPost()){
            
            $post   = $this->post;
            $menuid = isset($post['rules']) ? $post['rules'] : [];

            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if(empty($this->id)){
                return ['code'=>0,'msg'=>'需要授权的角色不存在'];
            }

            AuthAccess::where(["role_id" => $this->id,'type'=>'admin_url'])->delete();

            if ($menuid) {
                $data=[];
                $menuData       = Menu::where('id','IN',$menuid)->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
                foreach ($menuData as $v) {
                    $name   = strtolower("{$v['app']}/{$v['model']}/{$v['action']}");
                    $data[]   = [
                        "role_id"   => $this->id,
                        "rule_name" => $name,
                        'type'      => 'admin_url',
                        'menu_id'   => $v['id']
                    ];
                }
                if($data){
                    $AuthAccess = new AuthAccess();
                    if($AuthAccess->saveAll($data)){
                        return ['code'=>1,'msg'=>'增加成功','url'=>url('auth/role')];
                    }else{
                        return ['code'=>0,'msg'=>'增加失败'];
                    }
                }

            }else{
                AuthAccess::where(["role_id" => $this->id,'type'=>'admin_url'])->delete();
                return ['code'=>1,'msg'=>'没有接收到数据，执行清除授权成功！'];
            }
        }//表单处理结束

        if(empty($this->id)){
            return ['code'=>0,'msg'=>'删除错误！'];
        }
        
        $priv_data  =  AuthAccess::where(['role_id'=>$this->id,'type'=>'admin_url'])->column('menu_id');
        
        $info = ['id'=>$this->id,'rules'=>implode(',', $priv_data)];  
            
        return [VIEW_PATH.'admin/authorize.php',array_merge($this->data,['info'=>$info])];
    }
   
}