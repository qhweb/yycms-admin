<?php
// +----------------------------------------------------------------------
// | YYAdmin For ThinkPHP 5.1.x
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
use think\Validate;
use think\Controller;
class YYAdmin extends Controller
{
    public $id;

    /**
     * YYAmin constructor.初始化类
     * @param \think\App $request
     * @param $controller
     */
    public function __construct($request,$controller){
        $this->request      = $request;
        $this->param        = $this->request->param();
        $this->post         = $this->request->post();
        $this->isPost         = $this->request->isPost();
        $this->controller   = $controller;
        $this->action       = $this->request->action();
        $this->id           = isset($this->param['id']) ? intval($this->param['id']):'';
        $this->ids           = isset($this->post['ids']) ? $this->post['ids'] : $this->id ;
        $this->data         = ['pach'=>VIEW_PATH,'siteid'=>SITEID,'version'=>config('yycms.version'),'wait'=>3];
        $this->tpl          = VIEW_PATH. strtolower($this->controller) .'/'. strtolower($this->action) . '.php';
    }

    /**
     * 输出内容
     * @param string $tpl  模板路径
     * @param array $data  模板输值数据
     * @return array
     */
    public function yycms_display($tpl='',array $data=[]){
        $tpl = $tpl?:$this->tpl;
        $data = $data?:$this->data;
        return [$tpl,$data];
    }
    /**
     * 获取单条数据
     * @return mixed
     */
    public function yycms_getOne($model,$id)
    {
        return $model->get($id);
    }

    /**
    * 返回封装后的API数据到客户端
    * @access protected
    * @param  mixed     $data 要返回的数据
    * @param  integer   $count 返回的数据总数
    * @param  mixed     $msg 提示信息
    * @param  string    $type 返回数据格式
    * @param  array     $header 发送的Header信息
    * @return void
    */
    public function yycms_reponse($data,$count=0, $msg = '', $type = '', array $header = [])
    {
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
            'count'=> $count,
        ];
        $type     = $type ?: $this->getResponseType();
        $response = \think\Response::create($result, $type)->header($header);
        throw new \think\exception\HttpResponseException($response);
    }
    /**
     * 添加新数据
     * @param $model 数据操作的模型
     * @param $data 提交的数据 array $post
     * @param $rule 验证规则 array
     * @param $msg  验证规则提示信息
     */
    public function yycms_addData( array $arr = [] ){
        $data = $arr?:$this->post;
        $access_token = \yycms\Admin::sessionGet('user_sign');
        if($access_token==$data['access_token']){
            $validate = Validate::make($this->rule)->message($this->rulemsg);
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            if($this->model->allowField(true)->save($data)){
                $insertid = $this->model->id;
                $this->success('增加成功','',['insertid'=>$insertid]);
            }else{
                $this->error( '增加失败' );
            }
        }else{
            $this->result('','1002','Token验证失败');
        }
    }
    /**
     * 修改保存数据
     * @param $model 数据操作的模型
     * @param $data 提交的数据 array $post
     * @param $rule 验证规则 array
     * @param $msg  验证规则提示信息
     */
    public function yycms_editData( array $arr = [] ){
        $data = $arr?:$this->post;
        $access_token = \yycms\Admin::sessionGet('user_sign');
        if($access_token==$data['access_token']){
            $validate = Validate::make($this->rule)->message($this->rulemsg);
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            if($this->model->allowField(true)->isUpdate(true)->save($data)){
                $this->success('修改成功');
            }else{
                $this->error( '修改失败' );
            }
        }else{
            $this->result('','1002','Token验证失败');
        }
    }
    /**
     * 删除数据
     * @param $model 数据操作的模型
     * @param $data 提交的数据 array $post
     * @param $rule 验证规则 array
     * @param $msg  验证规则提示信息
     */
    public function yycms_delData( array $where = [] ){
        $data = $this->post;
        $id = $this->ids ? : $this->id;
        $access_token = \yycms\Admin::sessionGet('user_sign');
        if($access_token==$data['access_token']){
            $result = $this->model->get($id);
            if (method_exists($this->controller,'yycms_before_del')){
                return  call_user_func([$this->controller, 'yycms_before_del']);
            }
            if($result->delete()){
                if (method_exists($this->controller,'yycms_after_del')){
                    return  call_user_func([$this->controller, 'yycms_after_del']);
                }
                $this->success('删除成功');
            }else{
                $this->error( '删除失败' );
            }
        }else{
            $this->result('','1002','Token验证失败');
        }
    }
}