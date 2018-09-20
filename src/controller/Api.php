<?php
//YYCMS后台统一模块
namespace yycms\auth\controller;

use think\controller\Rest;
use think\Cache;
use think\Config;
use think\Validate;
use yycms\auth\Auth;
use yycms\auth\model\Menu;
use yycms\auth\library\Tree;

class Api extends Rest
{
	private $id;
    public $auth;
    
    public function __construct($request)
    {
        $this->request  = $request;
        $this->view     = VIEW_PATH;
        $this->param    = $this->request->param();
        $this->post     = $this->request->post();
        $this->id       = isset($this->param['id'])?intval($this->param['id']):'';
        $this->data     = ['pach'=>$this->view];
        $this->auth     = new Auth();
    }
	//后台管理菜单
    public function menu(){
    	$result     = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
    	return ['code'=>1,'msg'=>'获取成功','data'=>$result];
    }
    /**
     * 上传图片
     */
    public function upImage(){
    	
    	$config = Config::get('upload');

        // 获取上传文件表单字段名
        $fileKey = array_keys($this->request->file());
        // 获取表单上传文件
        $file = $this->request->file($fileKey['0']);
        $size = $file->getSize();
        $ext = $file->getExtension();

		if(!in_array($ext,explode(',',$config['img_type']))){
			$msg = '上传类型不支持!'.$ext;
		}
		if($size > $config['max_pic_size']){
			$msg = '上传图片超出大小限制!'.$size;
		}
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['size'=>$config['max_pic_size'],'ext'=>$config['img_type']])->move(ROOT_PATH . 'public' . DS . $config['dir'] . DS . $this->param['dir'] ,true,false);//

        if($info){
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['url'] = $config['dir']. $path;
            $result['ext'] = $info->getExtension();
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = $msg?$msg:'图片上传失败!';
            $result['url'] = '';
            return $result;
        }
    }
    /**
     * 上传文件
     */
}