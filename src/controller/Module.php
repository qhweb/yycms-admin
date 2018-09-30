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
class Module extends YYAdmin
{
    public function __construct($request,$controller){
        parent::__construct($request,$controller);
        $this->model = new \yycms\model\Module();
        $this->rule = [
            'title'  => 'require',
            'name'  => 'require|alpha|unique:yycms_module',
        ];
        $this->rulemsg = [
            'title.require' => '模型名称必须填写',
            'name.require' => '模型名必须填写',
            'name.alpha' => '模型名必须是字母',
            'name.unique' => '模型名已经存在',
        ];
    }

    /**
     * 模型列表
     */
    public function yycms_index(){
        if($this->isPost){
            $limit = $this->post['limit']?:15;
            $key = isset($this->post['key']) ? trim($this->post['key']) : '';
            $map = array();
            if (!empty($key)) {
                $map['title|name|description']  = ['like','%'.$key.'%'];
            }
            $result = $this->model->where($map)->paginate($limit)->toArray();
            return $this->yycms_reponse($result['data'],$result['total']);
        }
        return $this->yycms_display();
    }

    /**
     * 模型编辑
     * @return array|void
     */
    public function yycms_edit(){
        if($this->isPost){
            $this->checkTable('edit');
            return $this->yycms_editData();
        }else{
            $info = $this->model->get($this->id);
            $this->data['title'] = '编辑模型';
            $this->data['info'] = $info;
            return $this->yycms_display();
        }
    }

    /**
     * 模型添加
     * @return array|void
     */
    public function yycms_add(){
        if($this->isPost){
            $this->checkTable('add');
            return $this->yycms_addData();
        }else{
            return $this->yycms_display();
        }
    }
    /**
     * 模型删除
     */
    public function yycms_del(){
        if($this->isPost){
            //关联模型删除together
            $ids = explode(',',$this->ids);
            foreach ($ids as $v){
                $Module = $this->model->get($v,'field');
                $this->post['name'] = $Module->name;
                if($Module->together('field')->delete()){
                    $this->checkTable('del');
                }
            }
            $this->success('删除成功');
        }
    }

    /**
     * 模型数据表和字段处理
     * @param string $type   add,edit,del
     */
    protected function checkTable($type='add'){
        $prefix = \think\facade\Config::get('database.prefix');
        $tablename = $prefix .$this->post['name'];
        $tbStatus = $this->model->query("SHOW TABLES LIKE '". $tablename ."'");
        switch ($type) {
            case 'add':
                if ($tbStatus){
                    $this->error('数据表已经存在');
                }else{
                    try{
                        $this->model->execute("CREATE TABLE `".$tablename."` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='". $this->post['title'] ."';");
                    }catch(\Exception $e){
                        $this->error('数据表创建失败');
                    }
                }
                break;
            case 'edit':
                if(!$tbStatus){
                    $Module = $this->model->get($this->post['id']);
                    try{
                        $this->model->execute("CREATE TABLE `".$tablename."` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='". $this->post['title'] ."';");
                        foreach ($Module->field as $k => $val){
                            $this->model->execute($Module->field()->get_tablesql($val,$tablename));
                        }
                    }catch(\Exception $e){
                        $this->error('数据表创建失败');
                    }
                }
                break;
            case 'del':
                $this->model->execute("DROP TABLE IF EXISTS `".$tablename."`");
                break;
            default:
                break;
        }
    }
}