<?php
/**
 * order: Administrator
 * @author xxx
 */

namespace Index\Controller;

use Think\Db;
use Index\Model\ExpressModel;
use Index\Model\CourierModel;
use Index\Model\OrderModel as OrderModel;

class OrderController extends BaseController
{
	/*
	*	状态3 配送中
	*	状态4 已完成	
	*/
	public function index() {
		$status = input('status',0);
		$Order = new OrderModel();
		$rs = $Order -> get_express($status);

		$this->assign('rs', $rs);
		$this->assign('status', $status);
		return $this->fetch();
	}

	/*结算记录*/
	public function bill() {
		$uid = session('courier.courier_id');
		$Courier = (new Courier)->getuser($uid);
		$rs = (new Express)->get_order($uid);

		$this->assign('Courier', $Courier);
		$this->assign('uid', $uid);
		$this->assign('rs', $rs);
		return $this->fetch();
	}
	



	/*
	*	状态0 未抢单
	*	状态1 配送中	
	*	状态2 已完成
	*	格外状态3 等待取货
	*	
	*//* 快递员行为*/
	public function action() {
		$status = input('status',0);
		$OrderModel = new OrderModel;
		$where = $OrderModel -> status_b($status,session('courier.courier_id'));
		$rs = $OrderModel -> get_order($where);
	
		$this->assign('status', $status);
		$this->assign('rs', $rs);
		return $this->fetch();
	}


	/* 接单抢单 */
	public function jxdj() {
		if($order_id = input('order_id',0)) {
			$data['courier_id'] = session('courier.courier_id');
			$data['express_id'] = $order_id;
			$data['sole_money'] = input('shipping_price',0);
			$data['create_time'] = time();
			$data['hold_give'] = 1;
			$data['status'] = 1;

			if((new Express) -> inExpress($data)) {
				$this->success('抢单成功！等待取货', url('Index/index'));
			} else {
				$this->error('抢单失败');
			}
		}
	}

	/* 取货确认 */
	public function quhuo() {
		if($order_id = input('order_id',0)) {
			$express = new Express;
			$where['order_id'] = $order_id;
			$data['shipping_status'] = 1;
			$data['courier_qudan'] = 2;
			$res = $express -> update_status('order',$where,$data);
			if($res || $rev) {
				$this->success('取货成功！请尽快配送', url('Index/index'));
			} else {
				$this->error('取单失败');
			}
		}
	}

	/* 送达 */
	public function over() {
		if($order_id = input('order_id',0)) {
			$where['order_id'] = $order_id;
			$data['order_status'] = 2;
			$data['courier_qudan'] = 3;
				$express = new Express;
				$res = $express -> update_status('order',$where,$data);
			$where1['express_id'] = $order_id;
			$data1['status'] = 2; 
			$data1['over_time'] = time();
				$rev = $express -> update_status('express',$where1,$data1);
			if($res || $rev) {				
				$rev = (new Courier) -> inset_money($order_id);
				if($rev) {
					$this->success('送达成功！辛苦了', url('Index/index'));
				} else {
					$this->error('邮费接收失败');
				}
			} else {
				$this->error('确认失败');
			}
		}
	}

	

}