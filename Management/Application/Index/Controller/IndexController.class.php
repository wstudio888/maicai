<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace Index\Controller;

use Think\Db;
use Index\Model\CourierModel;
use Index\Model\OrderModel as OrderModel;

/**
 * Index
 * User: Administrator
 * @author xxx
 */
class IndexController extends BaseController
{
	/*首页*/
	public function index(){
		$courier_id = session('courier.courier_id');
		$OrderModel  = new OrderModel;

		$courier = (new CourierModel)->getuser($courier_id);
		$num = $OrderModel->get_num($courier_id);
		$address = $OrderModel -> get_order_tow();
		
		$this->assign('address', $address);
		$this->assign('num', $num);
		$this->assign('courier', $courier);
		
		$this->display();
	}



	/*搜索页面*/
	public function sosuo(){
		if($sosuo = input('sosuo',0)) {
			$where['order_sn'] = $sosuo;
			$rs = (new OrderModel) -> get_order($where);
			
			$this->assign('rs', $rs);
			$this->display();
		}
	}

	/*用户中心*/
	public function userinfo(){
		$uid = session('courier.courier_i');
		$Courier = (new Courier)->getuser($uid);
		
		$this->assign('courier', $Courier);
		$this->display();
	}

	/*编辑用户资料*/
	public function user(){
		$uid = session('courier.courier_id');
		$courier = (new Courier)->getuser($uid);
		if ($data = $_GET) {
			$rs = (new Courier)->updateuser($uid,$data);
			if( $rs ){
				return 1;
			} else {
				return 2;
			}
		}		
		
		$this->assign('courier', $courier);
		$this->display();
	}

	/*查看路线地图*/		
	public function map(){	

		if($order_id = input('order_id',0)) {
			$status = input('status',0);

			//$address_s = input('address_s',0);

			$address = (new OrderModel) -> order_find($order_id,$status);

		$this->assign('status', $status);
		$this->assign('address', $address);	
		//$this->assign('address_s', $address_s);	
		$this->display();
		}
	}

	/*余额提现*/		
	public function money(){
		if($courier_id = input('courier_id',0)) {
			$rs = (new Courier)->getmoney($courier_id);
			if($rs == -3) {
				 $this->success('申请成功！', url('Index/userinfo'));
			} elseif($rs == -2) {
				$this->error('申请失败');
			} elseif($rs == -1) {
				$this->error('账户余额为零');
			}

		}
	}

	/*实时获取订单信息*/		
	public function getorder(){

		    if(empty($_POST['time']))exit();        
		    set_time_limit(0);//无限请求超时时间        
		    $i=0;
			while (true){        
		        //sleep(1);        
		        usleep(50000000);//0.5秒        
		        $i++;        

		        //若得到数据则马上返回数据给客服端，并结束本次请求        
		        $rand=rand(1,999);        
		        if($rand<=15){        
		            $arr=array('success'=>"1",'name'=>'xiaoyu','text'=>$rand);        
		            echo json_encode($arr);        
		            exit();        
		        }

		        //服务器($_POST['time']*0.5)秒后告诉客服端无数据        
		        if($i==$_POST['time']){        
		            $arr=array('success'=>"0",'name'=>'xiaoyu','text'=>$rand);        
		            echo json_encode($arr);        
		            exit();        
		        }        
		    }     
	}



	/*经纬度定位*/
/*	public function mapmap(){
		if($order_id = input('order_id',0) && $status = input('status',0)) {
		   
		$this->assign('status', $status);
		$this->assign('order_id', $order_id);	
		}
		return $this->fetch();
	}
*/
	/*地址定位*/
/*	public function address(){
		if($lng = input('lng',0)) {
			$lat = input('lat',0);
			$order_id = input('order_id',0);
			$status = input('status',0);

		$this->assign('status', $status);	
		$this->assign('order_id', $order_id);	
		$this->assign('lng', $lng);	
		$this->assign('lat', $lat);	
		return $this->fetch();
		}

	}*/

}