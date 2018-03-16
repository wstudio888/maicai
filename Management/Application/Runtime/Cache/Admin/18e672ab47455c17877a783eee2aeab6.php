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
                                 <a class="btn btn-info" href="<?php echo U('Store/store_list');?>">店铺列表</a>&nbsp;&nbsp;&nbsp;&nbsp;                                            
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
				            	<label class="control-label" for="input-order-id">所属等级</label>
				            	 <select name="grade_id" class="form-control">
				            	 	  <option>选择等级</option>
                                      <?php if(is_array($store_grade)): $k = 0; $__LIST__ = $store_grade;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><option value="<?php echo ($k); ?>" <?php if($pid == $k): ?>selected<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>                  
                                 </select>   
				            </div>
				           <div class="form-group">
				              	<input type="text" name="seller_name" class="form-control" placeholder="请输入店主名称">
				            </div>
				            <div class="form-group">
				            	<label class="control-label" for="input-order-id">店铺状态</label>
				            	 <select name="store_state" class="form-control">
				            	 	   <option>选择状态</option>
									   <option value="1">开启</option>
				            	 	   <option value="2">关闭</option>
                                       <option value="3">即将到期</option>
                                       <option value="4">已到期</option>
                                 </select>   
				            </div>
				           	<div class="form-group">
				            	<label class="control-label" for="input-order-id">店铺类别</label>
				            	 <select name="sc_id" class="form-control">
				            	 	  <option>选择类别</option>
                                      <?php if(is_array($store_class)): $k = 0; $__LIST__ = $store_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($k % 2 );++$k;?><option value="<?php echo ($k); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>                  
                                 </select>   
				            </div>
				            <div class="form-group">
				              	<input type="text" name="store_name" class="form-control" placeholder="请输店铺名称">
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
                               <th>店铺名称</th>
                               
			                   <th>卖家账号</th>	
			                   <th>所属等级</th>	                   
			                   <th>店铺类别</th>
                               <th>进驻日期</th>
			                   <th>状态</th>
		                  	   <th>推荐</th>
		                  	   <th>操作</th>
		                   </tr>
		                 </thead>
						<tbody>
                          <?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row">    
                             <td><?php echo ($vo["store_name"]); ?></td>
                             
		                     <td><?php echo ($vo["seller_name"]); ?></td>	                    
		                     <td><?php echo ($store_grade[$vo[grade_id]]); ?></td>
		                     <td><?php echo ($store_class[$vo[sc_id]]); ?></td>                                     
		                     <td><?php echo (date('Y-m-d',$vo["store_time"])); ?></td>
		                     <td><?php if($vo[store_state] == 1): ?>开启<?php else: ?>关闭<?php endif; ?></td>
		                     <td><img width="20" height="20" src="/Public/images/<?php if($vo[store_recommend] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('store','store_id','<?php echo ($vo["store_id"]); ?>','store_recommend',this)"/></td>
		                     <td class="text-center">
		                      	<a class="btn btn-info" href="<?php echo U('Store/store_info',array('store_id'=>$vo['store_id']));?>">查看</a>
		                      	<a class="btn btn-info" href="<?php echo U('Store/store_info_edit',array('store_id'=>$vo['store_id']));?>">编辑</a>
		                      	<a class="btn btn-info" href="<?php echo U('Store/store_class_info',array('store_id'=>$vo['store_id']));?>">经营类目</a>
								<a class="btn btn-info" href="<?php echo U('Store/store_article_list',array('store_id'=>$vo['store_id']));?>">活动</a>
		                      	<a class="btn btn-danger" onclick="delfunc(this)" data-url="<?php echo U('Store/store_del');?>" data-id="<?php echo ($vo["store_id"]); ?>">删除</a>
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
</div>
</body>
</html>