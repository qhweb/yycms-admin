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

class Admins extends YYAdmin
{
    public function __construct($request,$controller)
    {
        parent::__construct($request,$controller);
        $this->model = new \yycms\model\AdminUser();
        $this->rule = [
            'user_name'  => 'require|max:25',
        ];
        $this->rulemsg = [
            'user_name.require' => '用户名必须填写',
            'user_name.max'     => '名称最多不能超过25个字符',
        ];
    }
	/**
     * 管理员列表
     */
    public function yycms_index(){
        if($this->isPost){
            $limit = $this->post['limit']?:15;
            $key = isset($post['key']) ? trim($post['key']) : '';
            $map = array();
            if (!empty($key)) {
                $map['user_name|user_nicename|id']  = ['like','%'.$key.'%'];
            }
            $result = $this->model->with('authRole')->where($map)->order('id asc')->paginate($limit)->toArray();
            foreach ($result['data'] as $k=>$val){
                $result['data'][$k]['rolename'] = $val['auth_role']['name']?:'站长';
            }
            return $this->yycms_reponse($result['data'],$result['total']);
        }
        return $this->yycms_display();
    }

    /**
     * 角色选择select数据
     * @param string $roleid
     * @return string
     */
    private static function yycms_roleSelect($roleid=''){
        $role = \yycms\model\AuthRole::column('name','id');
        $html = '';
        foreach($role as $k=>$v){
            $selected = $k == $roleid ? 'selected':'';
            $html .= ' <option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }
        return $html;
    }
    /**
     * 管理员增加
     */
    public function yycms_add(){
        if($this->isPost){
            $this->post['user_status'] = $this->post['user_status'] =='on' ? 1 : 0;
            $this->rule['user_password'] = 'require|min:5';
            $this->rulemsg['user_password.require'] ='密码必须填写';
            $this->rulemsg['user_password.min'] ='密码不能少于5个字符';
            $result = $this->yycms_addData();
        }else{
            $this->data['role'] = self::yycms_roleSelect();
            return $this->yycms_display();
        }
    }
    /**
     * 管理员修改
     */
    public function yycms_edit(){
        if($this->isPost){
            $password = $this->post['user_password'];
            $this->post['user_status'] = $this->post['user_status'] ==1 || $this->post['user_status'] =='on' ? 1 : 0;
            if(!empty($password)){
                $this->post['user_password'] = md5($password);
            }
            return $this->yycms_editData();
        }else{
            $info = $this->model->get($this->id);
            $this->data['info'] = $info;
            $this->data['role'] = self::yycms_roleSelect($info['role']);
            return $this->yycms_display();
        }
    }
    /**
     * 管理员删除
     * @return array
     */
    public function yycms_del()
    {
        if($this->isPost){
            $access_token = \yycms\Admin::sessionGet('user_sign');
            if($access_token==$this->post['access_token']){
                $result = $this->model->get($this->ids);
                if ($result->delUser()) {
                    $this->success('删除成功');
                } else {
                    $this->error($result->getError());
                }
            }else{
                $this->result('','1002','Token验证失败');
            }
        }
    }

    /**
     * 删除用户后其他数据删除
     */
    public function yycms_after_del(){
        $info = $this->model->get($this->ids);
        $info->accessDel($info['role']);
        print_r($info['role']);
    }
    /**
     * 用户独立授权
     */
    public function yycms_authorize()
    {
        //表单处理
        if($this->isPost){
            $post   = $this->post;
            $userInfo = $this->model->find($post['id']);
            $menuid = isset($post['rules']) ? $post['rules'] : [];
            if (!$post = access_token($post)) {
                $this->result('token匹配不成功，操作失败',1002);
            }
            if(empty($post['id'])){
                $this->error('需要授权的用户不存在');
            }
            //先删除授权信息
            $userInfo->authAccess()->where(['type'=>'admin'])->delete();
            if ($menuid) {
                $data=[];
                $menuData       = \yycms\model\Menu::where('id','IN',$menuid)->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
                foreach ($menuData as $v) {
                    $name   = strtolower("{$v['app']}/{$v['model']}/{$v['action']}");
                    $data[]   = [
                        "role_id"   => $post['id'],
                        "rule_name" => $name,
                        'type'      => 'admin',
                        'menu_id'   => $v['id']
                    ];
                }
                if($data){
                    if($userInfo->authAccess()->saveAll($data)){
                        $this->success('用户独立授权成功');
                    }else{
                        $this->error('用户独立授权失败');
                    }
                }
            }else{
                $this->success('没有接收到数据，执行清除授权成功！');
            }
        }
        //表单处理结束
        $userInfo = $this->model->get($this->id);
        $roleId = $userInfo['role'];
        $userAccess =  $userInfo->authAccess()->where(['type'=>'admin'])->column('menu_id');
        if(count($userAccess) == 0){
            $userAccess   = \yycms\model\AuthAccess::where(["role_id"=>["in",$roleId]])->column('menu_id');
        }
        $info = ['id'=>$this->id,'rules'=>implode(',', $userAccess)];
        $this->data['info'] = $info;
        return $this->yycms_display();
    }
   
}