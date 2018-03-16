<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>tpshop管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
 	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/Public/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
    	folder instead of downloading all of them to reduce the load. -->
    <link href="/Public/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/Public/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />   
    <!-- jQuery 2.1.4 -->
    <script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="/Public/js/global.js"></script>
    <script src="/Public/js/myFormValidate.js"></script>    
    <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/Public/js/layer/layer-min.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script src="/Public/js/myAjax.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
   						if(data==1){
   							layer.msg('操作成功', {icon: 1});
   							$(obj).parent().parent().remove();
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
//   						layer.closeAll();
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }
    
    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }   
    
    function get_help(obj){
        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'), 
        });
    }
    </script>        
  </head>
  <body style="background-color:#ecf0f5;">
 

<script src="/Public/js/jquery.event.drag.js" type="text/javascript"></script>
<style type="text/css">
#container ,.container {
  width: <?php echo ($config["width"]); ?>px;
  height: <?php echo ($config["height"]); ?>px;
  text-align: left;
  margin: 0;
  position: relative;
  overflow: hidden;
  border: 1px solid #d2d2d2;
  background: url(<?php echo ($config["background"]); ?>) 0px 0px no-repeat;
}

.container .item {
  line-height: 20px;
  float: left;
  position: absolute;
  top: 0px;
  left: 0px;
  color: #666666;
  overflow: hidden;
  word-wrap: break-word;
  filter: alpha(opacity = 80);
  -moz-opacity: 0.8;
  opacity: 0.8;
  border: 1px dotted #999999;
  background: #ffffff;
  padding-left:4px;
  color: #000;
}

.container .selected {
  filter: alpha(opacity = 100);
  -moz-opacity: 1;
  opacity: 1;
  border: 1px solid #ff6600;
}

.container .item pre {
  height: 100%;
  float: left;
  padding: 0;
  margin: 0;
  border:0;
  cursor: move;
}

.container textarea {
  padding-left: 0px;
  margin: 0px;
  font-size: 12px;
  resize: none;
  outline: none;
  overflow: hidden;
  border: none;
}

.container .resize {
  height: 6px;
  width: 6px;
  position: absolute;
  bottom: 0px;
  right: 0px;
  overflow: hidden;
  cursor: nw-resize;
  background-color: #aaaaaa;
}
</style>
<div class="wrapper">
   <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <form action="edit_shipping_print" id="inputForm" method="post"  onsubmit="return checkForm()">  
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo ($plugin["name"]); ?>模板设置</h3>
                </div>
                <div class="panel-body">
            	    <nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				        	<div class="navbar-form  form-inline">
                                <div class="form-group">
                                	宽度：<input type="text" id="width" class="form-control" name="width" value="<?php echo ($config["width"]); ?>">
                                </div>
                                <div class="form-group">
                                	高度：<input type="text" class="form-control" id="height" name="height" value="<?php echo ($config["height"]); ?>">
                                </div>
                                <div class="form-group">
                                	偏移量X：<input type="text" id="offset_x" class="form-control" name="offset_x" value="<?php echo ($config["offset_x"]); ?>">
                                </div>
                                <div class="form-group">
                                	偏移量Y：<input type="text" class="form-control" id="offset_x" name="offset_y" value="<?php echo ($config["offset_y"]); ?>">
                                </div>
                                <div class="form-group">
                                	<input type="hidden" id="content_map" name="content" value="<?php echo ($plugin["config_value"]); ?>">
                                	<input type="hidden" name="code" value="<?php echo ($plugin["code"]); ?>">
                                	<input type="button" class="btn btn-default" onclick="GetUploadify(1,'background_input','plugins','setImg')" value="背景图片">
                                </div>
                             </div> 	
				      	</div>
	    			</nav> 	    			
	    			 <nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <div class="navbar-form form-inline">  
                                <div class="form-group">
                                    <select class="form-control" id="tagOption">
                                        <option value="">添加标签</option>
                                        <option value="发货点-名称">发货点-名称</option>
                                        <option value="发货点-联系人">发货点-联系人</option>
                                        <option value="发货点-电话">发货点-电话</option>
                                        <option value="发货点-省份">发货点-省份</option>
                                        <option value="发货点-城市">发货点-城市</option>
                                        <option value="发货点-区县">发货点-区县</option>
                                        <option value="发货点-手机">发货点-手机</option>
                                        <option value="发货点-详细地址">发货点-详细地址</option> 
                                        <option value="收件人-姓名">收件人-姓名</option> 
                                        <option value="收件人-手机">收件人-手机</option>
                                        <option value="收件人-电话">收件人-电话</option>                                      
                                        <option value="收件人-省份">收件人-省份</option>
                                        <option value="收件人-城市">收件人-城市</option>
                                        <option value="收件人-区县">收件人-区县</option>
                                        <option value="收件人-邮编">收件人-邮编</option>
                                        <option value="收件人-详细地址">收件人-详细地址</option>                                        
                                        <option value="时间-年">时间-年</option>
                                        <option value="时间-月">时间-月</option>
                                        <option value="时间-日">时间-日</option>
                                        <option value="时间-当前日期">时间-当前日期</option>
                                        <option value="订单-订单号">订单-订单号</option>
                                        <option value="订单-备注">订单-备注</option>
                                        <option value="订单-配送费用">订单-配送费用</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                	<input type="button" class="btn btn-default" id="deleteTag" value="删除标签">
                                </div>
                                <div class="form-group" style="margin-left:50px;">
                                	<input  id="background_input" name="background" type="hidden"  value="<?php echo ($config["background"]); ?>" />
                                	<input type="submit" class="btn btn-info" value="保存模板">
                                </div>	
                           </div>    
				      	</div>
	    			</nav>       
                    <!--表单数据-->
                    <div id="container" class="container">
                    	 <?php echo (htmlspecialchars_decode($plugin["config_value"])); ?>             
                    </div>
                </div>
            </div>
           </form>
        </div> 
    </section>
</div>
<script type="text/javascript">
$().ready(function() {

  var $inputForm = $("#inputForm");
  var $addTag = $("#addTag");
  var $tagOption = $("#tagOption");
  var $deleteTag = $("#deleteTag");
  var $container = $("#container");
  var $browserButton = $("#browserButton");
  var $background = $("#background_input");
  var $width = $("#width");
  var $height = $("#height");
  var zIndex = 1;

  bind($container.find("div.item"));

  // 标签选项

  $tagOption.change(function() {
    var value = $(this).find("option:selected").val();
    if (value != "") {
      bind($('<div class="item"><pre>' + value + '<\/pre><div class="resize"><\/div><\/div>').appendTo($container));
    }
    return false;
  });

  // 绑定
  function bind($item) {
    $item.drag("start", function(ev, dd) {
      var $this = $(this);
      dd.width = $this.width();
      dd.height = $this.height();
      dd.limit = {
        right: $container.innerWidth() - $this.outerWidth(),
        bottom: $container.innerHeight() - $this.outerHeight()
      };
      dd.isResize = $(ev.target).hasClass("resize");
    }).drag(function(ev, dd) {
      var $this = $(this);
      if (dd.isResize) {
        $this.css({
          width: Math.max(20, Math.min(dd.width + dd.deltaX, $container.innerWidth() - $this.position().left) - 2),
          height: Math.max(20, Math.min(dd.height + dd.deltaY, $container.innerHeight() - $this.position().top) - 2)
        }).find("textarea").blur();
      } else {
        $this.css({
          top: Math.min(dd.limit.bottom, Math.max(0, dd.offsetY)),
          left: Math.min(dd.limit.right, Math.max(0, dd.offsetX))
        });
      }
    }, {relative: true}).mousedown(function() {
      $(this).css("z-index", zIndex++);
    }).click(function() {
      var $this = $(this);
      $container.find("div.item").not($this).removeClass("selected");
      $this.toggleClass("selected");
    }).dblclick(function() {
      var $this = $(this);
      if ($this.find("textarea").size() == 0) {
        var $pre = $this.find("pre");
        var value = $pre.hide().text($pre.html()).html();
        $('<textarea>' + value + '<\/textarea>').replaceAll($pre).width($this.innerWidth() - 6).height($this.innerHeight() - 6).blur(function() {
          var $this = $(this);
          $this.replaceWith('<pre>' + $this.val() + '<\/pre>');
        }).focus();
      }
    });
  }

  // 删除标签
  $deleteTag.click(function() {
    $container.find("div.selected").remove();
    return false;
  });

  $background.bind("input propertychange change", function() {
    $container.css({
      background: "url(/" + $background.val() + ") 0px 0px no-repeat"
    });
  });

  // 宽度
  $width.bind("input propertychange change", function() {
    $container.width($width.val());
  });

  // 高度
  $height.bind("input propertychange change", function() {
    $container.height($height.val());
  });


});

function checkForm(e){
  if(e==null){
    if ($.trim($("#container").html()) == "") {
      alert('模板内容不能为空！')
          return false;
    }
    if($("#background_input").val() == ""){
        alert('请上传物流打印背景图片！')
        return false;
    }
    $("#content_map").val($("#container").html());
    return true;
  }
}

function setImg(value){
	  $("#background_input").val(value);
	  $("#container").css({
	       background: "url(" + value + ") 0px 0px no-repeat"
	  });
}
</script>
</body>
</html>