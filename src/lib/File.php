<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace yycms\lib;

use think\facade\Response;
use think\facade\Request;
class File
{
	/**
     * 注册样式文件
     */
    public function openfile($file = "",$ext = "")
    {

        $text       = '';
        $file       = strtr($file, '_', '/');
        switch ($ext)
        {
            case 'css':
                $text = 'text/css';
                break;
            case 'js':
                $text = 'text/js';
                break;
            default:
                return abort(404,'文件类型不支持');
        }

        $pach = __DIR__ .  '/../../asset/yyadmin/'.$file .'.' . $ext;
        // print_r($pach);
        // exit;
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
