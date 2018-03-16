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

  <section class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i>汇款记录</h3>
        </div>
        <div class="panel-body">    
		<div class="navbar navbar-default">                    
                <form id="search-form2" class="navbar-form form-inline"  method="get" action="<?php echo U('store_remittance');?>">
                <div class="form-group">                
                  <label for="input-order-id" class="control-label">店铺ID:</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="user_id" placeholder="店铺id" value="<?php echo ($_REQUEST['store_id']); ?>" name="store_id" />
                  </div>
                  
                  <label for="input-order-id" class="control-label">收款账号:</label>                
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-order-id" placeholder="收款账号" value="<?php echo ($_REQUEST['account_bank']); ?>" name="account_bank" />                    
                  </div>
                  <label for="input-order-id" class="control-label">收款账户名:</label>                
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-order-id" placeholder="收款账户名" value="<?php echo ($_REQUEST['account_name']); ?>" name="account_name" />                    
                  </div>
                  
                   <div class="input-group margin">                    
                    <div class="input-group-addon">
                        时间<i class="fa fa-calendar"></i>
                    </div>
                       <input type="text" id="start_time" value="<?php echo ($create_time); ?>" name="create_time" class="form-control pull-right" style="width: 200px">
                  </div>                  
                </div>
                <div class="form-group">    
                    <button class="btn btn-primary" id="button-filter search-order" type="submit"><i class="fa fa-search"></i> 筛选</button>    
                </div>                                 
                </form>    
          </div>                        
          <div id="ajax_return">                  
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="sorting text-left">记录ID</th>
                                <th class="sorting text-left">店铺ID</th>
                                <th class="sorting text-left">店铺名称</th>
                                <th class="sorting text-left">银行名称</th>
                                <th class="sorting text-left">银行账号</th>
                                <th class="sorting text-left">银行账户</th>
                                <th class="sorting text-left">申请金额</th>
                                <th class="sorting text-left">状态</th>
                                <th class="sorting text-left">操作时间</th>
                                <th class="sorting text-left">备注</th>
                                <!--<th class="sorting text-left">申请提现记录</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                                    <td class="text-left">
                                        <a href="<?php echo U('editStoreWithdrawals',array('id'=>$v[withdrawals_id]));?>"><?php echo ($v["id"]); ?></a>                                        
                                    </td>
                                    <td class="text-left">
                                        <?php echo ($v["store_id"]); ?>
                                    </td>
                                    <td class="text-left">
										<a href="<?php echo U('Store/store_info',array('store_id'=>$v[store_id]));?>"><?php echo ($v["store_name"]); ?></a>
                                    </td>
                                    <td class="text-left"><?php echo ($v["bank_name"]); ?></td>
                                    <td class="text-left"><?php echo ($v["account_bank"]); ?></td>
                                    <td class="text-left"><?php echo ($v["account_name"]); ?></td>
                                    <td class="text-left"><?php echo ($v["money"]); ?></td>
                                    <td class="text-left">支付成功</td>                                    
                                    <td class="text-left"><?php echo (date("Y-m-d H:i",$v["create_time"])); ?></td>                                    
                                    <td class="text-left"><?php echo ($v["remark"]); ?></td>
                                    <!--<td class="text-left"><a href="<?php echo U('Admin/Distribut/editWithdrawals',array('id'=>$v[withdrawals_id]));?>">查看</a></td>-->
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                
                <div class="row">
                    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo ($show); ?></div>
                </div>
          
          </div>
        </div>
      </div>
    </div>
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
<!-- /.content-wrapper --> 
 <script>
 
$(document).ready(function() {
	$('#start_time').daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: false,
		showDropdowns: true,
		minDate:'2016-01-01',
		maxDate:'2030-01-01',
		startDate:'2016-01-01',
	    locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
	});
});
</script>
</body>
</html>