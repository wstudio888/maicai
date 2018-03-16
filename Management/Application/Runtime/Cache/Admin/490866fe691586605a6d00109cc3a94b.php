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
			            <div class="form-group pull-right">
			            	 <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
			            </div>			          
			          </div>
			       </div>
           </nav>
   
        <!--新订单列表 基本信息-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">公司及联系人信息</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>公司名称</td>
                        <td><?php echo ($apply["company_name"]); ?></td>
                        <td>公司性质:</td>
                         <td></td>
                        <td>公司网址:</td>
                         <td><?php echo ($apply["company_website"]); ?></td>
                    </tr>
                    <tr><td>公司所在地:</td>
                    	<td></td>
                        <td>公司详细地址:</td>
                        <td><?php echo ($apply["company_address"]); ?></td>
                        <td>固定电话</td><td><?php echo ($apply["company_telephone"]); ?></td>
                    </tr>
                    <tr><td>邮政编码:</td><td><?php echo ($apply["company_zipcode"]); ?></td>
                        <td>电子邮箱:</td><td><?php echo ($apply["company_email"]); ?></td>
                        <td>传真:</td><td><?php echo ($apply["company_fax"]); ?></td>
                    </tr>
                    <tr>
                        <td>联系人姓名:</td><td><?php echo ($apply["contacts_name"]); ?></td>
                        <td>联系人电话:</td><td><?php echo ($apply["contacts_mobile"]); ?></td>
                        <td>联系人邮箱:</td><td><?php echo ($apply["contacts_email"]); ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <!--新订单列表 收货人信息-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">营业执照信息（副本）</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
					<td>营业执照号:</td><td><?php echo ($apply["business_licence_number"]); ?></td>
					<td>营业执照有效期:</td><td><?php echo ($apply["business_date_start"]); ?>=><?php echo ($apply["business_date_end"]); ?></td>
					<td>法定经营范围:</td><td><?php echo ($apply["business_scope"]); ?></td>
                    </tr>
                    <tr>
   					<td>注册资本:</td><td><?php echo ($apply["reg_capital"]); ?></td>
					<td>组织机构代码:</td><td><?php echo ($apply["orgnization_code"]); ?></td>
			 		<td>一般纳税人证明:</td><td><?php echo ($apply["reg_capital"]); ?></td>
                    </tr>
                    <tr>
   					<td>法人代表姓名:</td><td><?php echo ($apply["legal_person"]); ?></td>
					<td>纳税类型码:</td><td><?php echo ($apply["tax_rate"]); ?></td>
			 		<td>税务登记号码:</td><td><?php echo ($apply["attached_tax_number"]); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--新订单列表 商品信息-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">结算账号信息 </h3>
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
                            <td class="text-left"><?php echo ($apply["bank_account_name"]); ?></td>
                            <td class="text-left"><?php echo ($apply["bank_account_number"]); ?></td>
                            <td class="text-right"><?php echo ($apply["bank_branch_name"]); ?></td>
                            <td class="text-right"><?php echo ($apply["bank_province"]); ?></td>
                            <td class="text-right"><?php echo ($apply["bank_city"]); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--新订单列表 费用信息-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">店铺经营信息</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="text-right">卖家账号:</td><td><?php echo ($apply["seller_name"]); ?></td>
                        <td class="text-right">店铺名称:</td><td><?php echo ($apply["store_name"]); ?></td>
                        <td class="text-right">店铺等级:</td><td></td>
                        </tr>  
         				<tr>
                        <td class="text-right">店铺负责人:</td><td><?php echo ($apply["store_person_name"]); ?></td>
                         <td class="text-right">负责人手机号码:</td><td><?php echo ($apply["store_person_mobile"]); ?></td>
                         <td class="text-right">负责人QQ号码:</td><td><?php echo ($apply["store_person_qq"]); ?></td>
                        </tr>
                        <tr>
                        <td class="text-right">店铺分类:</td><td><?php echo ($apply["sc_name"]); ?></td>
                        <td class="text-right">负责人邮箱:</td><td><?php echo ($apply["store_person_email"]); ?></td>
                        <td class="text-right">审核意见:</td><td></td>
                  		</tr>
                  		<tr>
                        <td class="text-right">经营类目:</td>
                        <td colspan="5">
	                        <table class="table table-bordered">
					               <thead>
				                    <tr>
				                        <th>分类1</th>
				                        <th>分类2</th>
				                        <th>分类3</th>
				                        <th>比例</th>
				                    </tr>
					               </thead>
				                   <tbody>
				                   <?php if(is_array($bind_class_list)): foreach($bind_class_list as $key=>$vo): ?><tr>
				                        <td><?php echo ($vo["class_1_name"]); ?></td>
				                        <td><?php echo ($vo["class_2_name"]); ?></td>
				                        <td><?php echo ($vo["class_3_name"]); ?></td>
				                        <td><?php echo ($vo["commis_rate"]); ?>%</td>
				                    </tr><?php endforeach; endif; ?>
				                   </tbody>
				        	</table>
                        </td>
         				</tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">证件信息</h3>
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
                            <td class="text-left"><a href="<?php echo ($apply["business_licence_cert"]); ?>" target="_blank"><img src="<?php echo ($apply["business_licence_cert"]); ?>" height="60"></a></td>
                            <td class="text-left"><a href="<?php echo ($apply["taxpayer_cert"]); ?>" target="_blank"><img src="<?php echo ($apply["taxpayer_cert"]); ?>" height="60"></a></td>
                            <td class="text-right"><a href="<?php echo ($apply["orgnization_cert"]); ?>" target="_blank"><img src="<?php echo ($apply["orgnization_cert"]); ?>" height="60"></a></td>
                            <td class="text-right"><a href="<?php echo ($apply["legal_identity_cert"]); ?>" target="_blank"><img src="<?php echo ($apply["legal_identity_cert"]); ?>" height="60"></a></td>
                            <td class="text-right"><a href="<?php echo ($apply["store_person_cert"]); ?>" target="_blank"><img src="<?php echo ($apply["store_person_cert"]); ?>" height="60"></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
       </div>
	  </div>
    </div> 
   </section>
</div>
</body>
<script>
</script>
</html>