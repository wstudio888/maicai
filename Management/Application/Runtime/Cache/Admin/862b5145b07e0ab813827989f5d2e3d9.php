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
	               <nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <form class="navbar-form form-inline" action="<?php echo U('Promotion/prom_order_list');?>" method="post">
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="活动名称">
				            </div>
				            <button type="submit" class="btn btn-default">提交</button>	          
				          </form>		
				      	</div>
	    			</nav>                
	             </div>	    
	             <!-- /.box-header -->
	             <div class="box-body">	             
	           		<div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable">
		                 <thead>
		                   <tr role="row">
			                   <th class="sorting" tabindex="0">活动名称</th>
			                   <th class="sorting" tabindex="0">活动类型</th>
			                   <th class="sorting" tabindex="0">适用范围</th>
			                   <th class="sorting" tabindex="0">开始时间</th>
			                   <th class="sorting" tabindex="0">结束时间</th>
			                   <th>推荐</th>
			                   <th>状态</th>
			                   <th class="sorting" tabindex="0">操作</th>
		                   </tr>
		                 </thead>
						<tbody>
						  <?php if(is_array($prom_list)): foreach($prom_list as $k=>$vo): ?><tr role="row" align="center">
		                     <td><?php echo (getSubstr($vo["name"],0,30)); ?></td>
		                     <td><?php echo ($parse_type[$vo[type]]); ?></td>
		                     <td><?php echo ($vo["group_name"]); ?></td>
		                     <td><?php echo (date('Y-m-d',$vo["start_time"])); ?></td>
							 <td><?php echo (date('Y-m-d',$vo["end_time"])); ?></td>
							 <th><img width="20" height="20" src="/Public/images/<?php if($vo[recommend] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('prom_order','id','<?php echo ($vo["id"]); ?>','recommend',this)"/></th>
							 <td><?php echo ($vo["state"]); ?></td>
							 <td>
		                      <?php if($vo['status'] == 1): ?><a class="btn btn-warning" href="javascript:;" onclick="changeStatus(0,'<?php echo ($vo["id"]); ?>','prom_order')">取消</a><?php endif; ?>
		                      <a class="btn btn-danger" href="<?php echo U('Promotion/prom_order_del',array('id'=>$vo['id']));?>" data-url="" data-id="<?php echo ($vo["id"]); ?>"><i class="fa fa-trash-o"></i></a>
							</td>
		                   </tr><?php endforeach; endif; ?>
		                   </tbody>
		               </table>
	               </div>
	          </div>
              <div class="row">
              	    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>		
              </div>
	          </div><!-- /.box-body -->
	        </div><!-- /.box -->
       	</div>
       </div>
   </section>
   
<script>
function changeStatus(status,id,tab){
	if(status>1){
    	layer.confirm('确认删除？', {btn: ['确定','取消']}, function(){
	  			$.ajax({
	  				type : 'GET',
	  				url : "<?php echo U('Promotion/activity_handle');?>",
	  				data : {'id':id,'tab':tab,'status':status},
	  				dataType :'JSON',
	  				success : function(res){
	  					if(res == 1){
	  						layer.msg('操作成功', {icon: 1});
	  						window.location.reload();
	  					}else{
	  						layer.msg('操作失败', {icon: 2,time: 2000});
	  					}
	  					layer.closeAll();
	  				}
	  			});
  			}, function(index){
  				layer.close(index);
  				return false;// 取消
  			});
	}else{
		$.ajax({
			type : 'GET',
			url : "<?php echo U('Promotion/activity_handle');?>",
			data : {'id':id,'tab':tab,'status':status},
			dataType :'JSON',
			success : function(res){
				if(res == 1){
					layer.msg('操作成功', {icon: 1});
					window.location.reload();
				}else{
					layer.msg('操作失败', {icon: 2,time: 2000});
				}
				layer.closeAll();
			}
		});
	}
}
</script>
</div>
</body>
</html>