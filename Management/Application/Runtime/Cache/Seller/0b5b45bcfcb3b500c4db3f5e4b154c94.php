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

     <section class="content">
		<div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-bell"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">待处理订单</span>
                  <span class="info-box-number"><?php echo ($count["handle_order"]); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">商品数量</span>
                  <span class="info-box-number"><?php echo ($count["goods_sum"]); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">文章数量</span>
                  <span class="info-box-number"><?php echo ($count["article"]); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-cny"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">今日销售总额</span>
                  <span class="info-box-number"><?php if($count['total_amount'] > 0): echo ($count["total_amount"]); else: ?>0<?php endif; ?></span>
                </div>
              </div>
            </div>
         </div>
          <div class="row">
          	     <div class="col-md-12">
			       	 <div class="box  box-primary">
                        <div class="box-body">
                            <div class="info-box">                 
           						<div class="row">
           							<div class="col-md-3">
           								<div><img alt="" height="80" src="<?php if(empty($store["store_logo"])): ?>/Public/images/not_adv.jpg<?php else: echo ($store["store_logo"]); endif; ?>">
           									<br/>
           									<a href="<?php echo U('Store/store_setting');?>"><i></i>编辑店铺设置</a>
           								</div>
           							</div>
           							<div class="col-md-3">
           								<p><b><?php echo ($seller["seller_name"]); ?></b>(用户名:<?php echo ($seller["user_name"]); ?>)</p>
           								<p>管理权限：管理员</p>
           								<p>店铺名称：<a href="<?php echo U('Home/Store/index',array('store_id'=>$store[store_id]));?>" target="_blank"><?php echo ($store["store_name"]); ?></a></p>
           							</div>
           							<div class="col-md-3">
           							    <p>最后登录：<?php echo (date('Y-m-d H:i',$seller["last_login_time"])); ?></p>
           								<p>店铺等级：默认等级</p>
           								<p>有限期：<?php if($store[store_state] == 1): ?>已关闭<?php else: ?>不限制<?php endif; ?></p>
           							</div>
           						</div>
                            </div>
                        </div>
                    </div>
			    </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-header with-border  bg-aqua">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">待处理</h3>
                </div>
                <div class="box-body">
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="<?php echo U('Goods/goodsList',array('goods_state'=>0));?>">待审核商品</a></h4>
			                   <a href="javascript:;"><?php echo ($count["verify_goods"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">未处理佣金</a></h4>
			                   <a href="javascript:;">0个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="<?php echo U('Comment/ask_list');?>">待回复咨询</a></h4>
			                   <a href="javascript:;"><?php echo ($count["consult"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="<?php echo U('Comment/index');?>">新增商品评论</a></h4>
			                   <a href="javascript:;"><?php echo ($count["comment"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
                </div>
              </div>
            </div>            
            <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-header with-border  bg-aqua">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">商品<?php echo ($count["goods_sum"]); ?>件</h3>
                </div>
                <div class="box-body">
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="<?php echo U('Goods/goodsList',array('goods_state'=>1));?>">审核通过商品</a></h4>
			                   <a href="javascript:;"><?php echo ($count["pass_goods"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">库存警告商品数</a></h4>
			                   <a href="javascript:;"><?php echo ($count["warning_goods"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">新品推荐数</a></h4>
			                   <a href="javascript:;"><?php echo ($count["new_goods"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">促销商品数</a></h4>
			                   <a href="javascript:;"><?php echo ($count["prom_goods"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
	                 <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">已下架商品数</a></h4>
			                   <a href="javascript:;"><?php echo ($count["off_sale_goods"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="<?php echo U('Goods/goodsList',array('goods_state'=>2));?>">审核未通过商品数</a></h4>
			                   <a href="javascript:;"><?php echo ($count["below_goods"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
                </div>
              </div>
            </div>           
            <div class="col-md-4">
              <div class="box box-solid">
                <div class="box-header with-border  bg-aqua">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">订单<?php echo ($count["order_sum"]); ?>笔</h3>
                </div>
                <div class="box-body">
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">待发货订单</a></h4>
			                  <a href="javascript:;"><?php echo ($count["wait_shipping"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">待支付订单</a></h4>
			                  <a href="javascript:;"><?php echo ($count["wait_pay"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">待确认订单</a></h4>
			                  <a href="javascript:;"><?php echo ($count["wait_confirm"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">退款申请</a></h4>
			                   <a href="javascript:;"><?php echo ($count["refund_pay"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
	                  <div class="row" style="margin-top:20px;">
	                    <div class="col-xs-6">
			              <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">部分发货订单</a></h4>
			                  <a href="javascript:;"><?php echo ($count["part_shipping"]); ?>个</a>
			                </div>
			              </div>
	                    </div>
	                    <div class="col-xs-6">                   
			               <div class="small-box">
			                <div class="inner text-center">
			                  <h4><a href="#">退货申请</a></h4>
			                   <a href="javascript:;"><?php echo ($count["refund_goods"]); ?>个</a>
			                </div>
			              </div>
	              		</div>
	                  </div>
                </div>
              </div>
            </div>
            
         </div>
          
     </section>
 </div>
 </body>
 </html>