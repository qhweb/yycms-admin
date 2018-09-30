<?php
//YYCMS后台统一模块
namespace yycms\controller;

class Menu extends YYAdmin
{
    public function __construct($request,$controller)
    {
        parent::__construct($request,$controller);
        $this->model      = new \yycms\model\Menu();
        $this->rule = [
            'name'  => 'require',
        ];

        $this->rulemsg = [
            'name.require' => '名称必须填写',
        ];
        
    }
    static private function menutree($cate , $lefthtml = '|— ' , $pid=0 , $lvl=0, $leftpin=0 ){
        $arr=array();
        foreach ($cate as $v){
            if($v['parent_id']==$pid){
                $v['lvl']=$lvl + 1;
                $v['leftpin']=$leftpin + 0;
                $v['lefthtml']=str_repeat($lefthtml,$lvl);
                $v['ltitle']=$v['lefthtml'].$v['name'];
                $arr[]=$v;
                $arr= array_merge($arr,self::menutree($cate,$lefthtml,$v['id'], $lvl+1 ,$leftpin+20));
            }
        }

        return $arr;
    }
    /**
     * 后台框架首页
     */
    public function yycms_index(){
        if ($this->request->isPost()) {
            $result     = $this->model->order(["list_order" => "asc",'id'=>'asc'])->select();
            if (isset($this->post['table'])) {
                $result     = self::menutree($result);
                return ['code'=>0,'msg'=>'获取成功','data'=>$result,'count'=>count($result)];
            }else{
                return ['code'=>0,'tip'=>'获取成功','data'=>$result,'count'=>count($result),'is'=>true];
            }
        }
        return $this->yycms_display();
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
                return ['code'=>1002,'msg'=>'access_token匹配不成功，操作失败'];
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
    public function yycms_Edit()
    {
        if($this->request->isPost()){
            return $this->yycms_editData();
        }
        $info   = $this->model->get($this->id);
        $data = $this->model->order(["list_order" => "asc",'id'=>'asc'])->select();
        $menus  = self::menutree($data);
        $this->data['menus'] = $menus;
        $this->data['info'] = $info;
        return $this->yycms_display();
    }

    /**
     * 菜单and权限 增加
     */
    public function yycms_Add()
    {
        if($this->request->isPost()){
            return $this->yycms_addData();
        }
        $data = $this->model->order(["list_order" => "asc",'id'=>'asc'])->select();
        $menus  = self::menutree($data);
        $this->data['menus'] = $menus;
        return $this->yycms_display();
    }

    /**
     * 菜单and权限 删除
     */
    public function yycms_Del()
    {
        $db = new MenuModel;
        if($this->request->isPost()){
            $post = $this->post;
            $result   =$db->get($this->id);

            if (!$post = access_token($post)) {
                return ['code'=>1002,'msg'=>'access_token匹配不成功，操作失败'];
            }

            if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }else if($db->where(['parent_id'=>$result['id']])->find()){
                return ['code'=>0,'msg'=>'请先删除子栏目'];
            };

            if($result->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('index')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }
}