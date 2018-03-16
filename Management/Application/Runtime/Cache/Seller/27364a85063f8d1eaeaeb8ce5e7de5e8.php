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
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?php echo ($plugin["name"]); ?> <small></small> </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 后台首页</a></li>
            <li><a href="#" class="active"><?php echo ($plugin["name"]); ?>配送区域配置</a></li>
            <!--<li class="active">Data tables</li>-->
        </ol>
    </section>
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">

                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo ($plugin["name"]); ?>配送区域配置</h3>
                </div>
                <div class="panel-body">

                    <!--表单数据-->
                    <form method="post" id="addEditSpecForm" action="">
                        <input type="hidden" name="id" value="<?php echo ($setting["shipping_area_id"]); ?>">
                        <input type="hidden" name="default" value="1">

                        <!--通用信息-->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_tongyong">

                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>配送区域名称：</td>
                                            <td>
                                                <input readonly type="text" value="<?php echo ($setting["shipping_area_name"]); ?>" name="shipping_area_name"/>
                                            </td>
                                        </tr>
                                        <!--<tr>-->
                                            <!--<td>基本费用：</td>-->
                                            <!--<td>-->
                                                <!--<input type="text" value="<?php echo ($setting["config"]["base_fee"]); ?>" name="config[base_fee]"/>-->
                                            <!--</td>-->
                                        <!--</tr>-->
                                        <tr>
                                            <td>
                                            首&nbsp;&nbsp;重
                                            <select name="config[first_weight]">
	                                            <?php $__FOR_START_585019476__=500;$__FOR_END_585019476__=8000;for($v=$__FOR_START_585019476__;$v < $__FOR_END_585019476__;$v+=500){ ?><option value="<?php echo ($v); ?>" <?php if($setting[config][first_weight] == $v): ?>selected="selected"<?php endif; ?> ><?php echo ($v); ?></option><?php } ?>
                                            </select>
                                            克以内费用：
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo ($setting["config"]["money"]); ?>" name="config[money]"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            续重每
                                            <select name="config[second_weight]">
	                                            <?php $__FOR_START_768323702__=500;$__FOR_END_768323702__=8000;for($v=$__FOR_START_768323702__;$v < $__FOR_END_768323702__;$v+=500){ ?><option value="<?php echo ($v); ?>" <?php if($setting[config][second_weight] == $v): ?>selected="selected"<?php endif; ?> ><?php echo ($v); ?></option><?php } ?>
                                            </select>                                            
                                            克或其零数的费用：
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo ($setting["config"]["add_money"]); ?>" name="config[add_money]"/>
                                            </td>
                                        </tr>
                                        <!--<tr>-->
                                            <!--<td>免费额度：</td>-->
                                            <!--<td>-->
                                                <!--<input type="text" value="<?php echo ($setting["config"]["free"]); ?>" name="config[free]"/>-->
                                            <!--</td>-->
                                        <!--</tr>-->

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" title="" data-toggle="tooltip" type="submit"  data-original-title="保存"><i class="fa fa-save"></i></button>
                        </div>
			        </form><!--表单数据-->

                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>

</body>
</html>