<style>
	/*图标管理*/
	.icons{ margin:0 0px;overflow: hidden;padding: 0;}
	.icons li{  margin:5px 0; text-align:center;  cursor:pointer;}
	.icons li i{ display:block; font-size:35px; margin:10px 0; line-height:60px; height:60px;}
	.icons li:hover{ background:rgba(13,10,49,.9); border-radius:5px; color:#fff;}
	.icons li:hover i{ font-size:50px;}
	#copyText{ width:0;height:0; opacity:0; position:absolute; left:-9999px; top:-9999px;}
</style>
<div class="layui-fluid">
    <div class="layui-tab layui-tab-brief">
      <ul class="layui-tab-title">
        <li class="layui-this">外部图标（<span class="layui-red wiconsLength"></span>）</li>
        <li>Layui图标（<span class="layui-red niconsLength"></span>）</li>
        <li>操作说明</li>
      </ul>
      <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <ul class="icons layui-row" id="wb"></ul>
        </div>
        <div class="layui-tab-item">
            <ul class="icons layui-row" id="nb"></ul>
        </div>
        <div class="layui-tab-item">
            <p>【点击可复制】此页面并非后台模版需要的，只是为了让大家了解都引入了哪些外部图标，实际应用中可删除。</p>
            <p>弹窗选择图标的时候，自动填充</p>
        </div>
      </div>
    </div> 
    <textarea id="copyText"></textarea>
</div> 		
<script> 
layui.use(['yycms','layer'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        element = layui.element;
        $ = layui.jquery,
        yycms = layui.yycms;
	var iconUrl = "http://cdn.yyinfos.com/font/yyicon.css";
    $.get(iconUrl,function(data){
        var iconHtml = '';
        var fanum=0;
        for(var i=1;i<data.split(".yycms-").length;i++){
            var css = data.split(".yycms-")[i];
            if (css.indexOf(":before")>0 ) {
                // console.log(css)
                iconHtml += "<li class='layui-col-xs4 layui-col-sm3 layui-col-md2 layui-col-lg1'>"+
                            "<i class='yycms-icon yycms-" + css.split(":before")[0] + "'></i>" +
                        "</li>";
                fanum++;
            };            
        }
        $("#wb").html(iconHtml);
        $(".wiconsLength").text(fanum);
    })
    $.get('http://cdn.yyinfos.com/layui/css/layui.css',function(data){
         var iconHtml = '';
         for(var i=1;i<data.split(".layui-icon-").length;i++){
             iconHtml += "<li class='layui-col-xs4 layui-col-sm3 layui-col-md2 layui-col-lg1'>"+
                             "<i class='layui-icon layui-icon-" + data.split(".layui-icon-")[i].split(":before")[0] + "'></i>"+
                         "</li>";
         }
         $("#nb").html(iconHtml);
         $(".niconsLength").text(data.split(".layui-icon-").length-1);
    })

    $("body").on("click",".icons li",function(){
       var layer_index = yycms.openDialog.index;
       var $css = $(this).find('i').attr('class');
       // console.log(layer_index);
        if (layer_index) {
        	//给父页面传值
			     parent.layui.$('input[name="icon"]').val($css);
           var id = "{:input('id')}";
           yycms.req({
            url:"{:url('menu/menuIcon')}",
            type:'post',
            data:{id:id,icon:$css}
           })
		       yycms.closeDialog();
        }else{
        	var copyText = document.getElementById("copyText");
	        copyText.innerText = $css;
	        copyText.select();
	        document.execCommand("copy");
        	layer.msg("复制成功",{anim: 2});
        }
        
    })
})


</script>