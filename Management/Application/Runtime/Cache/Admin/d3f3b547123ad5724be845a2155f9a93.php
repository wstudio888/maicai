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
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">

	               	
	             </div>
	             <div class="box-body">
	           	 <div class="row">
	            	<div class="col-sm-12">
                    <table id="list-table" class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr role="row">
                            <th>快递员名称</th>
                            <th>联系电话</th>
                            <th>联系微信</th>
                            <th>银行卡号</th>
                            <th>银行名称</th>
                            <th>提现金额</th>
                            <th>申请提现时间</th>
                            <th>完成提现时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                          <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row">    
                             <td><?php echo ($vo["courier_name"]); ?></td>
		                     <td><?php echo ($vo["courier_phone"]); ?></td>
		                     <th><?php echo ($vo["courier_wx"]); ?></th>   
		                     <th><?php echo ($vo["card_no"]); ?></th> 
		                     <th><?php echo ($vo["bank_name"]); ?></th>
		                     <td><?php echo ($vo["money_x"]); ?></td>                                     
		                     <td><?php echo (date('Y-m-d h:i:s',$vo["time_now"])); ?></td> 
		                     <td>
		                     <?php if($vo['terminal_time'] == null): ?>未完成支付
		                     <?php elseif($vo['terminal_time'] != null): ?> 
								<?php echo (date('Y-m-d h:i:s',$vo["terminal_time"])); endif; ?>
		                     		                    					
		                     </td>
		                     <td>
		                    <?php if($vo['status_x'] == 1): ?><a href="<?php echo U('Courier/courier_money',['id' => $vo['id']]);?>">确认付款</a>
		                    <?php elseif($vo['status_x'] == 2): ?> 
		                     <a style="color:green;">已完成付款</a><?php endif; ?>

				     		</td>
		                   </tr><?php endforeach; endif; ?>
		                   </tbody>
		                 <tfoot>
		                 </tfoot>
		               </table>
	               </div>
	          </div>
              <div class="row">
              	    <div class="col-sm-6 text-left">
              	    	<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
              	    </div>
                    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>		
              </div>
	          </div>
	        </div>
       	</div>
       </div>
   </section>
<script>

//修改指定表的指定字段值 给商家使用的函数
function change(id,status)
{
		                                                
		$.ajax({
				url:"/index.php?m=Admin&c=Store&a=change&id="+id+"&status="+status,			
				success: function(data){									
					window.location.href="/index.php?m=Admin&c=Store&a=apply_list";        
				}
		});		
}
</script>
</div>
</body>
</html>