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
 

<style type="text/css">
	#spec_item_table .table-bordered tr td input{display:initial;}
</style>
<div class="wrapper"> 
  <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> 商品规格</h3>
        </div>
        <form action="<?php echo U('Goods/batchAddSpecItem');?>" id="search-form2" class="navbar-form form-inline" method="post" >        
        <div class="panel-body">
          <div class="navbar navbar-default">
               <div class="form-group"> 
                  <select name="cat_id1" id="cat_id1" onchange="get_category(this.value,'cat_id2','0');"  class="form-control">
                    <option value="">所有分类</option>
                        <?php if(is_array($cat_list)): foreach($cat_list as $k=>$v): ?><option value="<?php echo ($v['id']); ?>"><?php echo ($v['name']); ?></option><?php endforeach; endif; ?>
                   </select> 
                  <select name="cat_id2" id="cat_id2" onchange="get_category(this.value,'cat_id3','0');" class="form-control" style="width:250px;">
                    <option value="0">请选择商品分类</option>
                  </select>
                  <select name="cat_id3" id="cat_id3" class="form-control" style="width:250px;">
                    <option value="0">请选择商品分类</option>
                  </select>	                     
 				</div>
                <button type="button" id="button-filter2" class="btn btn-primary pull-right">
                 <i class="fa fa-plus"></i> 添加规格
                </button>
          </div>
          <div id="ajax_return"></div>
          </form>
          
        </div>
      </div>
    </div>
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
<!-- /.content-wrapper --> 
<script>
    $(document).ready(function(){		
	 
			 
    });

/**
* ajax 请求加载下面列表
*/
function ajax_get_data(spec_id){
		 $.ajax({
                type : "GET",
                url:"/index.php?m=Seller&c=goods&a=ajaxSpecList&cat_id3="+$("#cat_id1").val()+"&spec_id="+spec_id,//+tab,                 
                success: function(data){
                    $("#ajax_return").html('');
                    $("#ajax_return").append(data);
                }
            });	
} 		 	
	 
// 商品类型切换时 ajax 调用  返回不同的属性输入框
$("#cat_id2").change(function(){
	ajax_get_data(0);
});
/**
* 添加一个规格项
*/ 
$(document).on("click","#button-filter2",function(){

		if($('#spec_item_table > tbody').length == 0) {
			var msg = '需要平台在对应的分类绑定规格名, 卖家才可以添加规格值.';
			layer.msg(msg, {
				  icon: 2,   // 成功图标
				  time: 3000 //2秒关闭（如果不配置，默认是3秒）
				});						
			return false;
		}

		var str = '<tr>'+
						'<td class="text-left">新增</td>'+
						'<td class="text-right">'+
						'<input type="text" class="form-control input-sm"  name="item[]" style="width:300px;"/>'+
						'<span style="color:#F00; display:none;">请填写内容</span>'+
						'</td>'+
						'<td class="text-right">'+
					    '<a href="javascript:void(0);"  class="btn btn-danger delItem2"><i class="fa fa-trash-o"></i></a>'+
						'</td>'+
					'</tr>';
		$('#spec_item_table > tbody').append(str);
					
});
	
/**
* 删除一个已有的规格项
*/
$(document).on("click",".delItem",function(){
	if(!confirm('你确定要删除吗?'))
	    return false;
	
	 var spec_item_id = $(this).data('spec_item_id');
     var del = $(this);  // 先把当前对象保存起来
	 $.ajax({
			type : "GET",
//            data:{spec_id:spec_id, spec_item:spec_item},
			dataType : 'json',			
			url:"/index.php?m=Seller&c=goods&a=delSpecItem&spec_item_id="+spec_item_id,//+tab,                 
			success: function(data){
			   if(data.status < 0)
			   {
				   layer.alert(data.msg, {icon: 2}); 
			   }else{
					del.parent().parent().remove();
			   }				
			}
		});		
});
/**
* 删除一个 未保存的规格项
*/
$(document).on("click",".delItem2",function(){
	$(this).parent().parent().remove();
});
</script> 
</body>
</html>