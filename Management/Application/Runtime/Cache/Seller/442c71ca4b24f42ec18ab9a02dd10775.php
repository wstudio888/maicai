<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>商家管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap 3.3.4 -->
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
 	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="/Public/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="/Public/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/Public/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" /> 
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->   
    <!-- jQuery 2.1.4 -->
    <script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/Public/js/global.js"></script>
    <script src="/Public/js/upgrade.js"></script>
	<script src="/Public/js/layer/layer.js"></script><!--弹窗js 参考文档 http://layer.layui.com/--> 
    <style type="text/css">
    	#riframe{min-height:inherit !important}
		.tpmenu{float:left;padding-left:100px;height:50px;}
		.menu_list{}
		.menu_list li{list-style:none;width:30px;height:48px;float:left;}
    </style>
  </head>
<body class="skin-green-light sidebar-mini" style="overflow-y:hidden;">
<div class="wrapper">
  <header class="main-header">
      <!-- Logo -->
      <a href="/index.php/Seller/Index/index" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="/Public/images/TP-shop_logo.png" width="40" height="30">&nbsp;&nbsp;<b>商家版</b></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">        
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
          <?php if($upgradeMsg[0] != null): ?><li>
                  <a href="javascript:void(0);" id="a_upgrade">
                      <i class="glyphicon glyphicon-upload"></i>
                      <span  style="color:#FF0;"><?php echo ($upgradeMsg["0"]); ?>&nbsp;</span>
                  </a>
               </li><?php endif; ?>
		   <li>
              <a href="javascript:void(0);">            
                  <span></span>
               </a>
		   </li>              
           <li>
              <a href="/index.php" target="_blank">
                  <i class="glyphicon glyphicon-home"></i>
                  <span>网站前台</span>
              </a>
           </li>
           <li>
               <a href="<?php echo U('/Seller/Admin/cleanCache');?>">
                   <i class="glyphicon glyphicon glyphicon-refresh"></i>
                   <span>清除缓存</span>
               </a>
           </li>      
           <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!--<img src="/Public/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                <i class="glyphicon glyphicon-user"></i>
                <span class="hidden-xs">欢迎：<?php echo ($seller["seller_name"]); ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-footer">
                  <div class="pull-left">
                  	<a href="<?php echo U('Home/Store/index',array('store_id'=>STORE_ID));?>" data-url="" target="_blank" class="btn btn-default btn-flat">店铺首页</a>
                   	<a href="<?php echo U('Admin/admin_info',array('seller_id'=>$seller['seller_id']));?>" target="rightContent" class="btn btn-default btn-flat">修改密码</a>
                   	<a href="<?php echo U('Admin/logout');?>" class="btn btn-default btn-flat">安全退出</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-street-view"></i>换肤</a></li>
          </ul>
        </div>
     </nav>
</header> 

<aside class="main-sidebar" style="overflow-y:auto;">
      <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </form>
        <!-- /.search form -->
        <ul class="sidebar-menu"> 
	      <?php if(is_array($menu_list)): foreach($menu_list as $k=>$vo): ?><li class="treeview">
        	    <a href="javascript:void(0)">
	              <i class="fa <?php echo ($vo["icon"]); ?>"></i><span><?php echo ($vo["name"]); ?></span><i class="fa fa-angle-left pull-right"></i>
	            </a>
	            <ul class="treeview-menu">
	            	<?php if(is_array($vo["child"])): foreach($vo["child"] as $kk=>$vv): ?><li onclick="makecss(this)" data-id="<?php echo ($vv["act"]); ?>_<?php echo ($vv["op"]); ?>"><a href='<?php echo U("$vv[op]/$vv[act]");?>' target='rightContent'><i class="fa fa-circle-o"></i><?php echo ($vv["name"]); ?></a></li><?php endforeach; endif; ?>
	            </ul>
        	</li><?php endforeach; endif; ?>     
          </ul>
      </section>
</aside>

<section class="content-wrapper right-side" id="riframe" style="margin:0px;padding:0px;margin-left:230px;">
    <iframe id='rightContent' name='rightContent' src="<?php echo U('Seller/Index/welcome');?>" width='100%' frameborder="0"></iframe>
</section>

	<footer class="main-footer">
	   <div class="pull-right hidden-xs">
	    	 感谢使用ThinkPHP 开源系统<b></b>
	   </div>
	   
	</footer>

     <!-- Control Sidebar -->
     <aside class="control-sidebar control-sidebar-dark">
       <!-- Create the tabs -->
       <!--
       <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
         <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-at"></i></a></li>
         <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
       </ul>
       -->
       <!-- Tab panes -->
       <div class="tab-content">
      	<!-- Home tab content -->
         <div class="tab-pane" id="control-sidebar-home-tab">
         </div><!-- /.tab-pane -->
         <!-- Stats tab content -->
         <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
         <!-- Settings tab content -->
         <div class="tab-pane" id="control-sidebar-settings-tab">
         </div>
       </div>
     </aside>
   <div class="control-sidebar-bg"></div>
</div>

<script src="/Public/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/Public/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/Public/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<script src="/Public/dist/js/app.js" type="text/javascript"></script>
<script src="/Public/dist/js/demo.js" type="text/javascript"></script>
 
<script type="text/javascript">
$(document).ready(function(){
	$("#riframe").height($(window).height()-100);//浏览器当前窗口可视区域高度
	$("#rightContent").height($(window).height()-100);
	$('.main-sidebar').height($(window).height()-50);
});

var tmpmenu = 'index_Index';
function makecss(obj){
	$('li[data-id="'+tmpmenu+'"]').removeClass('active');
	$(obj).addClass('active');
	tmpmenu = $(obj).attr('data-id');
}

function callUrl(url){
	layer.closeAll('iframe');
	rightContent.location.href = url;
}
</script>
<!-- 新订单提醒-s -->
<script type="text/javascript">
function closes(){
       is_close = 1;
	document.getElementById('ordfoo').style.display = 'none';
}
	
$(document).ready(function(){    
	// 没有点击收货确定的按钮让他自己收货确定    
	var timestamp = Date.parse(new Date());
	$.ajax({
         type:'post',
         url:"<?php echo U('Seller/Admin/login_task');?>",
         data:{timestamp:timestamp},
         timeout : 100000000, //超时时间设置，单位毫秒
         success:function(){
             // 执行定时任务
         }
    }); 
});
</script>
<!-- 新订单提醒-e -->
</body>
</html>