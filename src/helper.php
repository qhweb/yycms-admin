<?php
Route::get('yycms/yyadmin', "\\yycms\\lib\\File@openfile");
// 格式化文件路径
function get_file($file=''){
    return '/yycms/yyadmin/'.$file;
}
/**
 * @param string    $path
 * @param array     $param
 * @return bool
 */
function checkAuthPath($path,$param=[]){
    $result =  \yycms\Admin::checkPath($path,$param);
    return $result;
}
function checkPath($path,$param=[]){
    $result =  \yycms\Admin::checkPath($path,$param);
    return $result;
}
function access_token($post){
	$access_token = \yycms\Admin::sessionGet('user_sign');
	if($access_token==$post['access_token']){
		unset($post['access_token']);
		return $post;
	}else{
		return false;
	}
}
/**
 * PHP格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}
//文件单位换算
function byte_format($input, $dec=0){
    $prefix_arr = array("B", "KB", "MB", "GB", "TB");
    $value = round($input, $dec);
    $i=0;
    while ($value>1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec).$prefix_arr[$i];
    return $return_str;
}
//时间日期转换
function auth_toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date($format, $time );
}
/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
+----------------------------------------------------------
 */
function auth_rand_string($len=6,$type='',$addChars='') {
    $str ='';
    $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;

    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    $chars   =   str_shuffle($chars);
    $str     =   substr($chars,0,$len);
    return $str;
}
/**
 * 层级递归
 * @param  [type]  $arr   [description]
 * @param  integer $tid   [description]
 * @param  [type]  $level [description]
 * @return [type]         [description]
 */
function getLevelTree($arr,$level=0){
    static $res=[];//静态变量 只会被初始化一次
    foreach($arr as $k=>$v){
        if($v['children']){
            $tmp = $v;
            unset($tmp['children']);
            $tmp['child'] = 1;
            $tmp['level'] = $level;
            $res[] = $tmp;
            getLevelTree($v['children'],$level+1);
        }else{
            $tmp = $v;
            $tmp['child'] = 0;
            $tmp['level'] = $level;
            $res[] = $tmp;
        }
    }
    return $res;
}
?>
