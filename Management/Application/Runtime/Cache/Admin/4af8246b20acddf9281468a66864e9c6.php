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
                                 <a class="btn btn-default" href="<?php echo U('Store/store_list');?>">管理</a>&nbsp;&nbsp;&nbsp;&nbsp;                                            
                                 <a class="btn btn-default" href="<?php echo U('Store/apply_list');?>" >开店申请</a>&nbsp;&nbsp;&nbsp;&nbsp;                                            
                                 <a class="btn btn-info" href="<?php echo U('Store/reopen_list');?>" >签约申请</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="btn btn-default" href="<?php echo U('Store/apply_class_list');?>">经营类目申请</a>&nbsp;&nbsp;&nbsp;&nbsp;
				            </div>			          
				            <div class="form-group pull-right">
					            <a href="<?php echo U('Store/store_add',array('is_own_shop'=>0));?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> 新增店铺</a>
				            </div>	
				          </div>
				       </div>
	    		 	</nav>	
	               	<nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <form class="navbar-form form-inline" action="<?php echo U('Store/store_list');?>" method="post">
				           <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="请输入店主名称">
				            </div>
				            <div class="form-group">
				            	<label class="control-label" for="input-order-id">店铺状态</label>
				            	 <select name="pid" class="form-control">
                                       <option value="1">开启</option>
                                       <option value="0">关闭</option>
                                       <option value="2">即将到期</option>
                                       <option value="3">已到期</option>
                                 </select>   
				            </div>
				           	<div class="form-group">
				            	<label class="control-label" for="input-order-id">店铺类别</label>
				            	 <select name="pid" class="form-control">
                                      <?php if(is_array($store_class)): $k = 0; $__LIST__ = $store_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><option value="<?php echo ($k); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>       
                                 </select>   
				            </div>
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="请输店铺名称">
				            </div>
				            <button type="submit" class="btn btn-primary">查询</button>	          
				          </form>
				      	</div>
	    			</nav>
	             </div>
	             <div class="box-body">
	           	 <div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable">
		                 <thead>
		                   <tr role="row">
                               <th>店铺/ID</th>
                               <th>申请时间</th>
			                   <th>收费标准(元/年)</th>	
			                   <th>续签时长(年)</th>
			                   <th>付款金额(年)</th>	
			                   <th>续签起止有效期</th>	
			                   <th>状态</th>	
			                   <th>付款凭证</th>
			                   <th>付款备注</th>			                   
		                  	   <th>操作</th>
		                   </tr>
		                 </thead>
						<tbody>
                          <?php if(is_array($bind_class)): foreach($bind_class as $k=>$vo): ?><tr role="row">    
                             <td><?php echo ($vo["class_1_name"]); ?></td>
                             <td><?php echo ($vo["store_name"]); ?></td>
		                     <td><?php echo ($vo["seller_name"]); ?></td>	                    
		                     <td><?php echo ($vo["commis_rate"]); ?></td>
		                     <td><?php echo ($vo["class_1_name"]); ?></td>
                             <td><?php echo ($vo["store_name"]); ?></td>
		                     <td><?php echo ($vo["seller_name"]); ?></td>	                    
		                     <td><?php echo ($vo["commis_rate"]); ?></td>
		                     <td class="text-center">
		                      	<a class="btn btn-info" href="javascript:;">审核</a>
		                      	<a class="btn btn-danger" onclick="delfunc(this)" data-url="<?php echo U('Store/apply_class_del');?>" data-id="<?php echo ($vo["ad_id"]); ?>">删除</a>
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

</script>
</div>
</body>
</html>