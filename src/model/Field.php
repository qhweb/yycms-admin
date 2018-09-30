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
namespace yycms\model;
class Field extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'yycms_field';
    //非int时间字段转换
    protected $autoWriteTimestamp = 'datetime';
    //初始化属性
    protected function initialize()
    {
    }

    /**
     * 字段验证规则
     * @return array
     */
    public function field_pattern(){
        return [
            ['name'=>'defaul','title'=>'默认'],
            ['name'=>'email','title'=>'电子邮件'],
            ['name'=>'url','title'=>'网址'],
            ['name'=>'date','title'=>'日期'],
            ['name'=>'number','title'=>'有效的数值'],
            ['name'=>'digits','title'=>'数字'],
            ['name'=>'equalTo','title'=>'再次输入相同的值'],
            ['name'=>'ip4','title'=>'IP'],
            ['name'=>'mobile','title'=>'手机号码'],
            ['name'=>'zipcode','title'=>'邮编'],
            ['name'=>'qq','title'=>'QQ'],
            ['name'=>'idcard','title'=>'身份证号'],
            ['name'=>'chinese','title'=>'中文字符'],
            ['name'=>'cn_username','title'=>'中文英文数字和下划线'],
            ['name'=>'tel','title'=>'电话号码'],
            ['name'=>'english','title'=>'英文'],
            ['name'=>'en_num','title'=>'英文数字和下划线'],
        ];
    }
    public function get_tablesql($info,$tablename,$do='add'){
        $info['setup'] = yycms_string2array($info['setup']);
        $fieldtype  =   isset($info['setup']['fieldtype'])  ?   $info['setup']['fieldtype'] :   $info['type'];
        $fieldtitle =   $info['name'];
        $default    =   isset($info['setup']['default']) ? $info['setup']['default'] : '';
        $field      =   $info['field'];
        $numbertype =   isset($info['setup']['numbertype']) ? $info['setup']['numbertype'] : '';
        $maxlength  =   $info['maxlength'];
        $oldfield   =   isset($info['oldfield']) ? $info['oldfield'] : '';
        $do         =   $do=='add' ? 'ADD' : " CHANGE `".$oldfield."` ";
        $sql        =   '';
        switch($fieldtype) {
            case 'varchar':
            case 'text':
            case 'catid':
            case 'title':
            case 'linkpage':
            case 'image':
            case 'file':
            case 'template':
                if(!$maxlength){$maxlength = 255;}
                $maxlength = min($maxlength, 255);
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;

            case 'number':
                $decimaldigits = isset($info['setup']['decimaldigits']) ? $info['setup']['decimaldigits'] : 0;
                $default = $decimaldigits == 0 ? intval($default) : floatval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` ".($decimaldigits == 0 ? 'INT' : 'decimal( 10,'.$decimaldigits.' )')." ".($numbertype ==1 ? 'UNSIGNED' : '')."  NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;

            case 'tinyint':
                if(!$maxlength) $maxlength = 3;
                $maxlength = min($maxlength,3);
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` TINYINT( $maxlength ) ".($numbertype ==1 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;

            case 'smallint':
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT ".($numbertype ==1 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;

            case 'int':
            case 'mediumint':
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` INT ".($numbertype ==1 ? 'UNSIGNED' : '')." NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;

            case 'mediumtext':
            case 'images':
            case 'files':
                $sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT NOT NULL COMMENT '$fieldtitle'";
                break;

            case 'editor':
            case 'textarea':
                $sql = "ALTER TABLE `$tablename` $do `$field` TEXT NOT NULL COMMENT '$fieldtitle'";
                break;

            case 'date':
                $sql = "ALTER TABLE `$tablename` $do `$field` date NOT NULL COMMENT '$fieldtitle'";
                break;
            default:
                if(!$maxlength){$maxlength = 255;}
                $maxlength = min($maxlength, 255);
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT '$default' COMMENT '$fieldtitle'";
                break;
        }
        return $sql;
    }
}