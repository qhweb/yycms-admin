layui.define(['layer'],function(exports){
	var $ = layui.$
	,LAY_BODY ="YY_BODY"
	,layer=layui.layer
	,$win=$(window)
	,$body = $('body');
  var Admin = {
  	v:'1.0.1'
    ,page_title:'控制台'
    ,correctRouter: function(href){
      if(!/^\//.test(href)) href = '/' + href;
      //纠正首尾
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
  	//构造器
  	,view:function(id){
  	    return new Class(id);
  	}
  	,pageType:''
    ,success:function(msg,fun){
      layer.msg(msg,{icon:1,time:1000},function(res){
        typeof fun === 'function' && fun(res);
      })
    }
    ,error:function(msg){
      layer.alert(msg,{icon:2})
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
      options.data[tokenName] = tokenName in options.data   ?  options.data[tokenName] : (layui.data('yycms_data')[tokenName] || '');
      
      //自动给 Request Headers 传入 token
      options.headers[tokenName] = tokenName in options.headers  ?  options.headers[tokenName]  : (layui.data('yycms_data')[tokenName] || '');

      delete options.success;
      delete options.error;

      return $.ajax($.extend({
        type: 'get'
        ,dataType: 'json'
        ,success: function(res){
          //只有 response 的 code 一切正常才执行 done
          if(res.code ==1) {
            typeof options.done === 'function' && options.done(res); 
          } 
          
          //登录状态失效，清除本地 access_token，并强制跳转到登入页
          else if(res.code == 1001){
            location.href = res.url
          }
          
          //其它异常
          else {
            if(res['url']){
              layer.msg(res.msg,{icon:2,time:1000,function(){
                location.hash = res.url;
              }})
            }else{
              Admin.error(res.msg);
            }
            
          }
          
          //只要 http 状态码正常，无论 response 的 code 是否正常都执行 success
          typeof success === 'function' && success(res);
        }
        ,error: function(e, code){
          var error = '请求异常，请重试<br><cite>错误信息：</cite>'+ code ;
          Admin.error(error);
          typeof error === 'function' && error(res);
        }
      }, options));
    }
    ,openDialog:function(options) {
      return Admin.openDialog.index = layer.open($.extend({
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
  }
  //构造器
	,Class=function(id){
	    this.id = id;
	    this.container = $('#'+(id || LAY_BODY));
	};
  //请求模板文件渲染
  Class.prototype.render = function(views, params){
    var that = this, router = layui.router();
    views =  views + '.html';
    $('#'+ LAY_BODY).children('.layadmin-loading').remove();
    Admin.loading(that.container); //loading
    
    //请求模板
    $.ajax({
      url: views
      ,type: 'get'
      // ,dataType: 'json'
      ,data: {
        v: layui.cache.version
      }
      ,success: function(html){
        if (html.code == 0) {
          location.href = html.url;
        };
        // html = '<div>' + html + '</div>';
        var elemTitle = $(html).find('title')
        ,title = elemTitle.text();
        var res = {
          title: title
          ,body: html
        };
        
        elemTitle.remove();
        that.params = params || {}; //获取参数
        
        if(that.then){
          that.then(res);
          delete that.then; 
        }
        
       
        that.container.html(html);
        that.settitle(title);
        Admin.removeLoad();
        
        if(that.done){
          that.done(res);
          delete that.done; 
        }
        
      }
      ,error: function(e){
        Admin.removeLoad();
        
        if(that.render.isError){
          return Admin.error('请求视图文件异常，状态：'+ e.status)
        };
        
        if(e.status === 404){
          that.render(layui.cache.base+'template/404');
        } else {
          that.render(layui.cache.base+'template/error');
        }
        
        that.render.isError = true;
      }
    });
    return that;
  };
  //视图请求成功后的标题变更
  Class.prototype.settitle = function(title){
    title = title ? title : Admin.page_title
    $("#YY_HEADER").find('cite').text(title);
  };
  //视图请求成功后的回调
  Class.prototype.then = function(callback){
    this.then = callback;
    return this;
  };
  
  //视图渲染完毕后的回调
  Class.prototype.done = function(callback){
    this.done = callback;
    return this;
  };
  //事件
  var events = Admin.events = {
    //弹出面板事件
    dialog:function(){
      var that = $(this),
      mobile = Admin.screen() < 2,
      url = that.data("href"),
      width = (that.data("width")|| '700')+'px',
      height = (that.data("height")||'500')+'px';
      title = that.text();
      Admin.openDialog({
          id: "openDialog"+Math.ceil(Math.random()*10),
          area: [width, height],
          title:title,
          success: function() {
              Admin.view(this.id).render(url)
          }
      })
      if(mobile){
        layer.full(layerid);
      }
      return false;
    }
    ,delete:function(){
      var that   = $(this)
          ,index = that.parents('tr').eq(0).data('index')
          ,tr    = $body.find('tr[data-index="'+ index +'"]')
          ,href  = that.data('href');
      layer.confirm('确定要删除吗？', function(index){
        layer.close(index);
        Admin.req({
          url:href||''
          ,type:'post'
          ,success:function(res){
            if(res.code){
              layer.msg(res.msg,{time:1000,icon:1});
              tr.remove();
            }else{
              Admin.error(res.msg)
            }
          }
        });
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
  $body.on('click', '*[lay-href]', function(){
    var othis = $(this)
    ,href = othis.attr('lay-href')
    ,title = othis.text()
    ,router = layui.router();
    Admin.page_title = title;
    //执行跳转
    location.hash = Admin.correctRouter(href);
  });
  exports('admin', Admin);
}); 
//纠正路由格式
    