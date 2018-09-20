<?php
namespace yycms\controller;

class Error
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
    
    public function index()
    {
        $id = @$this->param['id'];
        return [$this->tpl,array_merge($this->data,['id'=>$id])];
    }
     /**
     * 系统图标选择
     */
    public function icon(){
        return [$this->tpl,''];
    }
}

