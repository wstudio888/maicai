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
 

<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">
	              <nav class="navbar navbar-default">	     
					<div class="collapse navbar-collapse">
		                <div class="navbar-form  margin">
		                      <a href="<?php echo U('Store/bind_class_list');?>" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="编辑">经营类目</a>
		                      <a href="<?php echo U('Store/store_info');?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑">店铺信息</a>
		                      <a href="javascript:;" onclick="get_goods_class()" data-toggle="tooltip" title="" class="btn btn-success pull-right" data-original-title="返回">申请新的经营类目</a>
		               </div>
		            </div>
		           </nav>             
	             </div><!-- /.box-header -->
	           <div class="box-body">
	           <div class="row">
	            <div class="col-sm-12">
	              <table id="list-table" class="table table-bordered table-striped">
	                 <thead>
	                   <tr role="row">
		                   <th>经营类目</th>
		                   <th>分佣比例</th>
		                   <th>状态</th>
		                   <th>操作</th>
	                   </tr>
	                 </thead>
					<tbody>
					  <?php if(is_array($bind_class_list)): foreach($bind_class_list as $k=>$vo): ?><tr role="row" align="left">
                         <td>	
                         		<span class="col-xs-3"><?php echo ($vo["class_1_name"]); ?></span>
                         		<span class="col-xs-2"><i class="fa fa-angle-double-right"></i><?php echo ($vo["class_2_name"]); ?></span>
                         		<span class="col-xs-2"><i class="fa fa-angle-double-right"></i><?php echo ($vo["class_3_name"]); ?></span>
                         </td>
                         <td><?php echo ($vo["commis_rate"]); ?></td>
	                 	 <td><?php if($vo[state] == 1): ?>已审核<?php else: ?>审核中<?php endif; ?></td>
	                     <td>
	                       <?php if($vo[state] == 0): ?><a class="btn btn-danger" href="javascript:void(0)" data-url="<?php echo U('Store/bind_class_del');?>" data-id="<?php echo ($vo["bid"]); ?>" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a><?php endif; ?>
						</td>
	                   </tr><?php endforeach; endif; ?>
	                   </tbody>
	               </table></div>
	               </div>
	             </div>
	           </div>
       		</div>
       </div>
</div>
<script type="text/javascript">
function delfun(obj){
	if(confirm('确认删除')){		
		$.ajax({
			type : 'post',
			url : $(obj).attr('data-url'),
			data : {act:'del',bid:$(obj).attr('data-id')},
			dataType : 'json',
			success : function(data){
				if(data.stat=='ok'){
					$(obj).parent().parent().remove();
				}else{
					layer.alert(data, {icon: 2});  //alert(data);
				}
			}
		})
	}
	return false;
}

function get_goods_class(){
    layer.open({
        type: 2,
        title: '申请经营新的类目',
        shadeClose: true,
        shade: 0.2,
        area: ['660px', '300px'],
        content: "<?php echo U('Store/get_bind_class');?>", 
    });
}

//回调函数
function call_back(msg){
	if(msg>0){
		layer.msg('操作成功', {icon: 1});
		layer.closeAll('iframe');
		window.location.reload();
	}else{
		layer.msg('操作失败', {icon: 3});
		layer.closeAll('iframe');
	}
}
</script>
</body>
</html>