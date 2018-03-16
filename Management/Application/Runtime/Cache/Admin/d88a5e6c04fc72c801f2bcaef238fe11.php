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
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 店铺管理</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_store" data-toggle="tab">店铺信息</a></li>
                        <li><a href="#tab_info" data-toggle="tab">注册信息</a></li>                                        
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="store_info">
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_store">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-md-2">店铺账号：</td>
                                    <td><?php echo ($store["seller_name"]); ?></td>
                                </tr>
                                <tr>
                                    <td>店铺名称：</td>
                                    <td>
	                                    <input type="text" value="<?php echo ($store["store_name"]); ?>" name="store[store_name]" class="form-control" style="width:250px;"/>
                                        <span id="err_goods_remark" style="color:#F00; display:none;"></span>
                                        <input type="hidden" name="store[user_id]" value="<?php echo ($store["user_id"]); ?>">
                                    </td>                                                                        
                                </tr>
                                <tr>
                                    <td>开店时间：</td>                                                                       
                                    <td><?php echo (date("Y-m-d H:i:s",$store["store_time"])); ?></td>
                                    <span id="err_goods_sn" style="color:#F00; display:none;"></span>
                                </tr>                              
                                <tr>
                                    <td>所属分类:</td>
                                    <td>
                                      <select name="store[sc_id]" class="form-control" style="width:200px;">
                                        <option value="0">请选择分类</option>                                      
                                             <?php if(is_array($store_class)): foreach($store_class as $k=>$v): ?><option value="<?php echo ($k); ?>" <?php if($k == $store['sc_id']): ?>selected="selected"<?php endif; ?>>
                                               	<?php echo ($v); ?>
                                               </option><?php endforeach; endif; ?>
                                      </select>                         
                                    </td>
                                </tr>                                                                
                                <tr>
                                    <td>所属等级:</td>
                                    <td>
									<select name="store[grade_id]" id="grade_id" class="form-control" style="width:200px;">
                                       		<option value="0">选择等级</option>
                                            <?php if(is_array($store_grade)): foreach($store_grade as $k=>$v): ?><option value="<?php echo ($k); ?>" <?php if($k == $store['grade_id'] ): ?>selected="selected"<?php endif; ?>>
                                               	<?php echo ($v); ?>
                                               </option><?php endforeach; endif; ?>
                                      </select>                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td>店铺保障服务开关:</td>
                                    <td>
				                       <div class="row">
				                       		<div class="col-md-2">
				                       			<button type="button" rel="1" data-id="ensure" class="btn <?php if($store[ensure] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">保障</button>
				                       			<button type="button" rel="0" data-id="ensure" class="btn <?php if($store[ensure] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">图标</button>
				                       			<input type="hidden" id="ensure" name="store[ensure]" value="<?php echo ($store["ensure"]); ?>">
				                       		</div>
				                       		<div class="col-md-2">
				                       			<button type="button" data-id="deposit_icon" rel="1" class="btn <?php if($store[deposit_icon] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">保证金</button>
				                       			<button type="button" data-id="deposit_icon" rel="0" class="btn <?php if($store[deposit_icon] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">图标</button>
				                       			<input type="hidden" id="deposit_icon" name="store[deposit_icon]" value="<?php echo ($store["deposit_icon"]); ?>">
				                       		</div>
				                       		<div class="col-md-2 input-group">
				                       			<input type="text" name="store[deposit]" placeholder="保证金额" value="<?php echo ($store["deposit"]); ?>" class="form-control">
				                       			<span class="input-group-addon">元</span>
				                       		</div>
				                       </div>                 
                                    </td>
                                </tr>  
                                <tr>
                                    <td>保障内容开关:</td>
                                    <td>
				                        <div class="row">
					                        <div class="col-xs-2">
					                            <button type="button"  rel="1"  data-id="certified" class="btn <?php if($store[certified] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">正品</button>
				                       			<button type="button"  rel="0"  data-id="certified" class="btn <?php if($store[certified] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">保障</button>
				                       			<input type="hidden" id="certified" name="store[certified]" value="<?php echo ($store["certified"]); ?>">
					                        </div>
					                        <div class="col-xs-2">
					                            <button type="button" rel="1" data-id="qitian" class="btn <?php if($store[qitian] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">七天</button>
				                       			<button type="button" rel="0" data-id="qitian" class="btn <?php if($store[qitian] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">退换</button>
				                       			<input type="hidden" id="qitian" name="store[qitian]" value="<?php echo ($store["qitian"]); ?>">
					                        </div>
					                        <div class="col-xs-2">
					                            <button type="button" rel="1" data-id="returned" class="btn <?php if($store[returned] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">退换</button>
				                       			<button type="button" rel="0" data-id="returned" class="btn <?php if($store[returned] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">承诺</button>
				                       			<input type="hidden" id="returned" name="store[returned]" value="<?php echo ($store["returned"]); ?>">
					                        </div>
					                        <div class="col-xs-2">
					                            <button type="button" rel="1" data-id="two_hour" class="btn <?php if($store[two_hour] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">2H</button>
				                       			<button type="button" rel="0" data-id="two_hour" class="btn <?php if($store[two_hour] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">发货</button>
				                       			<input type="hidden" id="two_hour" name="store[two_hour]" value="<?php echo ($store["two_hour"]); ?>">
					                        </div>
					                        <div class="col-xs-2">
					                            <button type="button" rel="1" data-id="cod" class="btn <?php if($store[cod] == 1): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">货到</button>
				                       			<button type="button" rel="0" data-id="cod" class="btn <?php if($store[cod] == 0): ?>btn-info<?php else: ?>btn-default<?php endif; ?> btn-flat">付款</button>
				                       			<input type="hidden" id="cod" name="store[cod]" value="<?php echo ($store["cod"]); ?>">
					                        </div>
				                        </div>              
                                    </td>
                                </tr>
                                <tr>
		                            <td>商品是否需要审核：</td>
		                            <td><input type="radio" name="store[goods_examine]" value="1" <?php if($store[goods_examine] == 1): ?>checked<?php endif; ?>>是
		                            	<input type="radio" name="store[goods_examine]" value="0" <?php if($store[goods_examine] == 0): ?>checked<?php endif; ?>>否
		                            </td>
		                        </tr>                                
                                <tr>
		                            <td>状态：</td>
		                            <td><input type="radio" name="store[store_state]" value="1" onclick="$('#close_reason').hide();" <?php if($store[store_state] == 1): ?>checked<?php endif; ?>>开启
		                            	<input type="radio" name="store[store_state]" onclick="$('#close_reason').show();" value="0" <?php if($store[store_state] == 0): ?>checked<?php endif; ?>>关闭
		                            </td>
		                        </tr>                             
		                        <tr id="close_reason" <?php if($store[store_state] == 1): ?>style="display:none;"<?php endif; ?>>
		                        	<td>关闭原因：</td>
		                        	<td><textarea name="store[store_close_info]" rows="3" cols="30"><?php echo ($store["store_close_info"]); ?></textarea></td>
		                        </tr> 
                             </tbody>                                
                           </table>
                        </div>

                        <div class="tab-pane margin" id="tab_info">
				        <div class="panel panel-default">
				            <div class="panel-heading">
				                <h3 class="panel-title">公司及联系人信息</h3>
				            </div>
				            <div class="panel-body">
				                <table class="table table-bordered">
				                    <tbody>
				                    <tr>
				                        <td>公司名称</td><td><input name="company_name" value="<?php echo ($apply["company_name"]); ?>" class="form-control"></td>
				                        <td>公司性质:</td><td><input name="company_type" value="<?php echo ($apply["company_type"]); ?>" class="form-control"></td>
				                        <td>公司网址:</td><td><input name="company_website" value="<?php echo ($apply["company_website"]); ?>" class="form-control"></td>
				                    </tr>
				                    <tr><td>公司所在地:</td>
				                    	<td> <div class="form-group ">
                                    	<div class="col-xs-3">
                                        <select onchange="get_city(this)" id="province" name="company_province" class="form-control">
                                            <option value="0">选择省份</option>
                                            <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo[id] == $apply[company_province]): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                         </div>   
                                        <div class="col-xs-3">                                        
                                        <select onchange="get_area(this)" id="city" name="company_city" class="form-control">
                                            <option value="0">选择城市</option>
                                            <?php if(is_array($city)): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        </div>   
                                        <div class="col-xs-3">                                        
                                        <select id="district" name="company_district" class="form-control">
                                            <option value="0">选择区域</option>
                                            <?php if(is_array($area)): $i = 0; $__LIST__ = $area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                         </div>   
                                         </div> 
                                        </td>
				                        <td>公司详细地址:</td>
				                        <td><input name="company_address" value="<?php echo ($apply["company_address"]); ?>" class="form-control"></td>
				                        <td>固定电话</td><td><input name="company_telephone" value="<?php echo ($apply["company_telephone"]); ?>" class="form-control"></td>
				                    </tr>
				                    <tr><td>邮政编码:</td><td><input name="company_zipcode" value="<?php echo ($apply["company_zipcode"]); ?>" class="form-control"></td>
				                        <td>电子邮箱:</td><td><input name="company_email" value="<?php echo ($apply["company_email"]); ?>" class="form-control"></td>
				                        <td>传真:</td><td><input name="company_fax" value="<?php echo ($apply["company_fax"]); ?>" class="form-control"></td>
				                    </tr>
				                    <tr>
				                        <td>联系人姓名:</td><td><input name="contacts_name" value="<?php echo ($apply["contacts_name"]); ?>" class="form-control"></td>
				                        <td>联系人电话:</td><td><input name="contacts_mobile" value="<?php echo ($apply["contacts_mobile"]); ?>" class="form-control"></td>
				                        <td>联系人邮箱:</td><td><input name="contacts_email" value="<?php echo ($apply["contacts_email"]); ?>" class="form-control"></td>
				                    </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
				        <!--新订单列表 收货人信息-->
				        <div class="panel panel-default">
				            <div class="panel-heading">
				                <h3 class="panel-title">营业执照信息（副本）</h3>
				            </div>
				            <div class="panel-body">
				                <table class="table table-bordered">
				                    <tbody>
				                    <tr>
										<td>营业执照号:</td><td><input name="business_licence_number" value="<?php echo ($apply["business_licence_number"]); ?>" class="form-control"></td>
										<td>营业执照有效期:</td><td><input name="business_date_end" value="<?php echo ($apply["business_date_start"]); ?>=><?php echo ($apply["business_date_end"]); ?>" class="form-control"></td>
										<td>法定经营范围:</td>
										<td><textarea rows="3" cols="40" name="business_scope"><?php echo ($apply["business_scope"]); ?></textarea></td>
				                    </tr>
				                    <tr>
				   					<td>注册资本:</td><td><input name="reg_capital" value="<?php echo ($apply["reg_capital"]); ?>" class="form-control"></td>
									<td>组织机构代码:</td><td><input name="orgnization_code" value="<?php echo ($apply["orgnization_code"]); ?>" class="form-control"></td>
							 		<td>一般纳税人证明:</td><td><?php echo ($apply["taxpayer_cert"]); ?></td>
				                    </tr>
				                    <tr>
				   					<td>法人代表姓名:</td><td><input name="legal_person" value="<?php echo ($apply["legal_person"]); ?>" class="form-control"></td>
									<td>纳税类型码:</td>
									<td><?php echo ($apply["tax_rate"]); ?></td>
							 		<td>税务登记号码:</td><td><input name="attached_tax_number" value="<?php echo ($apply["attached_tax_number"]); ?>" class="form-control"></td>
				                    </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
				        <!--新订单列表 商品信息-->
				        <div class="panel panel-default">
				            <div class="panel-heading">
				                <h3 class="panel-title">结算账号信息 </h3>
				            </div>
				            <div class="panel-body">
				                <table class="table table-bordered">
				                    <thead>
				                    <tr>
				                        <td class="text-left">银行开户名</td>
				                        <td class="text-left">公司银行账号：</td>
				                        <td class="text-right">开户银行支行名称：</td>
				                        <td class="text-right">支行联行号：</td>
				                        <td class="text-right">开户银行所在地：</td>
				                    </tr>
				                    </thead>
				                    <tbody>
				                        <tr>
				                            <td class="text-left"><input name="bank_account_number" value="<?php echo ($apply["bank_account_name"]); ?>" class="form-control"></td>
				                            <td class="text-left"><input name="bank_account_name" value="<?php echo ($apply["bank_account_number"]); ?>" class="form-control"></td>
				                            <td class="text-right"><input name="bank_branch_name" value="<?php echo ($apply["bank_branch_name"]); ?>" class="form-control"></td>
				                            <td class="text-right"><input name="bank_province" value="<?php echo ($apply["bank_province"]); ?>" class="form-control"></td>
				                            <td class="text-right"><input name="bank_city" value="<?php echo ($apply["bank_city"]); ?>" class="form-control"></td>
				                        </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
				        <!--新订单列表 费用信息-->
				        <div class="panel panel-default">
				            <div class="panel-heading">
				                <h3 class="panel-title">店铺负责人信息</h3>
				            </div>
				            <div class="panel-body">
				                <table class="table table-bordered">
				                    <tbody>
				         				<tr>
				                         <td class="text-right">店铺负责人:</td><td><input name="store_person_name" value="<?php echo ($apply["store_person_name"]); ?>" class="form-control"></td>
				                         <td class="text-right">负责人手机号码:</td><td><input name="store_person_mobile" value="<?php echo ($apply["store_person_mobile"]); ?>" class="form-control"></td>
				                    	</tr>
				                    	<tr>			                        				                         
				                         <td class="text-right">负责人QQ号码:</td><td><input name="store_person_qq" value="<?php echo ($apply["store_person_qq"]); ?>" class="form-control"></td>
				                    	 <td class="text-right">负责人邮箱：</td><td><input type="text" name="store_person_email" value="<?php echo ($apply["store_person_email"]); ?>" class="form-control"></td>
				                    	</tr>
				                    </tbody>
				                </table>
				
				            </div>
				        </div>
				        <div class="panel panel-default">
				            <div class="panel-heading">
				                <h3 class="panel-title">证件信息</h3>
				            </div>
				            <div class="panel-body">
				                <table class="table table-bordered">
				                    <thead>
				                    <tr>
				                        <td class="text-left">企业营业执照副本：</td>
				                        <td class="text-left">税务登记证复印件：</td>
				                        <td class="text-right">织机构代码证复印件：</td>
				                        <td class="text-right">法人身份证：</td>
				                        <td class="text-right">店铺负责人身份证：</td>
				                    </tr>
				                    </thead>
				                    <tbody>
				                        <tr>
				                            <td>
				                            	<div class="text-center">
				                            		<a href="<?php echo ($apply["business_licence_cert"]); ?>" target="_blank">
													<?php if($apply[business_licence_cert] == ''): ?><img id="business_licence_cert" src="/Public/images/not_adv.jpg" height="120">
				                            		<?php else: ?>
				                            			<img id="business_licence_cert" src="<?php echo ($apply["business_licence_cert"]); ?>" height="120"><?php endif; ?>
				                            		</a>
				                            	</div>
				                            	<div class="text-center">
				                            		<input type="hidden" name="business_licence_cert" value="<?php echo ($apply["business_licence_cert"]); ?>">
				                            		<input type="button" class="btn btn-default" onClick="upload_img('business_licence_cert')"  value="上传图片"/>
				                            	</div>
				                            </td>
              				                <td>
				                            	<div class="text-center">
				                            		<a href="<?php echo ($apply["taxpayer_cert"]); ?>" target="_blank">
				                            		<?php if($apply[taxpayer_cert] == ''): ?><img id="taxpayer_cert" src="/Public/images/not_adv.jpg" height="120">
				                            		<?php else: ?>
				                            			<img id="taxpayer_cert" src="<?php echo ($apply["taxpayer_cert"]); ?>" height="120"><?php endif; ?>
				                            		</a>
				                            	</div>
				                            	<div class="text-center">
				                            		<input type="hidden" name="taxpayer_cert" value="<?php echo ($apply["taxpayer_cert"]); ?>">
				                            		<input type="button" class="btn btn-default" onClick="upload_img('taxpayer_cert')"  value="上传图片"/>
				                            	</div>
				                            </td>
				                            <td>
				                            	<div class="text-center">
				                            		<a href="<?php echo ($apply["orgnization_cert"]); ?>" target="_blank">
				                            		<?php if($apply[orgnization_cert] == ''): ?><img id="orgnization_cert" src="/Public/images/not_adv.jpg" height="120">
				                            		<?php else: ?>
				                            			<img id="orgnization_cert" src="<?php echo ($apply["orgnization_cert"]); ?>" height="120"><?php endif; ?>
				                            		</a>
				                            	</div>
				                            	<div class="text-center">
				                            		<input type="hidden" name="orgnization_cert" value="<?php echo ($apply["orgnization_cert"]); ?>">
				                            		<input type="button" class="btn btn-default" onClick="upload_img('orgnization_cert')"  value="上传图片"/>
				                            	</div>
				                            </td>
				                            <td>
				                            	<div class="text-center">
				                            		<a href="<?php echo ($apply["legal_identity_cert"]); ?>" target="_blank">
				                            		<?php if($apply[legal_identity_cert] == ''): ?><img id="legal_identity_cert" src="/Public/images/not_adv.jpg" height="120">
				                            		<?php else: ?>
				                            			<img id="legal_identity_cert" src="<?php echo ($apply["legal_identity_cert"]); ?>" height="120"><?php endif; ?>
				                            		</a>
				                            	</div>
				                            	<div class="text-center">
				                            		<input type="hidden" name="legal_identity_cert" value="<?php echo ($apply["legal_identity_cert"]); ?>">
				                            		<input type="button" class="btn btn-default" onClick="upload_img('legal_identity_cert')"  value="上传图片"/>
				                            	</div>
				                            </td>
				                            <td>
				                            	<div class="text-center">
				                            		<a href="<?php echo ($apply["store_person_cert"]); ?>" target="_blank">
				                            		<?php if($apply[store_person_cert] == ''): ?><img id="store_person_cert" src="/Public/images/not_adv.jpg" height="120">
				                            		<?php else: ?>
				                            			<img id="store_person_cert" src="<?php echo ($apply["store_person_cert"]); ?>" height="120"><?php endif; ?>
				                            		</a>
				                            	</div>
				                            	<div class="text-center">
				                            		<input type="hidden" name="store_person_cert" value="<?php echo ($apply["store_person_cert"]); ?>">
				                            		<input type="button" class="btn btn-default" onClick="upload_img('store_person_cert')"  value="上传图片"/>
				                            	</div>
				                            </td>
				                        </tr>
				                    </tbody>
				                </table>
				            </div>
				        </div>
                        </div>
                    </div>              
                    <div class="pull-right">
                        <input type="hidden" name="store_id" value="<?php echo ($store["store_id"]); ?>">
                        <button class="btn btn-primary" onclick="actsubmit()" title="" type="button">保存</button>
                    </div>
			    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function(){
	$('.btn-flat').click(function(){
		if(!$(this).hasClass('btn-info')){
			$(this).removeClass('btn-default');
			$(this).addClass('btn-info');
			$(this).siblings().addClass('btn-default');
			$(this).siblings().removeClass('btn-info');
			$('#'+$(this).attr('data-id')).val($(this).attr('rel'));
		}
	})
})


var flag = true;
function actsubmit(){
	if($('input[name=store_name]').val() == ''){
		layer.msg("店铺名称不能为空", {icon: 2,time: 2000});
		return;
	}
	if(flag){
		$('#store_info').submit();
	}else{
		layer.msg("请检查店铺名称和卖家账号", {icon: 2,time: 2000});
	}
}

var tmp_type = '';
function upload_img(cert_type){
	tmp_type = cert_type;
	GetUploadify(1,'store','cert','callback');
}

function callback(img_str){
	$('#'+tmp_type).attr('src',img_str);
	$('input[name='+tmp_type+']').val(img_str);
}
</script>
</body>
</html>