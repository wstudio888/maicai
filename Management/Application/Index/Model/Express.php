<?php

namespace app\home\model;

use think\Db;
use think\Model;


/**
 * 我的订单模型
 * User: Administrator
 * @author xxx
 */
class Express extends Model
{
	/**
     * 当天配送情况查询
     * @param  string  $uid 用户id
	 * @param  string  $msg 当天查询条件
     * @return integer 返回配送情况数组
     */
    public function get_order($uid) {

    	$rs['data'] = $this
             ->where(['courier_id'=> $uid])
             ->field("express_id,courier_id,create_time,sole_money,hold_give,status")
             ->order('express_id')
             ->select();
    	$rs['int']['num'] = $this->get_ordernum($uid);
        $rs['int']['money'] = $this->get_moneynum($uid);
    	return $rs;
    }


    /**
     * 当天配送总数统计
     * @param  string  $msg 当天查询条件
     * @return integer 返回总数
     */
    private function get_ordernum($uid) { 
    	$num = $this ->where(['courier_id' => $uid]) ->count('courier_id', $uid);
    	return $num;
    }
    

    /**
     * 当天配送总金额统计
     * @param  string  $msg 当天查询条件
     * @return integer 返回总数
     */
    private function get_moneynum($uid) {
        $money = $this ->where(['courier_id' => $uid]) ->sum('sole_money');
        return $money;
    }

    /**
     * 快递远接收订单
     * @return integer 返回对错
     */
    public function inExpress($data) {
        $rs = $this->insert($data);
        if($rs){
           $where['order_id'] = $data['express_id'];
           $arr['courier_qudan'] = 1; 
           $arr['courier_id'] = $data['courier_id'];
           $res = $this->update_status('order',$where,$arr);
           if($res) {
            return $rs; 
           }

        }    
           
    }

    /**
     * 跟新订单状态
     * @param  string  $name 表名称
     * @param  string  $where 条件数组
     * @param  string  $data 跟新数组
     * @return integer 返回成功与否
     */
    public function update_status($name,$where,$data) {
     $res = Db::name($name)->where($where)->update($data);
     return $res;
    }

}