<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>tpshop商家管理后台</title>
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
   						layer.closeAll();
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
 

<div class="wrapper">
	<div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>


    <!-- Main content -->
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">

                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
                <a onclick="get_help(this)" data-url="http://www.tp-shop.cn/Doc/Indexbbc/article/id/1073/developer/user.html" class="btn btn-default" href="javascript:;"><i class="fa fa-question-circle"></i> 帮助</a>                                
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>快递公司</h3>
                </div>
                <div class="panel-body">
                    
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsForm">
                        <div class="tab-content">                          
                            <!-- 物流插件-->
                            <div class="tab-pane" id="tab_shipping" style="display:inherit;">
                                <div class="row">
                                    <?php if(is_array($shipping)): $i = 0; $__LIST__ = $shipping;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><div class="col-sm-2"  style="width:260px;">
                                            <div class="thumbnail">
                                                <img style="width:150px; height:60px;" src="/plugins/shipping/<?php echo ($l["code"]); ?>/<?php echo ($l["icon"]); ?>">
                                                <div class="caption">
                                                    <h4><?php echo ($l["name"]); ?></h4>
                                                    <p><?php echo ($l["desc"]); ?></p>
                                                    <?php if($l["status"] == 0): ?><p><a href="#" class="btn btn-primary" role="button">平台未开放此物流</a> </p>
                                                        <?php else: ?>
                                                        <p>
                                                            <a href="<?php echo U('Plugin/shipping_list',array('type'=>'shipping','code'=>$l['code']));?>" class="btn btn-primary" role="button">配置</a>
                                                            <?php if($l["is_close"] == 0): ?><a href="<?php echo U('Plugin/shipping_close_or_open',array('type'=>'shipping','code'=>$l['code'],'is_close'=>1));?>" class="btn btn-primary" role="button">启动</a>
                                                                <?php else: ?>
                                                                <a href="<?php echo U('Plugin/shipping_close_or_open',array('type'=>'shipping','code'=>$l['code'],'is_close'=>0));?>" class="btn btn-primary" role="button">关闭</a><?php endif; ?>
                                                        </p><?php endif; ?>
                                                </div>
                                            </div>
                                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                            <!-- 物流插件-->                                                     
                        </div>
                    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
    <!-- /.content -->
</div>
<script>
    //插件安装(卸载)
    function installPlugin(type,code,type2){

        var url = '/index.php?m=Admin&c=Plugin&a=install&type='+type+'&code='+code+'&install='+type2;

        $.get(url,function(data){
            var obj = JSON.parse(data);
            alert(obj.msg);
            //layer.alert(obj.msg, {icon: 2});  
            if(obj.status == 1){
                parent.location.reload();
            }
        })
    }

</script>
</body>
</html>