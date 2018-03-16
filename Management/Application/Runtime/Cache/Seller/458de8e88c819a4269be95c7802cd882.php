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

    <section class="content" style="padding:0px 15px;">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
                <a onclick="get_help(this)" data-url="http://www.tp-shop.cn/Doc/Indexbbc/article/id/1064/developer/user.html" class="btn btn-default" href="javascript:;"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">           
                <div class="panel-body ">   
                    <ul class="nav nav-tabs">
    <li <?php if(ACTION_NAME == 'store_setting'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/store_setting');?>" onclick="goset(this)" data-toggle="tab">店铺设置</a></li> 
    <!--<li <?php if(ACTION_NAME == 'store_slide'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/store_slide');?>" onclick="goset(this)" data-toggle="tab">幻灯片设置</a></li> -->
    <!--<li <?php if(ACTION_NAME == 'theme'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/theme');?>" onclick="goset(this)" data-toggle="tab">店铺主题</a></li> -->
    <!--<li <?php if(ACTION_NAME == 'mobile_slide'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/mobile_slide');?>" onclick="goset(this)" data-toggle="tab">手机店铺设置</a></li>-->
    <li <?php if(ACTION_NAME == 'distribut'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/distribut');?>" onclick="goset(this)" data-toggle="tab">分销设置</a></li>                        
    <li <?php if(ACTION_NAME == 'message'): ?>class="active"<?php endif; ?>><a href="javascript:void(0)" data-url="<?php echo U('Store/message');?>" onclick="goset(this)" data-toggle="tab">消息通知</a></li>
</ul>
                    <!--表单数据-->
                    <form method="post" id="handlepost" action="<?php echo U('Store/setting_save');?>">                    
                        <!--通用信息-->
                    <div class="tab-content" style="padding:20px 0px;">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody> 
                                <tr><td>店铺等级：</td>
                                	<td colspan="2"><?php echo ($store["grade_name"]); ?></td>
                                </tr>
                                <tr><td>店铺名称：</td>
                                	<td><input type="text" name="store_name" value="<?php echo ($store["store_name"]); ?>"></td>
                                	<td></td>
                                </tr>
                                <tr>
                                    <td>主营商品：</td>
                                    <td><textarea rows="4" name="store_zy" cols="80" ><?php echo ($store["store_zy"]); ?></textarea></td>
                               		<td class="text-warning">关键字最多可输入50字，请用","进行分隔，例如”男装,女装,童装”</td>
                                </tr>
                                <!--   
                              	<tr>
                                    <td>店铺二维码：</td>
                                    <td>                     		
            							<img src="" height="100px" title="">
                                    </td>
                                    <td class="text-warning">保存后，生成新的二维码</td>
                                </tr>  
                                -->                             
                                <tr>
                                    <td>店铺LOGO：</td>
                                    <td><div style="width: 200px;height: 80px;">
                                    		 <img height="80" id="store_logo" src="<?php if(empty($store["store_logo"])): else: echo ($store["store_logo"]); endif; ?>" nc_type="store_label">
         								 </div>
         								 <input type="hidden" name="store_logo" value="<?php echo ($store["store_logo"]); ?>">
                         		 		<input type="button" class="button" onClick="GetUploadify(1,'store_logo','seller','callback1')"  value="上传  logo"/>
                                   </td>
                                	<td  class="text-warning">建议使用宽200像素-高60像素内的GIF或PNG透明图片；点击下方"提交"按钮后生效。</td>
                                </tr>                                    
                            	<tr>
                                    <td>店铺banner：</td>
                                    <td><div style="height:100px;">
                                    		 <img height="120" id="store_banner" src="<?php if(empty($store["store_banner"])): ?>/Public/images/not_adv.jpg<?php else: echo ($store["store_banner"]); endif; ?>" nc_type="store_label">
         								 </div>
         								 <input type="hidden" name="store_banner" value="<?php echo ($store["store_banner"]); ?>">
                                     </td>
                                	<td >
                                		<span class="text-warning">建议使用宽1000像素*高250像素的图片；点击下方"提交"按钮后生效。</span>
                                		<p><input type="button" class="button" onClick="GetUploadify(1,'store_banner','seller','callback2')"  value="上传banner"/></p>
                                		<p>banner背景颜色<input class="form-control" name="bgcolor" type="color" value="<?php echo ($info["bgcolor"]); ?>" style="width:200px;"/></p>
                                	</td>
                                </tr>
                                
                                <tr>
                                    <td>店铺电话：</td>
                                    <td colspan="2"><input type="text"  pattern="^\d{1,}$" title="只能输入数字"  class="input-sm" name="store_phone" value="<?php echo ($store["store_phone"]); ?>"></td>
                                </tr>
                                <tr>
                                	<td>客服QQ：</td>
                                	<td colspan="2"><input type="number" name="store_qq" class="input-sm" value="<?php echo ($store["store_qq"]); ?>"></td>
                                </tr>
                                <tr>
                                    <td>阿里旺旺：</td>
                                    <td colspan="2"><input type="text" name="store_aliwangwang" class="input-sm" value="<?php echo ($store["store_aliwangwang"]); ?>"></td>
                                </tr>
                                <tr>
                                	<td>店铺地址：</td>
                                	<td colspan="2">
                                	   <!--<div class="col-xs-2">-->
                                        <!--<select onchange="get_city(this)" id="province" name="province_id" class="form-control" style="margin-left:-15px;">-->
                                            <!--<option  value="0">选择省份</option>-->
                                            <!--<?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                                                <!--<option value="<?php echo ($vo["id"]); ?>" <?php if($store[province_id] == $vo[id]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option>-->
                                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                                        <!--</select>-->
                                        <!--</div>   -->
                                        <!--<div class="col-xs-2">                                        -->
                                        <!--<select onchange="get_area(this)" id="city" name="city_id" class="form-control">-->
                                            <!--<option value="0">选择城市</option>-->
                                            <!--<?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                                                <!--<option value="<?php echo ($vo["id"]); ?>" <?php if($store[city_id] == $vo[id]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option>-->
                                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                                        <!--</select>-->
                                        <!--</div>   -->
                                        <!--<div class="col-xs-2">                                        -->
                                        <!--<select id="district" name="district" class="form-control">-->
                                            <!--<option value="0">选择区域</option>-->
                                            <!--<?php if(is_array($area)): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
                                                <!--<option value="<?php echo ($vo["id"]); ?>" <?php if($store[district] == $vo[id]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option>-->
                                            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                                        <!--</select>-->
                                        <!--</div> -->
                                        <div class="col-xs-3">
                                        	<input type="text" placeholder="详细地址" class="form-control" name="store_address" value="<?php echo ($store["store_address"]); ?>">
                                        </div>      
                                	</td>
                                </tr>
                                <tr>
                                    <td>满多少免运费：</td>
                                    <td><input type="text" class="form-control" name="store_free_price" value="<?php echo ($store["store_free_price"]); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;"></td>
                                    <td class="text-warning">超出该金额免运费，大于0才表示该值有效</td>
                                </tr>
                                <tr>
                                	<td>SEO关键字：</td>
                                	<td><input type="text" class="form-control" name="seo_keywords" value="<?php echo ($store["seo_keywords"]); ?>"></td>
                                	<td class="text-warning">用于店铺搜索引擎的优化，关键字之间请用英文逗号分隔</td>
                                	
                                </tr>  
                                <tr>
                                	<td>SEO店铺描述：</td>
                                	<td colspan="2">
                                		<textarea rows="4" cols="80" name="seo_description"><?php echo ($store["seo_description"]); ?></textarea>
                                	</td>
                                </tr>          
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><input type="hidden" name="store_id" value="<?php echo ($store["store_id"]); ?>"></td>
                                	<td></td>
                                	<td class="text-right"><input class="btn btn-primary" type="button" onclick="adsubmit()" value="保存"></td>
                                	</tr>
                                </tfoot>                               
                                </table>
                        </div>                           
                    </div>              
			    	</form><!--表单数据-->
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function adsubmit(){
	$('#handlepost').submit();
}

function goset(obj){
	window.location.href = $(obj).attr('data-url');
}

function callback1(img_str){
	$('input[name="store_logo"]').val(img_str);
	$('#store_logo').attr('src',img_str);
}

function callback2(img_str){
	$('input[name="store_banner"]').val(img_str);
	$('#store_banner').attr('src',img_str);
}
</script>
</body>
</html>