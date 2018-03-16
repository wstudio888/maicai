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
 


<link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content" style="padding:0px 15px;">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">           
                <div class="panel-body ">   
                   	<ul class="nav nav-tabs">
                        <?php if(is_array($group_list)): foreach($group_list as $k=>$vo): ?><li <?php if($k == 'shopping'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('System/index',array('inc_type'=>$k));?>" data-toggle="tab" onclick="goset(this)"><?php echo ($vo); ?></a></li><?php endforeach; endif; ?>                        
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="handlepost" action="<?php echo U('System/handle');?>">                    
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>                                    
                                <tr>
                                    <td class="col-sm-2">全场满多少免运费：<br/>(0表示不免运费)</td>
                                    <td class="col-sm-8">
                                        <input type="number"  pattern="^\d{1,}$" title="只能输入数字"  class="form-control" name="freight_free" value="<?php echo ((isset($config["freight_free"]) && ($config["freight_free"] !== ""))?($config["freight_free"]):'0'); ?>" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>    
                                <tr>
                                    <td>积分换算比例：</td>
                                    <td >                         		
                                        <input type="radio" class="" name="point_rate" value="1"  <?php if($config[point_rate] == 1): ?>checked<?php endif; ?> >1元 = 1积分  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="" name="point_rate" value="10" <?php if($config[point_rate] == 10): ?>checked<?php endif; ?> >1元 = 10积分  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="" name="point_rate" value="100"<?php if($config[point_rate] == 100): ?>checked<?php endif; ?> >1元 = 100积分
                                    </td>
                                </tr>    
                                <tr>
                                    <td>发货后多少天自动收货：</td>
                                    <td >                         		
                                        <select class="form-control" name="auto_confirm_date" id="distribut_date">
                                                <?php $__FOR_START_1396__=1;$__FOR_END_1396__=31;for($i=$__FOR_START_1396__;$i < $__FOR_END_1396__;$i+=1){ ?><option value="<?php echo ($i); ?>" <?php if($config[auto_confirm_date] == $i): ?>selected="selected"<?php endif; ?>><?php echo ($i); ?>天</option><?php } ?>
                                        </select>                                      
                                    </td>
                                </tr>
                                <tr>
                                    <td>分成时间：<br/>
                                    订单收货确认后多少天可以分成
                                    </td>
                                    <td >
                                        <select class="form-control" name="pay_date" id="pay_date">
                                                <?php $__FOR_START_23197__=1;$__FOR_END_23197__=31;for($i=$__FOR_START_23197__;$i < $__FOR_END_23197__;$i+=1){ ?><option value="<?php echo ($i); ?>" <?php if($config[pay_date] == $i): ?>selected="selected"<?php endif; ?>><?php echo ($i); ?>天</option><?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    收货多少天后：<br/>
                                    金额转入卖家平台余额
                                    </td>
                                    <td>
                                        <select class="form-control" name="auto_transfer_date" id="auto_transfer_date">
                                                <?php $__FOR_START_6930__=1;$__FOR_END_6930__=31;for($i=$__FOR_START_6930__;$i < $__FOR_END_6930__;$i+=1){ ?><option value="<?php echo ($i); ?>" <?php if($config[auto_transfer_date] == $i): ?>selected="selected"<?php endif; ?>><?php echo ($i); ?>天</option><?php } ?>
                                        </select>                                      
                                    </td>
                                </tr>                                
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><input type="hidden" name="inc_type" value="<?php echo ($inc_type); ?>"></td>
                                	<td class="text-right"><input class="btn btn-primary" type="buuton" onclick="adsubmit()" value="保存"></td></tr>
                                </tfoot>                               
                                </table>
                        </div>                           
                    </div>              
			    	</form><!--表单数据-->
                </div>
            </div>
        </div>
    </section>
</div>
<script>
function adsubmit(){
	$('#handlepost').submit();
}

$(document).ready(function(){
	get_province();
});

function goset(obj){
	window.location.href = $(obj).attr('data-url');
}
</script>

</body>
</html>