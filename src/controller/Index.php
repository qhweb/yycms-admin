<?php
//YYCMS后台统一模块
namespace yycms\controller;

class Index
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
     * 后台框架首页
     */
    public function yycms_index(){
        return [$this->tpl,array_merge($this->data,[])];
    }
    /**
     * 后台首页
     */
    public function yycms_main(){
        print_r(ip2long($this->request->ip()));exit;
        return [$this->tpl,array_merge($this->data,[])];
    }
}