<?php
namespace yycms\controller;

use yycms\Admin;
use think\Validate;
use yycms\model\AdminUser;
class User
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
	 * 后台登录
	 */
    public function login(){
    	$login_errnum = intval(Admin::sessionGet('login_errnum'));
        if ($this->request->isPost()) {
            $post   = $this->post;
            $rule = [
                'username'  => 'require|max:25',
                'password'   => 'require|min:5',
            ];

            $msg = [
                'username.require' => '用户名必须填写',
                'username.max'     => '名称最多不能超过25个字符',
                'password.require'   => '密码必须填写',
                'password.min'  => '密码不能少于5个字符',
            ];
			$validate = Validate::make($rule)->message($msg);
            //验证

			if (!$validate->check($post)) {
				Admin::sessionSet('login_errnum',$login_errnum+1);
                return ['code'=>1,'msg'=>$validate->getError(),'url'=>url('login'),'errnum'=>$login_errnum+1];
            }

            $data = [
                'user_name'      => $post['username'],
                'user_password'  => md5($post['password']),
            ];

            $list =  AdminUser::where($data)->find();
            if($list){
                Admin::login($list['id'],$list['user_name']);
                $access_token = Admin::sessionGet('user_sign');
                $this->auth->createLog('管理员<spen style=\'color: #1dd2af;\'>[ {name} ]</spen>偷偷的进入后台了,','后台登录');
                Admin::sessionSet('login_errnum',0);
                return ['code'=>1,'msg'=>'登入成功','data'=>['access_token'=>$access_token,'time'=>time()]];
            }else{
           		Admin::sessionSet('login_errnum',$login_errnum+1);
           		return ['code'=>0,'msg'=>'账户或密码错误','errnum'=>$login_errnum+1,'url'=>url('login')];
            }
        }
        return [$this->tpl,array_merge($this->data,['errnum'=>$login_errnum])];
    }
    /**
     * 找回密码
     */
    public function forget(){
    	if ($this->request->isPost()) {
    		$where = [
    			'user_email'=>	$this->post['email'],
    			'user_name'	=>	$this->post['username'],
    		];
    		$info = AdminUser::where($where)->find();
    		if($info){
    			Auth::sessionSet('access_token',md5($this->post['username']));
    			Auth::sessionSet('forget',['uid'=>$info['id'],'email'=>$info['user_email'],'access_token'=>md5($this->post['username'])]);
    			return ['code'=>1,'msg'=>'邮箱匹配成功，进入下一步','url'=>url('resetpass')];
    		}else{
    			return ['code'=>0,'msg'=>'邮箱不匹配'];
    		}
    	}
    	return [$this->view.'user/forget.php',array_merge($this->data,['resetpass'=>0])];
    }
    /**
     * 用户注册
     */
    public function reg(){
    	return [$this->view.'user/reg.php',array_merge($this->data,[])];
    }
    /**
     * 用户密码修改
     */
    public function resetpass(){
    	if ($this->request->isPost()) {
    		$user = Auth::sessionGet('forget');
    		if($user){
    			$info = AdminUser::get($user['uid']);
    		}else{
    			return ['code'=>0,'msg'=>'参数错误','url'=>url('forget')];
    		}
    		
    		
    		$post = $this->post;
    		
    		$passord = $post['password'];
    		$repass  = $post['repass'];
    		
    		
    		if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，增加失败'];
            }
            
    		if($passord != $repass){
    			return ['code'=>0,'msg'=>'两次输入密码不一致'];
    		}else{
    			$data 	 = [
	    			'id'=>	$user['uid'],
	    			'user_password'	=>	md5($passord),
	    		];
    		}
    		if($info->allowField(true)->save($data)){
    			Auth::sessionSet('forget','');
    			return ['code'=>1,'msg'=>'密码重置成功'];
    		}else{
    			return ['code'=>0,'msg'=>'密码重置失败'];
    		}
    	}
    	return [$this->view.'user/forget.php',array_merge($this->data,['resetpass'=>1])];
    }
    /**
     * 退出登录
     */
    function logout(){
    	Auth::logout();
    	return ['code'=>1,'msg'=>'退出成功'];
    }
    /**	
     * 基本资料
     */
    public function user_info(){
    	$user = Auth::sessionGet('user');
    	$info = AdminUser::get($user['uid']);
    	if($this->request->isPost()){
    		$post = $this->post;
    		

    		if (!$post = access_token($post)) {
                return ['code'=>0,'msg'=>'access_token匹配不成功，增加失败'];
            }
            
            $validate = new Validate([
                ['user_email|邮箱','require|email'],
            ]);
			
            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            
			if($info->allowField(true)->save($post)){
                return ['code'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>0,'msg'=>'没有任何改动'];
            }
            
    	}
    	$role = AuthRole::all();
    	return [$this->view.'user/user_info.php',array_merge($this->data,['info'=>$info,'role'=>$role])];
    }
    /**	
     * 修改密码
     */
    public function user_pass(){
    	if($this->request->isPost()){
    		$post = $this->post;
    		$admin = new AdminUser();
    		$newpass = $post['password'];
    		$renewpass = $post['repassword'];
    		if($newpass != $renewpass){
    			return ['code'=>0,'msg'=>'新密码和确认密码不一致'];
    		}
    		$user = Auth::sessionGet('user');
    		
    		$data = [
                'id'      => $user['uid'],
                'user_password'  => md5($post['oldPassword']),
            ];

            $list = $admin->where($data)->find();
            if($list){
            	$insert = [
            		'id' 		=> $user['uid'],
            		'user_password' => md5($newpass)
            	];
            	if($admin->isUpdate(true)->save($insert)){
            		return ['code'=>1,'msg'=>'密码修改成功'];
            	}else{
            		return ['code'=>0,'msg'=>'密码修改失败'];
            	}
            }else{
            	return ['code'=>0,'msg'=>'旧密码错误'];
            }
    		
    	}
    	return [$this->view.'user/user_pass.php',''];
    }
    /**
     * 上传图片
     */
    public function upface(){
    	$allowType = 'jpg,png,gif';
    	$allowSize = 1048576;
        // 获取上传文件表单字段名
        $fileKey = array_keys($this->request->file());
        // 获取表单上传文件
        $file = $this->request->file($fileKey['0']);
        // 移动到框架应用根目录/public/upload/avatar/ 目录下
        $info = $file->rule('uniqid')->validate(['size'=>$allowSize,'ext'=>$allowType])->move(ROOT_PATH . 'public' . DS . 'upload' . DS . 'avatar/' ,true,false);//

        if($info){
        	
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['url'] = '/upload/avatar/'. $path;
            $result['ext'] = $info->getExtension();
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = $file->getError()?$file->getError():'上传失败';
            $result['url'] = '';
            return $result;
        }
    }
}