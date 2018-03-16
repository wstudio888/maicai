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
 

<!--引入高德地图JSAPI -->
<script src="//webapi.amap.com/maps?v=1.3&key=742abd0ce995841e7b1721000ac4672e"></script>

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
				            	<p class="text-success margin blod">店铺管理:</p>
				            </div>
				             <div class="form-group">
				             <?php if($is_own_shop == 0): ?><a class="btn btn-default" href="<?php echo U('Store/store_list');?>">店铺列表</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="btn btn-default" href="<?php echo U('Store/apply_list');?>" >开店申请</a>&nbsp;&nbsp;&nbsp;
                                 <a class="btn btn-default" href="<?php echo U('Store/reopen_list');?>" >签约申请</a>&nbsp;&nbsp;&nbsp;
                             <?php else: ?>
                                 <a class="btn btn-default" href="<?php echo U('Store/store_own_list');?>">管理</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a class="btn btn-default" href="javascript:;" >新增</a>&nbsp;&nbsp;&nbsp;<?php endif; ?>
				            </div>
	                        <div class="pull-right">
				                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
				            </div>
				          </div>
				       </div>
	    		 	</nav>
	               	<nav class="navbar navbar-default">
		            	<?php if($is_own_shop == 1): ?><div class="callout callout-inro">
			            	<p>平台可以在此处添加自营店铺，新增的自营店铺默认为开启状态</p>
							<p>新增自营店铺默认绑定所有经营类目并且佣金为0，可以手动设置绑定其经营类目</p>
							<p>新增自营店铺将自动创建店主会员账号（用于登录网站会员中心）以及商家账号（用于登录商家中心）</p>
						</div>
						<?php else: ?>
						<div class="callout callout-inro">
							<p>1. 平台可以在此处添加外驻店铺，新增的外驻店铺默认为开启状态</p>
					        <p>2. 新增外驻店铺默认绑定所有经营类目并且佣金为0，可以手动设置绑定其经营类目。</p>
					        <p>3. 新增外驻店铺将自动创建店主会员账号（用于登录网站会员中心）以及商家账号（用于登录商家中心）。</p>
		            	</div><?php endif; ?>
	    			</nav>
	             </div>
	             <div class="box-body">
	           	 <div class="row">
	            	<div class="col-sm-12">
	            	  <form method="post" id="store_info">
		              <table class="table table-bordered table-striped dataTable">
                        <tbody>
                        <tr><td>店铺名称：</td>
                        	<td><input name="store_name" value="<?php echo ($store["store_name"]); ?>" class="small form-control" onblur="store_check()"></td>
                        	<td></td>
                        </tr>
                        <tr hidden><td>店主账号：</td>
                        	<td><input type="text" name="user_name" class="small form-control" value="<?php echo ($store["user_name"]); ?>" onblur="store_check()"></td>
                        	<td class="text-warning">用于登录会员中心,支持手机或邮箱</td>
                        </tr>
                        <tr>
                            <td>店主卖家账号：</td>
                            <td><input name="seller_name" class="small form-control" value="<?php echo ($store["seller_name"]); ?>" onblur="store_check()"></td>
                       		<td class="text-warning">用于登录商家中心</td>
                        </tr>
                         <tr>
                            <td>登陆密码：</td>
                            <td><input type="password" class="small form-control" name="password" value="<?php echo ($store["password"]); ?>"></td>
                       		<td class="text-warning">密码为6-16位字母数字组合</td>
                        </tr>
                        <!-- 达达门店信息 -->
                         <tr>
                            <td>门店名称：</td>
                            <td><input type="text" name="dada_name" class="small form-control" value="<?php echo ($store["dada_name"]); ?>"></td>
                       		<td class="text-warning">达达门店名称</td>
                        </tr>
                         <tr>
                            <td>门店主营：</td>
                            <td><select name="dada_class" class="small form-control">
                                    <option value="1" class="select-option">食品小吃</option>
                                    <option value="2" class="select-option">饮料</option>
                                    <option value="3" class="select-option">鲜花</option>
                                    <option value="5" class="select-option">其他</option>
                                    <option value="8" class="select-option">文印票务</option>
                                    <option value="9" class="select-option">便利店</option>
                                    <option value="13" class="select-option">水果生鲜</option>
                                    <option value="19" class="select-option">同城电商</option>
                                    <option value="20" class="select-option">医药</option>
                                    <option value="21" class="select-option">蛋糕</option>
                                    <option value="24" class="select-option">酒品</option>
                                    <option value="25" class="select-option">小商品市场</option>
                                    <option value="26" class="select-option">服装</option>
                                    <option value="27" class="select-option">汽修零配</option>
                                    <option value="28" class="select-option">数码</option>
                                    <option value="29" class="select-option">小龙虾</option>
                            </select></td>
                       		<td class="text-warning">达达门店主营</td>
                        </tr>
                         <tr>
                            <td>联系人/店长：</td>
                            <td><input type="text" name="dada_contacts" class="small form-control" value="<?php echo ($store["dada_contacts"]); ?>"></td>
                       		<td class="text-warning">达达联系人</td>
                        </tr>
                        <tr>
                            <td>联系人身份证：</td>
                            <td><input type="text" name="dada_identity" class="small form-control" value="<?php echo ($store["password"]); ?>"></td>
                            <td class="text-warning">达达身份证地址</td>
                        </tr>
                         <tr>
                            <td>联系电话：</td>
                            <td><input type="text" name="dede_mobile" class="small form-control" value="<?php echo ($store["password"]); ?>"></td>
                            <td class="text-warning">达达联系电话</td>
                        </tr>
                        <tr>
                            <td>门店地址：</td>
                            <td><input type="text" name="dada_addres" id="addres" class="small form-control" value="<?php echo ($store["password"]); ?>"></td>
                            <td class="text-warning"  onclick="getaddres()">
                                <div style="align-content:center;height:34px;width:120px;border:1px solid #00acd6;text-align:center;line-height:34px;">定位确认地址</div>
                            </td>
                        </tr>
                        <tr >
                            <td colspan="3"><div id="container" style="width:776px; height:450px;margin:auto;"></div></td>
                            <div id ='message'></div>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                        <input type="hidden" name="dada_lat" id="dada_lat" />
                        <input type="hidden" name="dada_lng" id="dada_lng" />
                        <input type="hidden" name="dada_city" id="dada_city" />
                        <input type="hidden" name="dada_district" id="dada_district" />
                        <td colspan="3" style="text-align:center;">
                        	<a href="javascript:void(0)" onclick="actsubmit()" class="btn btn-info margin">提交</a>
                        	<input type="hidden" name="is_own_shop" value="<?php echo ($is_own_shop); ?>">
                        </td>
                        </tr>
                        </tfoot>
		               </table>
		               </form>
	               </div>
	            </div>
	          </div>
	        </div>
       	</div>
       </div>
   </section>
    <script type="text/javascript">
    var map = new AMap.Map('container', {
        zoom: 13,
        resizeEnable: true,
        pageSize: 5,
        city: "广州市",
        pageIndex: 1,
        center: [113.241923,23.165876],
        map: map,
    });
    function getaddres() {
        var keyword = $("#addres").val();
        AMap.service(["AMap.Geocoder"],function () {
            //实例化Geocoder
            geocoder = new AMap.Geocoder({
            });
            //地理编码
            geocoder.getLocation(keyword, function (status, result) {
                if (status === 'complete' && result.info === 'OK') {
                    //TODO:获得了有效经纬度，可以做一些展示工作
                    console.info(result);
                   console.info("经度:"+result.geocodes[0].location.lng)
                   console.info("纬度:"+result.geocodes[0].location.lat);
                   $("#dada_lng").val(result.geocodes[0].location.lng);
                   $("#dada_lat").val(result.geocodes[0].location.lat);
                   $("#dada_city").val(result.geocodes[0].addressComponent.city);
                   $("#dada_district").val(result.geocodes[0].addressComponent.district);
                    alert("定位成功");
                } else {
                  //获取经纬度失败
                  alert("定位失败,请选择详细的地址！");
                  console.info(status);
                }
            });
        });
    }
    function dingwei() {
        var keyword = $("#addres").val();
        AMap.service(["AMap.PlaceSearch"], function () {
            var placeSearch = new AMap.PlaceSearch({ //构造地点查询类
                pageSize: 5,
                pageIndex: 1,
                city: "广州", //城市
                map: map//,
                //panel: "panel"
            });
            //关键字查询
            placeSearch.search(keyword, function (status, result) {
            });
        });
    }
    AMap.plugin(['AMap.Autocomplete', 'AMap.PlaceSearch'], function () {
        var autoOptions = {
            city: "广州", //城市，默认全国
            input: "addres"//使用联想输入的input的id
        };
        autocomplete = new AMap.Autocomplete(autoOptions);
        var placeSearch = new AMap.PlaceSearch({
            city: '广州',
            map: map
        })

        AMap.event.addListener(autocomplete, "select", function (e) {
            placeSearch.search(e.poi.name)
        });
    });

        AMap.plugin('AMap.Geocoder',function(){
        var geocoder = new AMap.Geocoder({
            city: "广州"//城市，默认：“全国”
        });
        var marker = new AMap.Marker({
            map:map,
            bubble:true
        })
        var input = document.getElementById('addres');
        var message = document.getElementById('message');
        map.on('click',function(e){
            marker.setPosition(e.lnglat);
            geocoder.getAddress(e.lnglat,function(status,result){
              if(status=='complete'){
                 input.value = result.regeocode.formattedAddress
                 message.innerHTML = ''
              }else{
                 message.innerHTML = '无法获取地址'
              }
            })
        })

        input.onchange = function(e){
            var address = input.value;
            geocoder.getLocation(address,function(status,result){
              if(status=='complete'&&result.geocodes.length){
                marker.setPosition(result.geocodes[0].location);
                map.setCenter(marker.getPosition())
                message.innerHTML = ''
              }else{
                message.innerHTML = '无法获取位置'
              }
            })
        }

    });
    </script>
<script>
var flag = true;
function actsubmit(){
	if($('input[name=store_name]').val() == ''){
		layer.msg("店铺名称不能为空", {icon: 2,time: 2000});
		return;
	}
	/*var user_name = $('input[name=user_name]').val();
	if(user_name == ''){
		layer.msg("店主账号不能为空", {icon: 2,time: 2000});
		return;
	}
	if(!checkEmail(user_name) && !checkMobile(user_name)){
		layer.msg("前台账号格式有误", {icon: 2,time: 2000});
		return;
	}*/
	if($('input[name=seller_name]').val() == ''){
		layer.msg("店主卖家账号不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=password]').val() == ''){
		layer.msg("登陆密码不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dada_name]').val() == ''){
		layer.msg("门店名称不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dada_contacts]').val() == ''){
		layer.msg("联系人不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dada_class]').val() == ''){
		layer.msg("门店主营不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dada_identity]').val() == ''){
		layer.msg("联系人身份证不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dede_mobile]').val() == ''){
		layer.msg("联系电话不能为空", {icon: 2,time: 2000});
		return;
	}
	if($('input[name=dada_addres]').val() == ''){
		layer.msg("门店地址不能为空", {icon: 2,time: 2000});
		return;
	}
    if($('input[name=dada_lat]').val() == ''){
		layer.msg("定位失败,请重新等位", {icon: 2,time: 2000});
		return;
	}
	if(flag){
		$('#store_info').submit();
	}
}

function store_check(){
	$.ajax({
		type:'post',
		url:"<?php echo U('Store/store_check');?>",
		dataType:'json',
		data:{store_name:$('input[name=store_name]').val(),seller_name:$('input[name=seller_name]').val(),user_name:$('input[name=user_name]').val()},
		success:function(res){
			if(res.stat != 'ok'){
				layer.msg(res.msg, {icon: 2,time: 2000});
				flag = false;
				return;
			}else{
				flag = true;
			}
		}
	});
}
</script>
</div>
</body>
</html>