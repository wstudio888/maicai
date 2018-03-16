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
	    				<div class="navbar-form form-inline">
				            <div class="form-group">
				            	<p class="text-success margin blod">店铺:</p>
				            </div>
				             <div class="form-group">
                         <a class="btn btn-info" href="<?php echo U('Store/store_add',array('is_own_shop'=>0));?>" >开店申请</a>&nbsp;&nbsp;&nbsp;&nbsp;
				            </div>
				          </div>
				       </div>
	    		 	</nav>	
	               	
	             </div>
	             <div class="box-body">
	           	 <div class="row">
	            	<div class="col-sm-12">
                    <table id="list-table" class="table table-bordered table-striped dataTable">
                        <thead>
                        <tr role="row">
                            <th>店铺名称</th>
                            <th>店主买家昵称</th>
                            <th>店铺类型</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                            <th>店铺地址</th>
                            <th>描述</th>
                            <th>申请时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                          <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row">    
                             <td><?php echo ($vo["sellername"]); ?></td>
		                     <td><?php echo ($vo["nick_name"]); ?></td>
		                     <th><?php echo ($vo["sellerxm"]); ?></th>
		                     <td><?php echo ($vo["sellerccontactname"]); ?></td>
		                     <td><?php echo ($vo["sellerccontactphone"]); ?></td>       
		                     <th><?php echo ($vo["selleraddress"]); ?></th>    
		                     <td><?php echo ($vo["sellerdesc"]); ?></td>                                     
		                     <td><?php echo (date('Y-m-d',$vo["add_time"])); ?></td>
		                     <td>
		                     	<?php if($vo[status] == 0): ?>新申请<?php elseif($vo[status] == 2): ?>未通过<?php else: ?>开店成功<?php endif; ?>
		                     </td>
		                     <td>
		                     <?php if($vo[status] == 0): ?><img width="20" height="20" src="/Public/images/yes.png" onclick="change('<?php echo ($vo["id"]); ?>',1)"/>
		                     
		                     <img width="20" height="20" src="/Public/images/cancel.png" onclick="change('<?php echo ($vo["id"]); ?>',2)"/><?php endif; ?>
		                      <a class="btn btn-danger" href="<?php echo U('Store/apply_del',array('del_id'=>$vo['id']));?>" ><i class="fa fa-trash-o"></i></a>
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