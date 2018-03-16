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
 

<link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<style>
    .serch_btn{ width:100px; text-align:center; height:35px; line-height:35px; margin:auto; color:inherit;
        border:1px solid #d2d2d2; background:white; display:block; margin-top:10px; }
</style>
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
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回">
                    <i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-body ">
                    <ul class="nav nav-tabs">
                        <?php if(is_array($group_list)): foreach($group_list as $k=>$vo): ?><li
                            <?php if($k == 'setting'): ?>class="active"<?php endif; ?>
                            >
                            <a href="javascript:void(0)" data-url="<?php echo U('System/index',array('inc_type'=>$k));?>" data-toggle="tab" onclick="goset(this)"><?php echo ($vo); ?></a>
                            </li><?php endforeach; endif; ?>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="handlepost" action="<?php echo U('System/handle');?>">
                        <!--通用信息-->
                        <div class="tab-content col-md-10">
                            <div class="tab-pane active" id="tab_tongyong">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td class="col-sm-2">设置发货通知</td>
                                        <td class="col-sm-8">
                                            <input type="text" placeholder="昵称/姓名/手机号" id="user" class="form-control" name="freight_free" value="">
                                            <span class="serch_btn" onclick="checkuser()">搜索用户</span>
                                            <div style="text-align:center;margin-top:10px;" id="adduser"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>通知用户</td>
                                        <td>
                                            <?php if(is_array($userlist)): $i = 0; $__LIST__ = $userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="display:inline-block;margin-right:10px;width:100px;">
                                                <img src="<?php echo ($vo["wx_img"]); ?>" alt="" style="width:100px;height:100px;">
                                                <span style="display:block;font-size:12px;line-height:30px;     white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo ($vo["wxname"]); ?></span>
                                                <div style="text-align:center;margin-top:10px;width:80px;height:35px;line-height:35px;border:1px solid #367fa9" onclick="deluser('<?php echo ($vo["id"]); ?>')">删除</div>
                                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td><input type="hidden" name="inc_type" value="<?php echo ($inc_type); ?>"></td>

                                        </td>
                                    </tr>
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
function adsubmit() {
    $('#handlepost').submit();
}
$(document).ready(function () {
    get_province();
});
function goset(obj) {
    window.location.href = $(obj).attr('data-url');
}
function checkuser() {
    var user = $("#user").val();
    if (user != '') {
        $.ajax({
            url: "<?php echo U('System/checkuser');?>",
            type: 'post',
            data: {user: user},
            success: function (res) {
                if (res.status == 200) {
                    var html = '<img style="width:100px;height:100px;" src="' + res.data.wx_img + '">' +
                        '<span style="display:block;font-size:8px;">' + res.data.wxname + '</span>' +
                        '<span style="display:block;width:100px;margin:auto;" class="btn btn-primary" data-id="' + res.data.id + '" id="ajaxadd" >添加</span>'
                    $("#adduser").html(html);
                }
            }
        })
    }
}
$(document).on('click', '#ajaxadd', function () {
    var id = $("#ajaxadd").attr('data-id');
    $.ajax({
        url: "<?php echo U('System/adduser');?>",
        type: 'post',
        data: {user_id: id},
        success: function (res) {
            if (res.status == 1) {
                alert('添加成功');
                window.location.href = window.location.href ;
            }
        }
    });
})
function deluser(id){
     $.ajax({
        url: "<?php echo U('System/deluser');?>",
        type: 'post',
        data: {user_id: id},
        success: function (res) {
            if (res.status == 200) {
                alert('删除成功');
            }
        }
    });
}
</script></body></html>