<?php
//YYCMS后台统一模块
namespace yycms\controller;

use yycms\model\Menu as MenuModel;
use yycms\lib\Tree;
use think\Validate;
class Menu
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
        $this->rule = [
            'name'  => 'require',
            'app'   => 'require',
            'model'   => 'require',
            'action'   => 'require',
        ];

        $this->rulemsg = [
            'name.require' => '名称必须填写',
            'app.require'     => '应用名称必须填写',
            'model.require'     => '应用控制器名称必须填写',
            'action.require'     => '应用方法名称必须填写',
        ];
        
    }
    /**
     * 后台框架首页
     */
    public function yycms_index(){
        if ($this->request->isPost()) {
            $result     = MenuModel::order(["list_order" => "asc",'id'=>'asc'])->select();
            // print_r($result);exit;
            return ['code'=>0,'msg'=>'获取成功','data'=>$result];
        }
        return [$this->tpl,array_merge($this->data,[])];
    }
    public function yycms_menuData(){
        $result     = MenuModel::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
        $tree       = new Tree();
        $result = $tree->ArrayTree($result);
        if (isset($this->param['table'])) {
            $result     = getLevelTree($result);
            return ['code'=>0,'msg'=>'获取成功','data'=>$result];
        }else{
            return ['code'=>1,'msg'=>'获取成功','data'=>$result];
        }
        
    }
    public function menuIcon()
    {
        $post   = $this->post;
        $info   = MenuModel::get($this->id);

        if(empty($info)){
            return false;
        }
        if($this->request->isPost()){

            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if($info->allowField(true)->isUpdate(true)->save($post)){
                return ['code'=>1,'msg'=>'修改成功','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }

    }
    /**
     * 菜单and权限 修改
     */
    public function yycms_menuEdit()
    {

        $post   = $this->post;
        $info   = MenuModel::get($this->id);

        if(empty($info)){
            return false;
        }

        if($this->request->isPost()){

            $validate = Validate::make($this->rule)->message($this->rulemsg);

            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if (!$post['field'] && !$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if($info->allowField(true)->isUpdate(true)->save($post)){
                return ['code'=>1,'msg'=>'修改成功','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }

        $info['selectCategorys'] = menu($info['parent_id']);
        return [VIEW_PATH.'menu/menuForm.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 菜单and权限 增加
     */
    public function yycms_menuAdd()
    {
        $parent_id  = isset($this->param['parent_id'])?$this->param['parent_id']:'';
        if($this->request->isPost()){
            $post   = $this->post;
            $validate = Validate::make($this->rule)->message($this->rulemsg);
            
            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，增加失败'];
            }
            
            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            
            if(Menu::create($post)){
                return ['code'=>1,'msg'=>'增加成功','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'增加失败'];
            }
        }

        $info['selectCategorys']  = menu($parent_id);
        return [VIEW_PATH.'menu/menuForm.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 菜单and权限 删除
     */
    public function yycms_menuDelete()
    {
        if($this->request->isPost()){
            $result   = Menu::get($this->id);

            if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }else if(Menu::where(['parent_id'=>$result['id']])->find()){
                return ['code'=>0,'msg'=>'请先删除子栏目'];
            };

            if($result->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('auth/menu')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }
}