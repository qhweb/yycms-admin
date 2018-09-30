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
namespace yycms\controller;

class Role extends YYAdmin
{
    public function __construct($request,$controller)
    {
        parent::__construct($request,$controller);
        $this->model = new \yycms\model\AuthRole();
        $this->rule = [
            'name'  => 'require',
        ];
        $this->rulemsg = [
            'name.require' => '角色名称必须填写',
        ];
    }
	/**
     * 角色列表
     */
    public function yycms_index(){
        if($this->isPost){
            $limit = $this->post['limit']?:15;
            $key = isset($post['key']) ? trim($post['key']) : '';
            $map = array();
            if (!empty($key)) {
                $map['name'] = array('like', '%'.$key.'%');
            }
            $result = $this->model->where($map)->paginate($limit)->toArray();
            return $this->yycms_reponse($result['data'],$result['total']);
        }
        return $this->yycms_display();
    }

    /**
     * 角色选择select数据
     * @param string $roleid
     * @return string
     */
    private static function yycms_roleSelect($roleid = ''){
        $roleid = explode(',',$roleid);
        $role = $this->model->column('name','id');
        $html = '';
        foreach($role as $k=>$v){
            $selected = in_array($k, $roleid) ? 'selected':'';
            $html   .= ' <option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }
        return $html;
    }
    
    /**
     * 角色修改
     */
    public function yycms_edit(){
        if($this->isPost){
            $this->post['status'] = isset($this->post['status']) && $this->post['status'] =='on' ? 1 : 0;
            return $this->yycms_editData();
        }else{
            $info = $this->model->get($this->id);
            $this->data['title'] = '角色修改';
            $this->data['info'] = $info;
            return $this->yycms_display();
        }
    }
    /**
     * 角色增加
     */
    public function yycms_add(){
        if($this->isPost){
            $this->post['status'] = isset($this->post['status']) && $this->post['status'] =='on' ? 1 : 0;
            return $this->yycms_addData();
        }else{
            return $this->yycms_display();
        }
    }

    /**
     * 角色删除
     * @return array
     */
    public function yycms_del()
    {
        if($this->isPost){
            $this->id = $this->ids;
            if (!$post = access_token($this->post)) {
                $this->result('token匹配不成功，操作失败',1002);
            }
            if($this->ids==1){
                $this->error('超级管理员不可删除');
            }else{
                //关联模型删除together
                $role = $this->model->get($this->ids);
                $delete = $role->authRoleDelete();
                if(is_string($delete)){
                    $this->error($delete);
                }else if($delete === true){
                    $this->success('删除成功');
                }else{
                    $this->error(删除失败);
                    return ['code'=>0,'msg'=>'删除失败'];
                }
            }
        }
    }
    /**
     * 角色授权
     */
    public function yycms_authorize()
    {
        //表单处理
        if($this->isPost){
            $post   = $this->post;
            $role = $this->model->find($post['id']);
            $menuid = isset($post['rules']) ? $post['rules'] : [];
            if (!$post = access_token($post)) {
                $this->result('token匹配不成功，操作失败',1002);
            }
            if(empty($this->id)){
                $this->error('需要授权的角色不存在');
            }
            //先删除授权信息
            $role->authAccess()->where(['type'=>'admin_url'])->delete();
            if ($menuid) {
                $data=[];
                $menuData       = \yycms\model\Menu::where('id','IN',$menuid)->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
                foreach ($menuData as $v) {
                    $name   = strtolower("{$v['app']}/{$v['model']}/{$v['action']}");
                    $data[]   = [
                        "role_id"   => $post['id'],
                        "rule_name" => $name,
                        'type'      => 'admin_url',
                        'menu_id'   => $v['id']
                    ];
                }
                if($data){
                    if($role->authAccess()->saveAll($data)){
                        $this->success('角色授权成功');
                    }else{
                        $this->error('角色授权失败');
                    }
                }
            }else{
                $this->success('没有接收到数据，执行清除授权成功！');
            }
        }//表单处理结束
        $role = $this->model->get($this->id);
        $priv_data  =  $role->authAccess()->where(['type'=>'admin_url'])->column('menu_id');
        $info = ['id'=>$this->id,'rules'=>implode(',', $priv_data)];
        $this->data['info'] = $info;
        return $this->yycms_display();
    }
   
}