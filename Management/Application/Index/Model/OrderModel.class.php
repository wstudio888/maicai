<?php

namespace Index\Model;

use Think\Db;
use Think\Model;


/**
 * 订单模型
 * User: Administrator
 * @author xxx
 */
class OrderModel extends Model
{    
	/**
	 * 订单状态判断
	 * 状态0 未抢单/未发货
	 * 状态1 配送中
	 * 状态2 已完成
	 * 格外状态3 等待取单
     * @param  int $status 状态条件
     * @param  int $courier_id 快递员id
     * @return array 返回状态数组
     */
	public function status_b( $status,$courier_id){
		if ($status == 0) {
			$where['shipping_status'] = 0;
			$where['courier_qudan'] = ['<',1];
			$where['order_status'] = ['<',2];	
            $where['pay_status'] = 1;
		} elseif ($status == 1) {
			$where['courier_id'] = $courier_id;
			$where['shipping_status'] = 1;
			$where['order_status'] = ['<',2];	
			$where['courier_qudan'] = 2;

		} elseif ($status == 2) {
			$where['courier_id'] = $courier_id;
			$where['order_status'] = ['>=',2];
			$where['courier_qudan'] = 3;

		}  elseif ($status == 3){
			$where['courier_id'] = $courier_id;
			$where['shipping_status'] = ['<',1];
			$where['courier_qudan'] = 1;
			$where['order_status'] = ['<',2];	
		}
		return $where;
	}

	/**
	 * 客户订单订单查
     * @param  array $where 查询条件
     * @return integer 返回配送情况数组
     */
    public function get_order($where){
    	$rs = $this
             ->alias('a')
             ->join('store w','a.store_id = w.store_id')
    		 ->where($where)
             ->field("a.order_id,a.order_sn,a.consignee,a.country,a.province,a.city,a.district,a.twon,a.address,a.mobile,w.store_address,a.shipping_status,a.add_time,a.shipping_price")	
             ->order('order_id')
             ->select();
        return $rs;
   	}

	/**
	 * 我的订单查询
     * @param  int $status 状态条件
     * @return integer 返回配送情况数组
     */
    public function get_express($status){
    	$courier_id = session('courier.courier_id');
    	if ($status == 2) {
    		$where['w.status'] = 1;
    	} else {
    		$where['w.status'] = 2;
    	}
    	    $where['a.courier_id'] = $courier_id;
    	$rs = $this
    		 ->alias('a')
			 ->join('express w','a.order_id = w.express_id')
			 ->where($where)
             ->field("a.order_id,a.order_sn,a.consignee,a.address,w.status")	
             ->order('w.status , w.express_id ')
             ->select();
        return $rs;
   	}

	/**
	 * 我的订单查询
     * @param  int $status 状态条件
     * @return integer 返回配送情况数组
     */
    public function order_find($order_id,$status){
/*      if($status == 0 || $status == 3)｛
            
        } elseif ( ) {

        }*/
        $where['order_id'] = $order_id;
        //后期加入商家地址后 加查询 field
    	$address['user_address'] = $this->where($where)->field('address,city,store_id')->find();
        $address['store_address'] = $this->store_address($address['user_address']['store_id']);

    	return $address;
    }


    /**
     * 商家地址查询
     * @param  int $status 状态条件
     * @return integer 返回配送情况数组
     */
     public function store_address($store_id){
        $store_address = Db::name('store')
                    ->where(['store_id' => $store_id])
                    ->field("store_address")
                    ->find();
        return $store_address;
     }



    /**
     * 当天待取货总数统计
     * @return integer 返回总数
     */
    public function get_num($courier_id) { 

        $where = $this -> status_b(3 , $courier_id);
        $where1 = $this -> status_b(1 , $courier_id);
        $num['wait'] = $this ->where($where) ->count('courier_id', $courier_id);
        $num['process'] = $this ->where($where1) ->count('courier_id', $courier_id);
        return $num;
    }
    
    
    public function get_order_tow() { 
        $rs = $this
             ->alias('a')
             ->join('store w','a.store_id = w.store_id')
             ->field("a.order_id,a.address,w.store_address")    
             ->order('order_id')
             ->limit(1)
             ->find();
        return $rs;
    }


}