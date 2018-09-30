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

namespace yycms\lib;

use think\facade\Response;
use think\facade\Request;
class File
{
	/**
     * 注册样式文件
     */
    public function openfile()
    {

        $text       = '';
        $file       = str_replace('static','',request()->path());
        $ext  = substr(strrchr($file, '.'), 1);
//        exit($file);
        switch ($ext)
        {
            case 'css':
                $text = 'text/css';
                break;
            case 'js':
                $text = 'text/js';
                break;
            case 'tpl':
                $text = 'text/html';
                break;
            case 'jpg':
                $text = 'image/jpg';
                break;
            case 'png':
                $text = 'image/png';
                break;
            case 'gif':
                $text = 'image/gif';
                break;
            default:
                return abort(404,'文件类型不支持');
        }
        $pach = YYCMS_STATIC. $file;
//        print_r($pach);exit;
        if (file_exists($pach)) {
        	$filecontent = file_get_contents($pach);
	        //正则url标签
	        preg_match_all ("/{:url\(['|\"]([\s\S]*?)['|\"](.*?)\)}/i", $filecontent,$arr);
	        foreach ($arr[1] as $k => $v) {
	           $filecontent = str_replace($arr[0][$k], url($v), $filecontent);
	        }
	        return Response::data($filecontent)->code(200)->contentType($text);
        }else{
        	return abort(404,'文件不存在');
        }
        
    }
}
