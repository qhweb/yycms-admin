var config = {
    root:''
    ,default:{
        index:'admin/index/main'
    }
    ,loginurl:'/admin/user/login'
}
layui.define(['layer','element','form','table'],function(exports){
    var $ = layui.$,LAY_BODY ="YY_BODY",layer=layui.layer,form=layui.form,table=layui.table; element=layui.element ;$win=$(window),$body = $('body'),cacheName='YYCMS_DATA',MOD_NAME='yycms';
    var YYClass = {
        v:'1.0.1'
        ,$:$
        ,TableIns:''
        ,CurrentUrl:''
        ,events:{}
        ,keyword:''
        ,page_title:'控制台'
        ,correctRouter: function(href){
            if(!/^\//.test(href)) href = '/' + href;//纠正首尾
            return href.replace(/^(\/+)/, '/');
        }
        //屏幕类型
        ,screen: function(){
            var width = $win.width()
            if(width >= 1200){
                return 3; //大屏幕
            } else if(width >= 992){
                return 2; //中屏幕
            } else if(width >= 768){
                return 1; //小屏幕
            } else {
                return 0; //超小屏幕
            }
        }
        //侧边伸缩
        ,sideFlexible: function(status){
            console.log(status);
        }
        //视图构造器
        ,view:function(id){
            return new ViewClass(id);
        }
        //视图构造器
        ,table:function(id){
            return new TableClass(id);
        }
        //视图构造器
        ,navbar:function(opt){
            var obj = new Navbar();
            obj.set(opt);
            obj.render();
            obj.on('click(nav)', function(data) {
                if (data.field.href) {
                    $body.removeClass('site-mobile');
                    location.hash = YYClass.correctRouter(data.field.href);
                };
            });
        }
        ,success:function(msg,fun){
            layer.msg(msg,{icon:1,time:1000},function(res){
                typeof fun === 'function' && fun(res);
            })
        }
        ,error:function(msg){
            layer.msg(msg,{icon:2,time:1000})
        }
        ,alert:function(msg){
            layer.alert(msg)
        }
        ,msg:function(msg){
            layer.msg(msg)
        }
        //加载中
        ,loading:function(elem){
            elem.append(
                this.elemLoad = $('<i class="layui-anim layui-anim-rotate layui-anim-loop layui-icon layui-icon-loading"></i>')
            );
        }
        //移除加载
        ,removeLoad:function(){
            this.elemLoad && this.elemLoad.remove();
        }
        //Ajax请求
        ,req : function(options){
            var that = this
                ,success = options.success
                ,error = options.error
            options.data = options.data || {};
            options.headers = options.headers || {};

            //自动给参数传入默认 token
            var tokenName = 'access_token';
            options.data[tokenName] = tokenName in options.data   ?  options.data[tokenName] : (layui.data(cacheName).LoginData.access_token || '');

            //自动给 Request Headers 传入 token
            options.headers[tokenName] = tokenName in options.headers  ?  options.headers[tokenName]  : (layui.data(cacheName).LoginData.access_token || '');

            delete options.success;
            delete options.error;
            return $.ajax($.extend({
                type: 'get'
                ,dataType: 'json'
                ,beforeSend:function(){
                    var loading = layer.load(2, {shade: [0.1, '#fff']});
                }
                ,success: function(res){
                    //只有 response 的 code 一切正常才执行 done
                    if(res.code ==1) {
                        typeof options.done === 'function' ? options.done(res) : YYClass.reqmsg(res);
                    }else if(res.code == 1002){
                        YYClass.events.lock.call();
                    }else if(res.code == 1001){
                        //登录状态失效，清除本地 access_token，并强制跳转到登入页
                        layui.data(cacheName, null);
                        location.href = res.url
                    }else {//其它异常
                        if(res['url']){
                            layer.msg(res.msg,{icon:2,time:1000})
                        }else{
                            YYClass.error(res.msg);
                        }
                    }
                    //只要 http 状态码正常，无论 response 的 code 是否正常都执行 success
                    typeof success === 'function' && success(res);
                }
                ,error: function(e, code){
                    var text = {
                        parsererror:'解析器错误',error:'未知错误'
                    }
                    var error = '请求异常，请重试<br><cite>错误信息：</cite>'+ text[code]||code ;
                    YYClass.error(error);
                    typeof error === 'function' && error(res);
                }
                ,complete:function(xhr,status){
                    layer.closeAll('loading');
                }
            }, options));
        }
        ,reqmsg:function(res){
            if (res.code == 1) {
                YYClass.success(res.msg);
            }else{
                YYClass.error(res.msg);
            }
            // if (res.url) {
            //   location.hash = res.url;
            // };
        }
        ,openDialog:function(options) {
            return YYClass.openDialog.index = layer.open($.extend({
                type: 1,
                id: "openDialog",
                anim: -0,
                title: '提示',
                shade: .1,
                shadeClose: !0,
                skin: "layui-anim layui-anim-scale,layui-layer-rim'",
                area: ["580px","300px"],
            },options))
        }
        ,closeDialog:function(index) {
            return layer.close(index||YYClass.openDialog.index);
        }
        /**
         * 页面入口
         * @return {[type]}      [description]
         */
        ,entryPage : function(){
            YYClass.events.checklock.call(this);
            //构造视图显示器
            var router = layui.router(),container = YYClass.view(),path = router.path;
            //默认读取主页
            if(!path.length) path = [''];
            //如果最后一项为空字符，则读取默认文件
            if(path[path.length - 1] === ''){
                path[path.length - 1] = config.default.index||'index';
            }
            if(path.length <3){
                path[path.length + 1] = 'index';
            }
            pathURL = YYClass.correctRouter(path.join('/'));
            container.render(pathURL).done(function(){
                YYClass.renderPage(container.id);
                layui.element.render();
                YYClass.InitForm();
                //手机端隐藏侧栏
                if(YYClass.screen() < 2){
                    YYClass.sideFlexible();
                }
            });
        }
        ,renderPage : function(container){
            $("#"+container).scrollTop(0);
            //重置导航栏选中状态
            var elemTemp = $('.layui-side').find('a');
            elemTemp.removeClass('layui-this');
            for(var i = elemTemp.length; i > 0; i--){
                var dataElem = elemTemp.eq(i - 1);
                url = dataElem.attr('yycms-href');
                if (url == pathURL) {
                    dataElem.addClass('layui-this');
                };
            }
        }
        ,charts:function(options){
            return new ChartsClass(options);
        }
        ,dialog_iframe:function(obj){
            var DW = $(obj).data('width') ?  $(obj).data('width') : '50%', DH = $(obj).data('height') ? $(obj).data('height'): '50%';
            var Tplurl = $(obj).data('href')||layui.cache.base+'template/404.tpl',title = $(obj).text(),dialogid = 'dialog'+Math.random().toString(36).substr(2);
            var objRegExp= /^\d+(px|%)$/;
            DW = objRegExp.test(DW) ? DW : DW+'px';
            DH = objRegExp.test(DH) ? DH : DH+'px';
            YYClass.openDialog({
                title: title,
                area: [DW, DH],
                content: '<div id="'+dialogid+'" style="padding:15px;"></div>'
            });
            Tplurl  = YYClass.correctRouter(Tplurl);
            ocontainer = YYClass.view(dialogid);
            ocontainer.render(Tplurl).done(function(){
                $("#"+ocontainer.id).scrollTop(0);
                layui.element.render();
                YYClass.InitForm();
            });
        },
        // 表单初始化
        InitForm:function(){
            form.render();
            var currurl = YYClass.CurrentUrl||layui.router().href;
            if ($body.find('form.layui-form').length > 0){
                $.each($body.find('form.layui-form'),function (index,item) {
                    var formurl = $(this).attr('action')|| currurl;
                    var layer_index = YYClass.openDialog.index;
                    var filter_name = $(this).attr('lay-filter');
                    var dataVal =  "undefined" == typeof FormVal ? {} : FormVal;
                    //表单初始赋值
                    form.val(filter_name, dataVal);
                    /**
                     * 表单返回事件
                     */
                    YYClass.events.back = function(){
                        var layer_index = YYClass.openDialog.index;
                        //console.log(layer_index);
                        if (layer_index > 0) {
                            parent.layer.close(layer_index);
                        }else{
                            history.back(-1);
                        }
                    }
                    /**
                     * 表单提交事件
                     */
                    form.on('submit(submit)', function (data) {
                        var submiturl = $(this).data('href')||formurl;
                        //获取编辑器数据
                        if (typeof editor!="undefined"){
                            data.field[editor.key] = editor.body.innerHTML
                        }

                        YYClass.req({
                            url:submiturl,
                            type:'post',
                            data:data.field,
                            done:function (res) {
                                if (res.code > 0) {
                                    layer.msg(res.msg, {time: 1000, icon: 1}, function () {
                                        if (layer_index > 0) {
                                            if (typeof parent.refresh == 'function') {
                                                parent.refresh();
                                            }else{
                                                table.reload(YYClass.TableIns);
                                            }
                                            parent.layer.close(layer_index);
                                        }else{
                                            location.hash = YYClass.correctRouter(res.url);
                                        }
                                    });
                                } else {
                                    layer.msg(res.msg, {time: 1000, icon: 2});
                                }
                            }
                        });
                        return false;
                    });
                })
            }
        }
    };
    //数据图表构造器
    var ChartsClass=function(options){
        this.config = {
            elem  : options.elem||"",   //容器di
            option  : options.option||{}  //表数据
        };
        return this.render();
    };
    ChartsClass.prototype.render = function () {
        var that = this;
        if(that.elem == ''){
            YYClass.error("eCharts error: elem参数未定义或设置出错，具体设置格式请参考文档API.");
        };
        layui.extend({
            echarts: '{/}http://www.admin.com/yycms/charts/echarts',
            echartsTheme :'{/}http://www.admin.com/yycms/charts/echartsTheme',
        }).use(['echarts','echartsTheme'],function(){
            var myecharts = layui.echarts;
            var yycmsChart = myecharts.init(document.getElementById(that.config.elem),layui.echartsTheme);
            return yycmsChart.setOption(that.config.option);
            window.onresize = yycmsChart,resize;
        });
    };
    //表格构造器
    var TableClass=function(options){
        this.id = 'list'+Math.random().toString(36).substr(2);
        this.search = "YYCMS_SEARCH_KEYWORD";
        this.boxid = "#"+(options.config.id||'yycms-table');
        this.editTable = "edit("+this.id+")";
        this.toolTable ="tool("+this.id+")";
        this.toolbarTable ="toolbar("+this.id+")";
        this.data={};
        /**
         *  默认配置
         */
        this.config = {
            pk: 'id', //主键
            addurl: '', // 数据操作添加地址
            addsaveurl:'',
            editurl: '', //数据操作修改地址
            editsaveurl:'',
            delurl: '' //数据操作删除地址
        };
        this.toolbar={};
        return this.set(options)
    };
    /**
     * 配置tableConfig
     * @param {Object} options
     */
    TableClass.prototype.set = function (options) {
        var that = this;
        $.extend(true, that.config,options.config);
        $(this.boxid).html('<div id="'+this.id+'" lay-filter="'+this.id+'"></div>');
        that.tool(options);
        return that.render(options.table);
    };
    /**
     * 配置toolbar
     * @param {Object} options
     */
    TableClass.prototype.tool = function (options) {
        var that =this;
        var btns = options.toolbar||[];
        var toolbar = [];
        toolbar = toolbar.concat(btns);
        var tbboxid = this.id;
        var searchclass = this.search;
        var str = '<script type="text/html" id="'+tbboxid+'_toolbar">';
        str +='<div class="layui-btn-container">';
        $.each(toolbar,function(index,btn){
            if (btn.hasOwnProperty('type') && btn.type == 'search'){
                str += '<input class="layui-input layui-input-inline layui-btn-sm" value="" style="width:150px;height:31px;float:left;margin-right:10px;" name="'+searchclass+'" placeholder="请输入关键字">';
                str += '<button class="layui-btn layui-btn-sm" id="search" lay-event="'+tbboxid+'_search" style="float: left">搜索</button><a class="layui-btn layui-btn-sm" style="float: left" lay-event="'+tbboxid+'_searchall">刷新</a>';
            }else{
                str +='<button class="layui-btn layui-btn-sm '+btn.class+'" data-url="'+btn.href+'" data-height="'+btn.height+'" data-width="'+btn.width+'" lay-event="'+tbboxid+'_'+btn.event+'">'+btn.name+'</button>';
            }
        })
        str +='</div></script>';
        $(this.boxid).append(str);
        return str;
    };
    /**
     * 数据表格输出
     * @param {Object} options
     */
    TableClass.prototype.render = function (options) {
        var that = this;
        var tbConfig = {
            elem: '#'+that.id,//容器ID
            toolbar: '#'+that.id+'_toolbar',
            method: 'post',
            cellMinWidth: 80,
            height:'full-232',
            cols: [],
            page:true,
            limit: 15,
            limits:[15,30,45,60,90,120,1000],
            done:function (res) {
                $('input[name='+that.search+']').val(res.key);
                that.data = res.data;
            }
        };
        $.extend(true, tbConfig,options);
        table.render(tbConfig);
        //头工具栏事件
        table.on(that.toolbarTable, function(obj){
            var checkStatus = table.checkStatus(that.id);
            YYClass.TableIns = that.id;
            switch(obj.event){
                case that.id+'_search':
                    YYClass.keyword = $(that.boxid).find('input[name='+that.search+']').val();
                    table.reload(that.id,{
                        where:{
                            key:YYClass.keyword
                        }
                    });
                    break;
                case that.id+'_searchall':
                    table.reload(that.id,{
                        where:{key:''}
                    });
                    break;
                case that.id+'_delall':
                    var data = checkStatus.data,ids=[];
                    if (data.length == 0) {
                        YYClass.error('请选择要删除的数据');
                    }else{
                        layer.confirm('您确定要删除该记录吗？', function(index){
                            $(data).each(function (i, o) {
                                ids.push(o[that.config.pk]);
                            });
                            YYClass.req({
                                type:'post',
                                url:that.config.delurl,
                                data:{ids:ids},
                                done:function(res){
                                    if (res.code === 1) {
                                        YYClass.success(res.msg,function(){
                                            table.reload(that.id);
                                        })
                                    } else {
                                        YYClass.error(res.msg);
                                    }
                                }
                            })
                        });
                    }
                    break;
                case that.id+'_add':
                    var href = $(this).data('url')||that.config.addurl;
                    location.hash = YYClass.correctRouter(href);
                    break;
                case that.id+'_adddialog':
                    var obj=$(this),href = obj.data('url')||that.config.addurl;
                    obj.attr('data-href',href);//重设地址
                    YYClass.dialog_iframe(obj);
                    break;
                case that.id+'_dialog':
                    YYClass.dialog_iframe(this);
                    break;
                case that.id+'_link':
                    var href = $(this).data('url')||'';
                    if (href != '' ){
                        location.hash = YYClass.correctRouter(href);
                    }else{
                        YYClass.error('没有设置URL链接')
                    }
                    break;
                default:
                    break;
            };
        });
        /**
         * 监听单元格编辑
         */
        table.on(that.editTable, function(obj){
            var val = obj.value,data = obj.data,field = obj.field,posturl = that.config.editsaveurl||that.config.editurl;
            data[field] = val;
            YYClass.req({
                url:posturl,
                type:'post',
                data:data
            })
        });
        /**
         * 表格相关操作
         */
        table.on(that.toolTable, function(obj){
            YYClass.TableIns = that.id;
            var data = obj.data;
              if(obj.event === 'del'){
                layer.confirm('您确定要删除该记录吗？', function(index){
                    YYClass.req({
                        type:'post',
                        url:that.config.delurl,
                        data:{ids:data[that.config.pk]},
                        done:function(res){
                            if (res.code === 1) {
                                YYClass.success(res.msg,function(){
                                    obj.del();
                                })
                            } else {
                                YYClass.error(res.msg);
                            }
                        }
                    })
                });
            }else if (obj.event === 'switch'){
                  data[obj.name] = obj.value;
                  var posturl = that.config.editsaveurl||that.config.editurl;
                  YYClass.req({
                      url:posturl,
                      type:'post',
                      data:data
                  })
            }else if(obj.event === 'edit') {
                var DT = $(this).data('type'),
                    title = $(this).text();
                var editurl = $(this).data('href') || that.config.editurl+'?'+that.config.pk+'='+data[that.config.pk];
                editurl = YYClass.correctRouter(editurl);
                if (DT === 'dialog') {
                    $(this).attr('data-href',editurl);//重设地址
                    YYClass.dialog_iframe(this);
                }else if(DT === 'prompt'){
                    var DT = $(this).data('type'),field = $(this).data('field');
                    layer.prompt({formType: 2 ,title: title ,value: data[field] }, function(value, index){
                        layer.close(index);
                        data[field] = value;
                        var loading = layer.load(1, {shade: [0.1, '#fff']});
                        YYClass.req(
                            {
                                url:editurl,
                                typeo:'post',
                                data:data,
                                done:function (){
                                    if(res.code === 1){
                                        layer.msg(res.msg, {time: 1000, icon: 1}, function () {
                                            table.reload(that.config.id)
                                        });
                                    }else{
                                        layer.msg(res.msg,{time:1000,icon:2});
                                    }
                                }
                            }
                        )
                    });
                }else{
                    location.hash = editurl;
                }
                return false;
            } else if(obj.event === 'shenhe') {
                var audit = function (status,content) {
                    data.status=status;
                    data.auditcontent=content;
                    $.post('/admin/article/audit',data,function(res){
                        if(res.code === 1){
                            layer.msg(res.msg, {time: 1000, icon: 1}, function () {
                                table.reload(tableConfig.id)
                            });
                        }else{
                            layer.msg(res.msg,{time:1000,icon:2});
                        }
                    })
                };
                var shenhe = function () {
                    layer.confirm('<textarea id="auditcontent" class="layui-textarea" placeholder="请输入审核意见" ></textarea>', {
                        btn: ['通过审核','不通过审核'],
                        title:'请输入审核意见',
                        btnAlign:'c',
                        area:['350px']
                    }, function(){
                        var c = $("#auditcontent").val();
                        if(c ==''){
                            layer.alert('请输入审核意见',function () {shenhe();});
                        }else{
                            audit(1,c);
                        }
                    }, function(){
                        var c = $("#auditcontent").val();
                        if(c ==''){
                            layer.alert('请输入审核意见',function () {shenhe();});
                        }else{
                            audit(0,c);
                        }
                    });
                };
                shenhe();
            }
        });
        /**
         * switch表单事件
         */
        form.on('switch(switch)', function(obj){
            var trid = $(this).parent().parent().parent().data('index'),posturl = that.config.editsaveurl||that.config.editurl,value = obj.elem.checked===true?1:0,fieldname = $(this).attr('name'),data={};
            data = that.data[trid];
            data[fieldname] = value;
            delete data.LAY_TABLE_INDEX;
            YYClass.req({
                url:posturl,
                type:'post',
                data:data
            })
        });
    };

    //构造器
    var ViewClass=function(id){
        this.id = id;
        this.container = $('#'+(id || LAY_BODY));
    };
    //请求模板文件渲染
    ViewClass.prototype.render = function(views, params){
        var that = this;
        // views =  views.indexOf(".html") > 0 ? config.root+views : config.root+views + '.html';
        views = config.root+views;
        YYClass.CurrentUrl = views;
        YYClass.loading(that.container); //loading
        //请求模板
        $.ajax({
            url: views
            ,type: 'get'
            // ,dataType: 'html'
            ,data: {v: layui.cache.version}
            ,success: function(html){
                if (typeof html == "object") {
                    layer.close(YYClass.openDialog.index);
                    if(html.code ==1) {
                        typeof options.done === 'function' ? options.done(html) : YYClass.reqmsg(html);
                    }else if(html.code == 1002){
                        YYClass.events.lock.call();
                    }else if(html.code == 1001){
                        //登录状态失效，清除本地 access_token，并强制跳转到登入页
                        layui.data(cacheName, {key: 'LoginData', value: ''});
                        location.href = html.url
                    }else {//其它异常
                        if(html['url']){
                            layer.msg(html.msg,{icon:2,time:1000});
                            location.hash = html.url;
                        }else{
                            YYClass.error(html.msg);
                        }
                    }
                    console.log(html);
                }
                html = '<div>' + html + '</div>';
                var elemTitle = $(html).find('title'),title = elemTitle.text();
                var res = {title: title,body: html};
                elemTitle.remove();//移除标题
                that.params = params || {}; //获取参数
                // 视图请求成功后的回调
                if(that.then){
                    that.then(res);
                    delete that.then;
                }
                that.container.html(html);// 写入html内容
                that.settitle(title);//设置页面标题
                YYClass.removeLoad();
                // 视图渲染完毕后的回调
                if(that.done){
                    that.done(res);
                    delete that.done;
                }

            }
            ,error: function(e){
                YYClass.removeLoad();
                if(that.render.isError){
                    return YYClass.error('请求视图文件异常，状态：'+ e.status);//错误信息提示
                };
                if(e.status === 404){
                    that.render(layui.cache.base+'template/404.tpl');//显示404错误模板
                } else {
                    that.render(layui.cache.base+'template/error.tpl');//显示error错误模板
                }

                that.render.isError = true;
            }
        });
        return that;
    };
    //视图请求成功后的标题变更
    ViewClass.prototype.settitle = function(title){
        title = title ? title : YYClass.page_title;
        $("#YY_HEADER").find('cite').text(title);//设置表标题
    };
    //视图请求成功后的回调
    ViewClass.prototype.then = function(callback){
        this.then = callback;
        return this;
    };

    //视图渲染完毕后的回调
    ViewClass.prototype.done = function(callback){
        this.done = callback;
        return this;
    };

    var Navbar = function () {
        /**
         *  默认配置
         */
        this.config = {
            elem: undefined, //容器
            data: undefined, //数据源
            url: undefined, //数据源地址
            type: 'GET', //读取方式
            cached: true, //是否使用缓存
            spreadOne: true //设置是否只展开一个二级菜单
        };
    };
    //渲染
    Navbar.prototype.render = function () {
        var _that = this;
        var _config = _that.config;
        if (typeof (_config.elem) !== 'string' && typeof (_config.elem) !== 'object') {
            YYClass.error('Navbar error: elem参数未定义或设置出错，具体设置格式请参考文档API.');
        }
        var $container;
        if (typeof (_config.elem) === 'string') {
            $container = $('' + _config.elem + '');
        }
        if (typeof (_config.elem) === 'object') {
            $container = _config.elem;
        }
        if ($container.length === 0) {
            YYClass.error('Navbar error:找不到elem参数配置的容器，请检查.');
        }
        if (_config.data === undefined && _config.url === undefined) {
            YYClass.error('Navbar error:请为Navbar配置数据源.')
        }
        if (_config.data !== undefined && typeof (_config.data) === 'object') {
            var html = _that.gethtml(_config.data);
            $container.html(html);
            element.init();
            _that.config.elem = $container;
        } else {
            if (_config.cached) {
                var cacheNavbar = layui.data(cacheName);
                if (cacheNavbar.navbar === undefined) {
                    YYClass.req({
                        type: _config.type,
                        url: _config.url,
                        async: false, //_config.async,
                        dataType: 'json',
                        done: function (result, status) {
                            //添加缓存
                            layui.data(cacheName, {
                                key: 'navbar',
                                value: result.data
                            });
                            var html = _that.gethtml(result.data);
                            $container.html(html);
                            element.init();
                        },
                        error: function (error) {
                            YYClass.error('Navbar error:' + error);
                        },
                        complete: function () {
                            _that.config.elem = $container;
                            layer.closeAll('loading');
                        }
                    });
                } else {
                    var html = _that.gethtml(cacheNavbar.navbar);
                    $container.html(html);
                    element.init();
                    _that.config.elem = $container;
                }
            } else {
                //清空缓存
                layui.data(cacheName, null);
                YYClass.req({
                    type: _config.type,
                    url: _config.url,
                    async: false, //_config.async,
                    dataType: 'json',
                    success: function (result) {
                        var html = _that.gethtml(result.data);
                        $container.html(html);
                        element.init();
                    },
                    error: function (error) {
                        YYClass.error('Navbar error:' + error);
                    },
                    complete: function (status) {
                        _that.config.elem = $container;
                        layer.closeAll('loading');
                    }
                });
            }
        }

        //只展开一个二级菜单
        if (_config.spreadOne) {
            var $ul = $container.children('ul');
            $ul.find('li.layui-nav-item').each(function () {
                $(this).on('click', function () {
                    $(this).siblings().removeClass('layui-nav-itemed');
                });
            });
        }
        return _that;
    };
    /**
     * 配置Navbar
     * @param {Object} options
     */
    Navbar.prototype.set = function (options) {
        var that = this;
        that.config.data = undefined;
        $.extend(true, that.config, options);
        return that;
    };
    /**
     * 绑定Navbar事件
     * @param {String} events
     * @param {Function} callback
     */
    Navbar.prototype.on = function (events, callback) {
        var that = this;
        var _con = that.config.elem;
        if (typeof (events) !== 'string') {
            YYClass.error('Navbar error:事件名配置出错，请参考API文档.');
        }
        var lIndex = events.indexOf('(');
        var eventName = events.substr(0, lIndex);
        var filter = events.substring(lIndex + 1, events.indexOf(')'));
        if (eventName === 'click') {
            if (_con.attr('lay-filter') !== undefined) {
                _con.children('ul').find('li').each(function () {
                    var $this = $(this);
                    if ($this.find('dl').length > 0) {
                        var $dd = $this.find('dd').each(function () {
                            $(this).on('click', function () {
                                var $a = $(this).children('a');
                                var href = $a.data('url');
                                var icon = $a.children('i:first').data('icon');
                                var title = $a.children('cite').text();
                                var data = {
                                    elem: $a,
                                    field: {
                                        href: href,
                                        icon: icon,
                                        title: title
                                    }
                                }
                                callback(data);
                            });
                        });
                    } else {
                        $this.on('click', function () {
                            var $a = $this.children('a');
                            var href = $a.data('url');
                            var icon = $a.children('i:first').data('icon');
                            var title = $a.children('cite').text();
                            var data = {
                                elem: $a,
                                field: {
                                    href: href,
                                    icon: icon,
                                    title: title
                                }
                            }
                            callback(data);
                        });
                    }
                });
            }
        }
    };
    /**
     * 清除Navbar缓存
     */
    Navbar.prototype.cleanCached = function () {
        layui.data(cacheName, null);
    };
    /**
     * 获取html字符串
     * @param {Object} data
     */
    Navbar.prototype.gethtml = function(data){
        var ulHtml = '<ul class="layui-nav layui-nav-tree beg-navbar">';
        for (var i = 0; i < data.length; i++) {
            if (data[i].spread) {
                ulHtml += '<li class="layui-nav-item layui-nav-itemed">';
            } else {
                ulHtml += '<li class="layui-nav-item">';
            }
            if (data[i].children !== undefined && data[i].children !== null && data[i].children.length > 0) {
                ulHtml += '<a href="javascript:;">';
                if (data[i].icon !== undefined && data[i].icon !== '') {
                    if (data[i].icon.indexOf('fa-') !== -1) {
                        ulHtml += '<i class="fa ' + data[i].icon + '" aria-hidden="true" data-icon="' + data[i].icon + '"></i>';
                    } else if (data[i].icon.indexOf('yycms-') !== -1 ){
                        ulHtml += '<i class="yycms-icon ' + data[i].icon + '" aria-hidden="true" data-icon="' + data[i].icon + '"></i>';
                    } else {
                        ulHtml += '<i class="layui-icon ' + data[i].icon + '" data-icon="' + data[i].icon + '"></i>';
                    }
                }
                ulHtml += '<cite>' + data[i].title + '</cite>'
                ulHtml += '</a>';
                ulHtml += '<dl class="layui-nav-child">'
                for (var j = 0; j < data[i].children.length; j++) {
                    var child = data[i].children[j].children;
                    ulHtml += '<dd title="' + data[i].children[j].title + '">';
                    //三级菜单
                    if (child !== undefined && child !== null && child.length > 0) {
                        ulHtml += '<a href="javascript:;">';
                        if (data[i].children[j].icon !== undefined && data[i].children[j].icon !== '') {
                            if (data[i].children[j].icon.indexOf('fa-') !== -1) {
                                ulHtml += '<i class="fa ' + data[i].children[j].icon + '" data-icon="' + data[i].children[j].icon + '" aria-hidden="true"></i>';
                            } else if (data[i].children[j].icon.indexOf('yycms-') !== -1) {
                                ulHtml += '<i class="yycms-icon ' + data[i].children[j].icon + '" data-icon="' + data[i].children[j].icon + '" aria-hidden="true"></i>';
                            } else {
                                ulHtml += '<i class="layui-icon ' + data[i].children[j].icon + '" data-icon="' + data[i].children[j].icon + '">' + data[i].children[j].icon + '</i>';
                            }
                        }
                        ulHtml += '<cite>' + data[i].children[j].title + '</cite>';
                        ulHtml += '</a>';

                        ulHtml += '<dl class="layui-nav-child">'
                        for (var t = 0; t < child.length; t++) {
                            ulHtml += '<dd title="' + child[t].title + '">';
                            var dataUrl = (child[t].href !== undefined && child[t].href !== '') ? 'data-url="' + child[t].href + '"' : '';
                            ulHtml += '<a href="javascript:;" ' + dataUrl + '>';
                            if (child[t].icon !== undefined && child[t].icon !== '') {
                                if (child[t].icon.indexOf('fa-') !== -1) {
                                    ulHtml += '<i class="fa ' + child[t].icon + '" data-icon="' + child[t].icon + '" aria-hidden="true"></i>';
                                } else if (child[t].icon.indexOf('yycms-') !== -1 ){
                                    ulHtml += '<i class="yycms-icon ' + child[t].icon+ '" aria-hidden="true" data-icon="' + child[t].icon + '"></i>';
                                } else {
                                    ulHtml += '<i class="layui-icon ' + child[t].icon + '" data-icon="' + child[t].icon + '"></i>';
                                }
                            }
                            ulHtml += '<cite>' + child[t].title + '</cite>';
                            ulHtml += '</dd>';
                        }
                        ulHtml += '</dl>';
                    }else{
                        var dataUrl = (data[i].children[j].href !== undefined && data[i].children[j].href !== '') ? 'data-url="' + data[i].children[j].href + '"' : '';
                        ulHtml += '<a href="javascript:;" ' + dataUrl + '>';
                        if (data[i].children[j].icon !== undefined && data[i].children[j].icon !== '') {
                            if (data[i].children[j].icon.indexOf('fa-') !== -1) {
                                ulHtml += '<i class="fa ' + data[i].children[j].icon + '" data-icon="' + data[i].children[j].icon + '" aria-hidden="true"></i>';
                            } else if (data[i].children[j].icon.indexOf('yycms-') !== -1 ){
                                ulHtml += '<i class="yycms-icon ' + data[i].children[j].icon + '" aria-hidden="true" data-icon="' + data[i].children[j].icon + '"></i>';
                            } else {
                                ulHtml += '<i class="layui-icon ' + data[i].children[j].icon + '"></i>';
                            }
                        }
                        ulHtml += '<cite>' + data[i].children[j].title + '</cite>';
                        ulHtml += '</a>';
                    }
                    ulHtml += '</dd>';
                }
                ulHtml += '</dl>';
            } else {
                var dataUrl = (data[i].href !== undefined && data[i].href !== '') ? 'data-url="' + data[i].href + '"' : '';
                ulHtml += '<a href="javascript:;" ' + dataUrl + '>';
                if (data[i].icon !== undefined && data[i].icon !== '') {
                    if (data[i].icon.indexOf('fa-') !== -1) {
                        ulHtml += '<i class="fa ' + data[i].icon + '" aria-hidden="true" data-icon="' + data[i].icon + '"></i>';
                    } else if (data[i].icon.indexOf('yycms-') !== -1 ){
                        ulHtml += '<i class="yycms-icon ' + data[i].icon + '" aria-hidden="true" data-icon="' + data[i].icon + '"></i>';
                    } else {
                        ulHtml += '<i class="layui-icon ' + data[i].icon + '"></i>';
                    }
                }
                ulHtml += '<cite>' + data[i].title + '</cite>'
                ulHtml += '</a>';
            }
            ulHtml += '</li>';
        }
        ulHtml += '</ul>';
        return ulHtml;
    }
    //事件
    var events = YYClass.events = {
        /**
         * 关闭弹窗
         */
        close:function(){
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
        /**
         * 弹出面板事件
         * @return {[type]} [description]
         */
        ,dialog:function(){
            var that = $(this),mobile = YYClass.screen() < 2,url = that.data("href"),title = that.text();
            var width = that.data('width') ? that.data('width'):'50%',height = that.data('height') ? that.data('height') : '80%';
            var objRegExp= /^\d+(px|%)$/;
            width = objRegExp.test(width) ? width : width+'px';
            height = objRegExp.test(height)  ? height: height+'px';
            var layerid = YYClass.openDialog({
                id: "openDialog"+Math.ceil(Math.random()*10),
                area: [width, height],
                title:title,
                success: function() {
                    YYClass.view(this.id).render(url)
                }
            })
            if(mobile){
                layer.full(layerid);
            }
            return false;
        }
        /**
         * 删除事件
         * @return {[type]} [description]
         */
        ,delete:function(){
            var that   = $(this)
                ,index = that.parents('tr').eq(0).data('index')
                ,tr    = $body.find('tr[data-index="'+ index +'"]')
                ,href  = that.data('href');
            layer.confirm('确定要删除吗？', function(index){
                layer.close(index);
                YYClass.req({
                    url:href||''
                    ,type:'post'
                    ,success:function(res){
                        if(res.code){
                            layer.msg(res.msg,{time:1000,icon:1});
                            tr.remove();
                        }else{
                            YYClass.error(res.msg)
                        }
                    }
                });
            });
        }
        ,checklock:function(){
            var LoginData = layui.data(cacheName).LoginData||{UserName:'admin',isShowLock:false,access_token:null};
            if(LoginData.isShowLock || LoginData.access_token == null){
                events.lock.call(this);
            }
        }
        ,lock:function() {
            var LoginData = layui.data(cacheName).LoginData||{UserName:'admin',isShowLock:false,access_token:null};
            //自定页
            layer.open({
                title: false,
                type: 1,
                closeBtn: 0,
                anim: 6,
                content: $('#lock-temp').html(),
                shade: [0.9, '#393D49'],
                success: function(layero, lockIndex) {
                    if(LoginData.isShowLock == false){
                        LoginData.isShowLock =  true;
                        LoginData.access_token =  null;
                        //更新缓存
                        layui.data(cacheName, {
                            key: 'LoginData',
                            value: LoginData
                        });
                    }

                    //给显示用户名赋值
                    layero.find('div#lockUserName').text(LoginData.UserName);
                    layero.find('input[name=lockPwd]').on('focus', function() {
                        var $this = $(this);
                        if($this.val() === '输入密码解锁..') {
                            $this.val('').attr('type', 'password');
                        }
                    })
                        .on('blur', function() {
                            var $this = $(this);
                            if($this.val() === '' || $this.length === 0) {
                                $this.attr('type', 'text').val('输入密码解锁..');
                            }
                        });
                    //绑定解锁按钮的点击事件
                    layero.find('button#unlock').on('click', function() {
                        var $lockBox = $('div#lock-box');

                        var userName = $lockBox.find('div#lockUserName').text();
                        var pwd = $lockBox.find('input[name=lockPwd]').val();
                        if(pwd === '输入密码解锁..' || pwd.length === 0) {
                            YYClass.error('请输入密码..');
                            return;
                        }
                        YYClass.req({
                            url:config.loginurl,
                            type:'post',
                            data:{username:userName,password:pwd},
                            done:function(res){
                                if(res.code == 1){
                                    LoginData.isShowLock = false;
                                    LoginData.access_token =  res.data.access_token;
                                    //更新缓存
                                    layui.data(cacheName, {
                                        key: 'LoginData',
                                        value: LoginData
                                    });
                                    layer.close(lockIndex);
                                }else{
                                    YYClass.error('密码输入错误..');
                                }
                            }
                        })
                    });
                }
            });
        }
    };
    //点击事件
    $body.on('click', '*[yycms-event]', function(){
        var othis = $(this)
            ,attrEvent = othis.attr('yycms-event');
        events[attrEvent] && events[attrEvent].call(this, othis);
    });
    //页面跳转
    $body.on('click', '*[yycms-href]', function(){
        var othis = $(this),href = othis.attr('yycms-href'),title = othis.text(),router = layui.router();
        YYClass.page_title = title;
        //执行跳转
        location.hash = YYClass.correctRouter(href);
    });
    //初始主体结构
    layui.link(
        layui.cache.base+'skin/default.css?v='+ (YYClass.v + '-1'),function(){
            YYClass.entryPage()
        },'yycmsAdmin'
    );
    //监听Hash改变
    window.onhashchange = function(){
        YYClass.entryPage();
    };
    exports(MOD_NAME, YYClass);
}); 
    
