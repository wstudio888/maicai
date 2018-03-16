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
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> <?php echo ($plugin["name"]); ?> <small></small> </h1>

  </section>
  
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo ($plugin["name"]); ?>配送列表
              <a href="<?php echo U('Plugin/shipping_list_edit',array('id'=>$list[shipping_area_id],'type'=>$plugin['type'],'code'=>$plugin['code']));?>"><button type="button" class="btn btn-info">
                  <i class="ace-icon fa fa-plus bigger-110"></i>
              </button></a>
              <label for="desc">物流描述：</label> <span id="desc_span"><?php echo ($shipping_info["desc"]); ?></span><input onblur="change_desc('<?php echo ($shipping_info["code"]); ?>')" type="text" value="<?php echo ($shipping_info["desc"]); ?>" id="desc" style="width: 80%;display: none">
          </h3>
        </div>
        <div class="panel-body">
          <div id="ajax_return">
              <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                      <thead>
                      <tr>
                          <td style="width: 1px;" class="text-center">
                              <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                          </td>
                          <td class="text-center">
                              配送区名称
                          </td>
                          <td class="text-center">
                              配送区域
                          </td>
                          <td class="text-center">操作</td>
                      </tr>
                      </thead>
                      <tbody>

                      <?php if(is_array($shipping_list)): $i = 0; $__LIST__ = $shipping_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                              <td class="text-center">
                                  <input type="checkbox" name="selected[]" value="<?php echo ($list["shipping_area_id"]); ?>">
                              </td>
                              <td class="text-center"><?php echo ($list["shipping_area_name"]); ?></td>
                              <td class="text-center"><?php echo ($list["region_list"]); ?>
                                  <?php if($list['is_default'] == 1): ?>全国其他地区<?php endif; ?>
                              </td>
                              <td class="text-center">
                                  <a href="<?php echo U('Plugin/shipping_list_edit',array('id'=>$list[shipping_area_id],'type'=>$plugin['type'],'code'=>$plugin['code'],'edit'=>1,'default'=>$list['is_default']));?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>

                                  <?php if($list['is_default'] != 1): ?><a href="javascript:void(0);" data-href="<?php echo U('Plugin/del_area',array('id'=>$list[shipping_area_id],'type'=>$plugin['type'],'code'=>$plugin['code']));?>" onclick="del('<?php echo ($list[shipping_area_id]); ?>',this)" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a><?php endif; ?>
                              </td>
                          </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                      </tbody>
                  </table>
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
        // 删除操作
        function del(id,t)
        {
            if(!confirm('确定要删除吗?'))
                return false;

            location.href = $(t).data('href');
        }
        function change_desc(code){
            var desc = $('#desc').val();
            $.post("<?php echo U('Plugin/shipping_desc');?>",{code:code,desc:desc},function(data){
                data = $.parseJSON(data);
                $('#desc_span').show();
                $('#desc').hide();
                if(data.status == 1){
                    $('#desc_span').text(desc);
                }else{
                    layer.alert('修改失败', {icon: 2});  // alert('修改失败');
                }
            })
        }

    function show_input(t){
        $(t).hide();
        $('#desc').show();
    }
</script> 
</body>
</html>