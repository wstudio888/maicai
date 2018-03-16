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
 

<style>
	 .ajax_bradnlist{height:318px; overflow:auto}	
	 .ajax_bradnlist ul li{float: left; display:inline-flex; display:-moz-inline-flex; display:-webkit-inline-box; width:20%; padding:6px 0; }
	 .table-bordered th:first-child{ width:8%}
   .ajax_bradnlist ul{clear:both;padding-top: 10px }
	 .table-bordered tbody tr td{ vertical-align:middle}
	 .form-control{ resize:vertical}
</style>
<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
                <a onclick="get_help(this)" data-url="http://www.tp-shop.cn/Doc/Indexbbc/article/id/1072/developer/user.html" class="btn btn-default" href="javascript:;"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 类型关联</h3>
                </div>
                <form method="post" id="addEditGoodsTypeForm" onsubmit="return checkgoodsTypeName();">                                    
                <div class="panel-body">
                
                    <!--商品类型-->                
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">商品类型</a></li>
                    </ul>
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>类型名称:</td>
                                    <td>
                                        <input type="text" value="<?php echo ($goodsType["name"]); ?>" name="name" class="form-control" style="width:300px;"/>
                                        <span id="err_name" style="color:#F00; display:none;">商品类型名称不能为空!!</span>
                                    </td>
                                </tr>  
                                
                                <!-- <tr>
                                    <td>所属分类:</td>
                                    <td>
                                      <div class="col-xs-3">
                                      <select name="cat_id1" id="cat_id1" onchange="get_category(this.value,'cat_id2','0');" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>                                      
                                             <?php if(is_array($cat_list)): foreach($cat_list as $k=>$v): ?><option value="<?php echo ($v['id']); ?>" <?php if($v['id'] == $goodsType[cat_id1]): ?>selected="selected"<?php endif; ?> >
                                               		<?php echo ($v['name']); ?>
                                               </option><?php endforeach; endif; ?>
                                      </select>
                                      </div>
                                      <div class="col-xs-3">
                                      <select name="cat_id2" id="cat_id2" onchange="get_category(this.value,'cat_id3','0');" class="form-control" style="margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                      </div>
                                      <div class="col-xs-3">                        
                                      <select name="cat_id3" id="cat_id3" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select> 
                                      </div>    
                                      <span id="err_cat_id" style="color:#F00; display:none;"></span>                                 
                                    </td>
                                </tr>                   -->          
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>  
                    <!--商品类型 end-->     
                    <!--关联规格-->                
                   <!--  <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">关联规格</a></li>
                    </ul>
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>                                                                   
                                <tr>
                                    <td>分类检索：</td>
                                    <td>
                                      <div class="col-xs-3">
                                      <select name="spec_cat_id1" id="spec_cat_id1" onchange="get_category(this.value,'spec_cat_id2','0');spec_scroll(this);" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>                                      
                                             <?php if(is_array($cat_list)): foreach($cat_list as $k=>$v): ?><option value="<?php echo ($v['id']); ?>">
                                               		<?php echo ($v['name']); ?>
                                               </option><?php endforeach; endif; ?>
                                      </select>
                                      </div>
                                      <div class="col-xs-3">
                                      <select name="spec_cat_id2" id="spec_cat_id2" onchange="get_category(this.value,'spec_cat_id3','0');" class="form-control" style="margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                      </div>
                                      <div class="col-xs-3">                        
                                      <select name="spec_cat_id3" id="spec_cat_id3" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select> 
                                      </div>    
                                      <span id="spec_cat_id" style="color:#F00; display:none;"></span>                                 
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    <div id="ajax_specList" class="ajax_bradnlist"></div> 
                                    </td>
                                </tr>                            
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>   -->
                    <!--关联规格 end-->                        
                   
                    <!--关联品牌-->                
                    <!-- <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">关联品牌</a></li>
                    </ul>
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>                                                                   
                                <tr>
                                    <td>分类检索：</td>
                                    <td>
                                      <div class="col-xs-3">
                                      <select name="brand_cat_id1" id="brand_cat_id1" onchange="get_category(this.value,'brand_cat_id2','0');brand_scroll(this);" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>                                      
                                             <?php if(is_array($cat_list)): foreach($cat_list as $k=>$v): ?><option value="<?php echo ($v['id']); ?>">
                                               		<?php echo ($v['name']); ?>
                                               </option><?php endforeach; endif; ?>
                                      </select>
                                      </div>
                                      <div class="col-xs-3">
                                      <select name="brand_cat_id2" id="brand_cat_id2" onchange="get_category(this.value,'brand_cat_id3','0');" class="form-control margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                      </div>
                                      <div class="col-xs-3">                        
                                      <select name="brand_cat_id3" id="brand_cat_id3" class="form-control" style="width:250px;margin-left:-15px;">
                                        <option value="0">请选择商品分类</option>
                                      </select> 
                                      </div>    
                                      <span id="brand_cat_id" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    <div id="ajax_brandList" class="ajax_bradnlist"></div>   
                                    </td>
                                </tr>                            
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>   -->
                    <!--关联品牌 end-->                        
                   
                    <!--添加属性-->                                    
                    <!-- <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered" id="attribute_table">
                                <tbody> 
                                    <tr>
                                       <th>排序</th>
                                       <th>属性名称</th>
                                       <th>属性可选值</th>
                                       <th>显示</th>
                                       <th>操作</th>                                       
                                    </tr>
                                    <?php if(is_array($attributeList)): foreach($attributeList as $k=>$v): ?><tr>
                                        <td>
                                            <input type="text" name="attr_id[]" value="<?php echo ($v['attr_id']); ?>"  class="form-control" style="display:none;"/>
                                            <input type="text" name="order[]" value="<?php echo ($v['order']); ?>"  class="form-control"/>
                                        </td>
                                        <td><input type="text" name="attr_name[]" value="<?php echo ($v['attr_name']); ?>"  class="form-control"/></td>
                                        <td>
                                        <textarea rows="1" name="attr_values[]" cols="36" class="form-control"><?php echo ($v['attr_values']); ?></textarea>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="attr_index[]" value="1" <?php if($v['attr_index'] == 1): ?>checked="checked"<?php endif; ?> />
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="del_attr">删除</a>
                                        </td>
                                    </tr><?php endforeach; endif; ?>
                                    <tr>
                                      <td colspan="5">
                                         <a href="javascript:void(0);" onclick="add_attribute(this);">添加一个属性</a>   
                                      </td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>                           
                    </div>   -->
                    <!--关联品牌 end-->                       
                        
                    <div class="pull-right">
                        <input type="hidden" name="id" value="<?php echo ($goodsType["id"]); ?>">
                        <button class="btn btn-primary" title="" data-toggle="tooltip" type="submit" data-original-title="保存"><i class="fa fa-save"></i></button>
                    </div>
			   
                </div>
			 </form>
             
             
<table id="attribute_html" style="display:none;">
    <tr>
        <td>
            <input type="text" name="attr_id[]" value=""  class="form-control" style="display:none;"/>
            <input type="text" name="order[]" value="50"  class="form-control"/>
        </td>
        <td><input type="text" name="attr_name[]" value=""  class="form-control"/></td>
        <td>
        <textarea rows="1" name="attr_values[]" cols="36" class="form-control"></textarea>
        </td>
        <td>
            <input type="checkbox" name="attr_index[]" value="1" checked="checked" />
        </td>
        <td>
            <a href="javascript:void(0);" class="del_attr">删除</a>
        </td>
    </tr>
</table>             
             
             <!--表单数据-->                
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>

// 将品牌滚动条里面的 对应分类移动到 最上面
//javascript:document.getElementById('category_id_3').scrollIntoView();
var brandScroll = 0;
function brand_scroll(o){
	var id = $(o).val();	
	if(!$('#category_id_'+id).is('h5')){
		return false;
	} 	 
	$('#ajax_brandList').scrollTop(-brandScroll);
	var sp_top = $('#category_id_'+id).offset().top; // 标题自身往上的 top
	var div_top = $('#ajax_brandList').offset().top; // div 自身往上的top
	$('#ajax_brandList').scrollTop(sp_top-div_top); // div 移动
	brandScroll = sp_top-div_top;
}

 // 将规格滚动条里面的 对应分类移动到 最上面
//javascript:document.getElementById('category_id_3').scrollIntoView();
var specScroll = 0;
function spec_scroll(o){
	var id = $(o).val();
	
	if(!$('#categoryId'+id).is('h5')){
		return false;
	}	
		 
	$('#ajax_specList').scrollTop(-specScroll);
	var sp_top = $('#categoryId'+id).offset().top; // 标题自身往上的 top
	var div_top = $('#ajax_specList').offset().top; // div 自身往上的top
	$('#ajax_specList').scrollTop(sp_top-div_top); // div 移动
	specScroll = sp_top-div_top;
}


// 判断输入框是否为空
function checkgoodsTypeName(){
	var name = $("#addEditGoodsTypeForm").find("input[name='name']").val();
    if($.trim(name) == '')
	{
		$("#err_name").show();
		return false;
	}
	return true;
}



/** 以下是编辑时默认选中某个商品分类*/
$(document).ready(function(){

	<?php if($goodsType['cat_id2'] > 0): ?>// 商品分类第二个下拉菜单
		 get_category('<?php echo ($goodsType[cat_id1]); ?>','cat_id2','<?php echo ($goodsType[cat_id2]); ?>');<?php endif; ?>
	<?php if($goodsType['cat_id3'] > 0): ?>// 商品分类第二个下拉菜单
		 get_category('<?php echo ($goodsType[cat_id2]); ?>','cat_id3','<?php echo ($goodsType[cat_id3]); ?>');<?php endif; ?>   
		
	getSpecList(0,0); // 默认查询所有规格
	getBrandList(0,0); // 默认查出所有品牌
 
});


/**
*获取筛选规格 查找某个分类下的所有Spec
* v 是分类id  l 是分类等级 比如 1级分类 2级分类 等
*/
function getSpecList(v,l)
{	  
	$.ajax({
		type : "GET",			
		url:"/index.php?m=Admin&c=Goods&a=getSpecByCat&cat_id="+v+"&l="+l+"&type_id=<?php echo ($goodsType[id]); ?>",//+tab,
		success: function(data)
		{					 
		   $("#ajax_specList").html('').append(data);
		}
	}); 		
}

/**
*获取筛选品牌 查找某个分类下的所有品牌
* v 是分类id  l 是分类等级 比如 1级分类 2级分类 等
*/
function getBrandList(v,l)
{	
		$.ajax({
			type : "GET",			
			url:"/index.php?m=Admin&c=Goods&a=getBrandByCat&cat_id="+v+"&l="+l+"&type_id=<?php echo ($goodsType[id]); ?>",//+tab,
			success: function(data)
			{					 
			   $("#ajax_brandList").html('').append(data);
			}
		}); 		 
}

// 添加一行属性	
function add_attribute(obj)
{	
  var trHtml = $('#attribute_html > tbody').html();
  $(obj).parent().parent().before(trHtml);
}	

// 删除一个 属性
$(document).on("click",".del_attr",function(){
	
	if(confirm('确定要删除吗?'))
	{
		$(this).parent().parent().remove();		
		var attr_id = $(this).parent().parent().find("input[name='attr_id\[\]']").val();				

		if(attr_id == '')
			return false;
			
		$.ajax({
			type : "GET",			
			url:"/index.php?m=Admin&c=Goods&a=delGoodsAttribute&id="+attr_id,
			success: function(data)
			{					 
			   // 删除回调
			}
		});		
	}
});
</script>
</body>
</html>