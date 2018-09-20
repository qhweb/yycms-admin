/**

 @Name：全局配置
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL（layui付费产品协议）
    
 */
 
layui.define('element', function(exports){
  exports('api', {
    messsage:{
    	all:'./json/message/all.js',//所有信息
    	notice:'./json/message/notice.js',//通知信息
    	direct:'./json/message/direct.js',//私信信息
    	del:'./json/message/direct.js',//删除操作
    	ready:'./json/message/direct.js',//已读标记操作
    	readyall:'./json/message/direct.js',//所有已读标记操作
    	replay:'./json/message/direct.js',//回复操作
    }
    ,database:{
    	table_list:'/admin/database/database.html',//数据表列表
    	restore_list:'/admin/database/restore.html',//还原列表
    	bakup:'/admin/database/backup.html',//备份操作
    	optimize:'/admin/database/optimize.html',//优化操作
    	repair:'/admin/database/repair.html',//修复操作
    	restoreData:'/admin/database/restoreData.html',//导入操作
    	delSqlFiles:'/admin/database/delSqlFiles.html',//删除操作
    }
    ,user:{
    	resetpass:'/admin/user/resetpass',//重置密码
    	forget:'/admin/user/forget',//找回密码
    	reg:'/admin/user/reg',//用户注册
    	login:'/admin/user/login',//用户登录
    	logout:'/admin/user/logout',//用户退出
    	info:'/admin/user/user_info',//用户资料
    	setpass:'/admin/user/user_pass',//用修改密码
    }
    ,upload:{
    	image:'',
    	file:''
    }
  });
});