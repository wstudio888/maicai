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
                                 <a class="btn btn-default" href="<?php echo U('Store/reopen_list');?>" >签约申请</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="btn btn-default" href="<?php echo U('Store/apply_class_list');?>">经营类目申请</a>&nbsp;&nbsp;&nbsp;&nbsp;
				            </div>
				          </div>
				       </div>
	    		 	</nav>
			        <div class="row navbar-collapse">
                       	<div class="callout callout-inro">
							<p>1. 删除店铺的经营类目会造成相应商品下架，请谨慎操作</p>
					        <p>2. 所有修改即时生效</p>
			            </div>
			      	</div>    		
	             </div>
				<div class="box-body">
                  <button class="btn btn-default btn-block"><p class="pull-left">店铺名称：<?php echo ($store["store_name"]); ?></p></button>
                </div>
	             <div class="box-body">
	           	 <div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable">
		                 <thead>
		                   <tr role="row">
                               <th>分类1</th>
                               <th>分类2</th>
			                   <th>分类3</th>	
			                   <th>分佣比例</th>	                   
		                  	   <th>操作</th>
		                   </tr>
		                 </thead>
						<tbody>
                          <?php if(is_array($bind_class_list)): foreach($bind_class_list as $k=>$vo): ?><tr role="row">    
                             <td><?php echo ($vo["class_1_name"]); ?></td>
                             <td><?php echo ($vo["class_2_name"]); ?></td>
		                     <td><?php echo ($vo["class_3_name"]); ?></td>	                    
		                     <td><?php echo ($vo["commis_rate"]); ?></td>
		                     <td class="text-center">
		                      	<a class="btn btn-danger" onclick="delfunc(this)" data-url="<?php echo U('Store/apply_class_save');?>" data-id="<?php echo ($vo["bid"]); ?>">删除</a>
				     		</td>
		                   </tr><?php endforeach; endif; ?>
		                   </tbody>
		                 <tfoot>
		                 </tfoot>
		               </table>
	               </div>
	          </div>
              <div class="row">
              	    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>		
              </div>
	          </div>
	          <div class="box-body">
                  <button class="btn btn-default btn-block"><p class="pull-left">添加经营类目</p></button>
               </div>
               <div class="box-body">
               		<form method="post" id="class_info">
               		<table class="table  table-bordered">
               			<tr>
               				<td class="col-md-2">选择分类:</td>
               				<td>
               					<div class="col-md-3">
				  	            <select name="class_1" id="cat_id" onchange="get_category(this.value,'cat_id_2','0');" class="form-control">
					               <option value="0">选择分类</option>                                      
					                    <?php if(is_array($cat_list)): foreach($cat_list as $k=>$v): ?><option value="<?php echo ($v['id']); ?>" <?php if($v['id'] == $level_cat['1']): ?>selected="selected"<?php endif; ?> >
					                      		<?php echo ($v['name']); ?>
					                      </option><?php endforeach; endif; ?>
					             </select>
               					</div>
               					<div class="col-md-3">
	             	             <select name="class_2" id="cat_id_2" onchange="get_category(this.value,'cat_id_3','0');" class="form-control">
					               	<option value="0">选择分类</option>
					             </select>  
               					</div>
               					 <div class="col-md-3">
						             <select name="class_3" id="cat_id_3" onchange="push_commis_rate(this)" class="form-control">
						               <option value="0">选择分类</option>
						             </select> 
               					</div>
               				</td>
               				<td></td>
               			</tr>
               			<tr>
               				<td>分佣比例：</td>
            				<td><div class="col-md-3 input-group">
	                       			<input type="text" name="commis_rate" placeholder="5" class="form-control">
	                       			<span class="input-group-addon">%</span>
	                       		</div>
				            </td>
               				<td class="col-md-2">必须为0-100的整数</td>
               			</tr>
               			<tr>
							<td colspan="3" class="text-center">
								<input type="hidden" name="store_id" value="<?php echo ($store["store_id"]); ?>">
								<input class="btn btn-primary" type="button" onclick="actsubmit()" value="确认添加">
							</td>
                        </tr>
               		</table>
               		</form>
               </div>
	        </div>
       	</div>
       </div>
   </section>
<script>
function push_commis_rate(obj){
	//$('input[name="commis_rate"]').val($(obj).val());
}

function actsubmit(){
	$('#class_info').submit();
}
</script>
</div>
</body>
</html>