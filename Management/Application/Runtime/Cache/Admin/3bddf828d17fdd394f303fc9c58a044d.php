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

    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加优惠券</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form action="" method="post">              
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">优惠券名称：</td>
                                    <td class="col-sm-4">
                                        <input type="text" value="<?php echo ($coupon["name"]); ?>" class="form-control" id="name" name="name" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">请填写优惠券名称
                                    </td>
                                </tr>  
                                <tr>
                                    <td>优惠券面额：</td>
                                    <td >
                         				<input type="text" value="<?php echo ($coupon["money"]); ?>" class="form-control" id="money" name="money">
                                    </td>
                                    <td class="col-sm-4">优惠券可抵扣金额</td>
                                </tr>  
                                <tr>
                                    <td>消费金额：</td>
                                    <td>
                      					<input type="text" value="<?php echo ($coupon["condition"]); ?>" class="form-control active" id="condition" name="condition">
                                    </td>
                                    <td class="col-sm-4">可使用最低消费金额</td>
                                </tr>
                                <tr>
			                        <td>发放类型:</td>
			                        <td id="order-status">
			                            <input name="type" type="radio" value="1" <?php if($coupon['type'] == 1): ?>checked<?php endif; ?> >按订单发放
			                            <input name="type" type="radio" value="2" <?php if($coupon['type'] == 2): ?>checked<?php endif; ?> >注册
			                            <input name="type" type="radio" value="3" <?php if($coupon['type'] == 3): ?>checked<?php endif; ?> >邀请
			                            <input name="type" type="radio" value="4" <?php if($coupon['type'] == 4): ?>checked<?php endif; ?> >按用户
			                        </td>
			                    </tr>   
			                    <tr id="order_limit" <?php if($coupon['type'] != 1): ?>style="display: none"<?php endif; ?>>
			                        <td>订单下限:</td>
			                        <td>
			                              <input type="text" value="<?php echo ($coupon["min_order"]); ?>" class="form-control active" id="min_order" name="min_order">
			                        </td>
			                         <td class="col-sm-4">当订单满多少金额时才发放</td>
			                    </tr>
			
			                    <tr>
			                        <td>发放开始日期:</td>
			                        <td>
			                            <div class="input-prepend input-group">
			                                <span class="add-on input-group-addon">
			                                      <i class="glyphicon glyphicon-calendar fa fa-calendar">  </i>
			                                </span>
			                                <input type="text" value="<?php echo (date('Y-m-d',$coupon["send_start_time"])); ?>" class="form-control" id="send_start_time" name="send_start_time">
			                            </div>
			                        </td>
			                        <td class="col-sm-4"></td>
			                    </tr>
			
			                    <tr>
			                        <td>发放结束日期:</td>
			                        <td>
			                            <div class="input-prepend input-group">
			                                <span class="add-on input-group-addon">
			                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"> </i>
			                                </span>
			                                <input type="text" value="<?php echo (date('Y-m-d',$coupon["send_end_time"])); ?>" class="form-control" id="send_end_time" name="send_end_time">
			                            </div>
			                        </td>
			                        <td class="col-sm-4"></td>
			                    </tr>
			
			                    <tr>
			                        <td>有效截止日期:</td>
			                        <td>
			                            <div class="input-prepend input-group">
			                                <span class="add-on input-group-addon">
			                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
			                                </span>
			                                <input type="text" value="<?php echo (date('Y-m-d',$coupon["use_end_time"])); ?>" class="form-control" id="use_end_time" name="use_end_time">
			                            </div>
			                        </td>
			                        <td class="col-sm-4"></td>
			                    </tr>                              
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><input type="hidden" name="act" value="<?php echo ($act); ?>">
                                		<input type="hidden" name="link_id" value="<?php echo ($info["link_id"]); ?>">
                                	</td>
                                	<td class="col-sm-4"></td>
                                	<td class="text-right"><input class="btn btn-primary" type="submit" value="保存"></td>
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
$('input[type="radio"]').click(function(){
    if($(this).val() == 1){
        $('#order_limit').show();
    }else{
        $('#order_limit').hide();
        $('#order_limit').find('input').val(0);
    }
})
    $(function(){
        data_pick('send_start_time');
        data_pick('send_end_time');
        data_pick('use_start_time');
        data_pick('use_end_time');

    })
    function data_pick(id){
        var myDate = new Date();
        $('#'+id).daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate:myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDate(),
            maxDate:'2030-01-01',
            format: 'YYYY-MM-DD',
            locale : {
                applyLabel : '确定',
                cancelLabel : '取消',
                fromLabel : '起始时间',
                toLabel : '结束时间',
                customRangeLabel : '自定义',
                daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月' ],
                firstDay : 1
            }
        });
    }
</script>
</body>
</html>