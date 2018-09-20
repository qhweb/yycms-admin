#使用说明：
YYAdmin For Layui 是一款采用Layui前端UI框架，遵循原生HTML/CSS/JS的书写与组织形式，门槛极低，拿来即用。其外在极简，却又不失饱满的内在，体积轻盈，组件丰盈，从核心代码到API的每一处细节都经过精心雕琢，非常适合界面的快速开发。YYAdmin 
首个版本发布于2018年金秋，她更多是为服务端程序员量身定做，你无需涉足各种前端工具的复杂配置，只需面对浏览器本身，让一切你所需要的元素与交互，从这里信手拈来。
##第一步：引入文件
```
CSS:
<link rel="stylesheet" type="text/css" href="https://cdn.yyinfos.com/font/yyicon.css" media="all">//YYAdmin专用图表库
<link rel="stylesheet" type="text/css" href="https://cdn.yyinfos.com/layui/css/layui.css" media="all">
JS:
<script src="https://cdn.yyinfos.com/layui/layui.js"></script>

````
##第二步：下载YYAdmin文件并解压
下载地址：
https://
```
在页面中引入引入YYAdmin Js框架
<script>layui.config({base: '/yycms/',version: '1.0.0'}).use('yycms',function(){
	var yycms = layui.yycms;
	//设置导航
	var navbar = yycms.navbar({
        elem: '#nav',
        url: 'json/menu.json',//你的json格式数据获取地址
        cached:true
    });
});
</script>

```
##首页代码
```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>YYAdmin框架</title>
    <meta name="keywords" content="" />
    <meta name="description" content="LarryCMS Version:1.09" />
    <meta name="Author" content="larry" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="Shortcut Icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="http://cdn.yyinfos.com/layui/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="http://cdn.yyinfos.com/font/yyicon.css" media="all">
</head>
<body class="layui-layout-body">
	<div class="layui-layout layui-layout-admin">
	  <div class="layui-header">
	    <div class="layui-main">
	      <div class="site-icon-mobile layui-hide-md"><i class="layui-icon">&#xe66b;</i></div>
	      <div class="layui-logo">YYCMS后台布局</div>
	      <ul class="layui-nav layui-layout-right">
	        <li class="layui-nav-item">
	          <a href="javascript:;">
	            <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
	            YYAdmin
	          </a>
	          <dl class="layui-nav-child">
	            <dd><a href="">基本资料</a></dd>
	            <dd><a href="">安全设置</a></dd>
	            <dd><a href="">退出</a></dd>
	          </dl>
	        </li>
	      </ul>
	    </div>
	  </div>
	  
	  <div class="layui-side layui-bg-black layui-side-menu">
	    <div class="layui-side-scroll" id="nav" lay-filter="nav"></div>
	  </div>
	  <div class="site-yycms-title layui-breadcrumb" id="YY_HEAD">
	      <a lay-href="/main">首页</a>
	      <a><cite>正文</cite></a>
	  </div>
	  <div class="layui-body site-yycms-body"  id="YY_BODY"></div>
	  <div class="layui-footer site-yycms-footer">
	    © yyinfos.com - 青海云音信息技术有限公司
	  </div>
	</div>
	<div class="site-tree-mobile layui-hide">
	    <i class="layui-icon">&#xe602;</i>
	</div>
	<div class="site-mobile-shade"></div>
</body>
<script src="http://cdn.yyinfos.com/layui/layui.js"></script>
<script>layui.config({base: '/yycms/',version: '1.0.0'}).use('yycms',function(){
	var yycms = layui.yycms;
	var navbar = yycms.navbar({
        elem: '#nav',
        url: 'json/menu.json',
        cached:true
    });
});
</script>
</html>
```
#框架函数说明
###1、数据表格
数据表格使用设置包括config、table、toobar3个元素,使用前需要引入yycms模块
```
layui.use('yycms',function(){
    var yycms=layui.yycms;
    var tableins = yycms.table({
        //表格操作相关url设置
        config:{
          addurl: 'add.html', // 数据操作添加地址
          addsaveurl:'json/addsave.json',// 保存添加地址，默认提交当前页面
          editurl: 'edit.html', //数据操作修改地址
          editsaveurl:'json/editsave.json',// 保存修改地址，默认提交当前页面
          delurl: 'json/del.json', //数据操作删除地址
        },
        //表格获取数据地址和列表设置，参数可以参照layui数据表格设置
        table:{
          url: 'json/list.json',
          cols: [[
              {checkbox: true, fixed: true},
              {field: 'id', title: 'ID', width: 80, fixed: true, sort: true,align:'center'},
              {field: 'title', title: '敏感词',edit:true},
              {field: 'replace', title: '过滤值',edit:true},
              {field: 'tel', title: '状态', width: 100,toolbar:"#status",align:'center'},
              {width: 150, align: 'center', toolbar: '#action', title: '操作'}
          ]]
        },
        //工具条按钮设置，此版本适用于layui 2.4.3
        //预设三个按钮事件功能，dialog（窗口打开），add(添加),delall(批量删除)，其他事件统一为跳转链接按钮
        toolbar:[
            {type:'search'},//开始搜索功能
            {event:'dialog',name:'弹窗',href:'',class:''},
            {event:'add',name:'添加',href:'html/add.html',class:'layui-btn-normal'},
            {event:'delall',name:'批量删除',href:'html/add.html',class:'layui-btn-danger'},
        ]
    });
});
```
###2、Charts图表
#####函数：yycms.charts({参数1，参数2})
参数1：图表显示的容器，必须是ID元素；
参数2：json对象，charts数据选项设置，**此处参照百度echarts APi文档**

#####代码示例：
```
layui.use('yycms',function(){
    var yycms=layui.yycms;
    yycms.charts({
        elem  : "#element",   //容器必须是ID
        option : {
            title : {
                text: '资源统计',
                x: 'center',
                textStyle: {
                    fontSize: 14
                }
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:["\u5355\u9875\u6a21\u578b","\u6587\u7ae0\u6a21\u578b","\u4e0b\u8f7d\u6a21\u578b"]
            },
            series : [{
                name:'资源统计',
                type:'pie',
                radius : '55%',
                center: ['50%', '50%'],
                data:[{"name":"\u5355\u9875\u6a21\u578b","value":128},{"name":"\u6587\u7ae0\u6a21\u578b","value":231},{"name":"\u4e0b\u8f7d\u6a21\u578b","value":230}]
            }]
        }
    });
});
```
#####效果：
<p align="center">
<img src="https://cdn.yyinfos.com/r/echarts_demo.jpg" />
</p>

###3、表单
##### 代码示例：
```
<form class="layui-form" action="json/edit.json" lay-filter="example">
    <div class="layui-form-item">
        <label class="layui-form-label">输入框</label>
        <div class="layui-input-block">
            <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码框</label>
        <div class="layui-input-block">
            <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">选择框</label>
        <div class="layui-input-block">
            <select name="interest" lay-filter="aihao">
                <option value=""></option>
                <option value="0">写作</option>
                <option value="1">阅读</option>
                <option value="2">游戏</option>
                <option value="3">音乐</option>
                <option value="4">旅行</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-input-block">
            <input type="checkbox" name="like[write]" title="写作">
            <input type="checkbox" name="like[read]" title="阅读">
            <input type="checkbox" name="like[daze]" title="发呆">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开关</label>
        <div class="layui-input-block">
            <input type="checkbox" name="close" lay-skin="switch" lay-text="ON|OFF">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单选框</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="1" title="男" checked="">
            <input type="radio" name="sex" value="0" title="女">
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">文本域</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入内容" class="layui-textarea" name="desc"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="submit">立即提交</button>
            <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="back">返回</button>
        </div>
    </div>
</form>
<!--
这里注意按钮的写法，提交按钮lay-filter="submit",返回按钮lay-filter="back"
-->
```
##### 数据初始化：
```
<script>
    var FormVal = {};//你的表单初始化数据
    这里有个坑，raido的数据格式必须是字符串格式，例如 ：正确格式：{"status":"1"},错误格式：{"status":1}
</script>
```
