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
            <div class="panel panel-default">           
                <div class="panel-body ">   
                   	<ul class="nav nav-tabs">
                        <?php if(is_array($group_list)): foreach($group_list as $k=>$vo): ?><li <?php if($k == 'smtp'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('System/index',array('inc_type'=>$k));?>" data-toggle="tab" onclick="goset(this)"><?php echo ($vo); ?></a></li><?php endforeach; endif; ?>                        
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="handlepost" action="<?php echo U('System/handle');?>">                    
                        <!--通用信息-->
                    <div class="tab-content" style="padding:20px 0px;">                 	  
                        <div class="tab-pane active" id="tab_smtp">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">邮件发送服务器(SMTP)：</td>
                                    <td class="col-sm-8">
                         				<input type="text" class="form-control" name="smtp_server" value="<?php echo ($config["smtp_server"]); ?>" >
                         				<p class="text-warning">发送邮箱的smtp地址。如: smtp.gmail.com或smtp.qq.com</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>服务器(SMTP)端口：</td>
                                    <td >
                                        <input type="text" class="form-control" name="smtp_port" value="<?php echo ((isset($config["smtp_port"]) && ($config["smtp_port"] !== ""))?($config["smtp_port"]):25); ?>" >
                                    	<p class="text-warning">smtp的端口。默认为25。具体请参看各STMP服务商的设置说明 （如果使用Gmail，请将端口设为465）</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>邮箱账号：</td>
                                    <td >
                         				<input type="text" class="form-control" name="smtp_user" value="<?php echo ($config["smtp_user"]); ?>" >
                                    </td>
                                </tr>  
                            	<tr>
                                    <td>邮箱密码：</td>
                                    <td >
                         				<input type="text" class="form-control" name="smtp_pwd" value="<?php echo ($config["smtp_pwd"]); ?>" >
										<p class="text-warning"> 该密码请根据STMP服务商的设置说明填写，QQ邮箱此处填写授权码。</p>
                                    </td>
                                </tr>
								<tr>
									<td>注册启用邮箱：</td>
									<td>
										<input id="turnOn" type="radio" class="" name="regis_smtp_enable" <?php if($config['regis_smtp_enable'] == 1): ?>checked<?php endif; ?> value="1" >是
										<input type="radio"  class="" name="regis_smtp_enable" <?php if($config['regis_smtp_enable'] == 0): ?>checked<?php endif; ?> value="0" >否
									</td>
								</tr>
               					<tr>
                                    <td>测试收件邮箱：</td>
                                    <td >
                                    	<div class="col-xs-3" style="margin-left:-15px;"><input type="text" class="form-control" name="test_eamil" value="<?php echo ($config["test_eamil"]); ?>"></div>
                         				<div><input type="button" value="测试发送" class="btn btn-info" onclick="sendEmail()"><span class="text-warning"> 首次请先保存配置再测试</span></div>                         				
                                    </td>
                                </tr>    
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><input type="hidden" name="inc_type" value="<?php echo ($inc_type); ?>"></td>
                                	<td class="text-right"><input class="btn btn-primary" type="button" onclick="adsubmit()" value="保存"></td></tr>
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
var flag = true;
function adsubmit(){
	check_form();
	if(flag){
		$('#handlepost').submit();
	}
}

function check_form(){
	if($('input[name="smtp_server"]').val() == ''){
		alert('请填写邮件发送服务器地址');
		flag = false;
		return;
	}
	if($('input[name="smtp_user"]').val() == '' || !checkEmail($('input[name="smtp_user"]').val())){
		alert('请填写正确的邮箱账号');
		flag = false;
		return;
	}
	if($('input[name="smtp_pwd"]').val() == ''){
		alert('请填写发送邮箱密码');
		flag = false;
		return;
	}
}

$(document).ready(function(){
	get_province();
});

function goset(obj){
	window.location.href = $(obj).attr('data-url');
}

function sendEmail(){
	if($('input[name="test_eamil"]').val() == '' || !checkEmail($('input[name="test_eamil"]').val())){
		alert('请填写正确的测试邮箱账号');
		return;
	}else{
		$.ajax({
			type : "post",
			data : $('#handlepost').serialize(),
			dataType : 'json',
			url : "<?php echo U('System/send_email');?>",
			success : function(res){
				if(res==1){
					layer.msg('发送成功', {icon: 1});
				}else{
					layer.msg('发送失败', {icon: 2,time: 2000});
				}
			}
		})
	}
}
</script>
</body>
</html>