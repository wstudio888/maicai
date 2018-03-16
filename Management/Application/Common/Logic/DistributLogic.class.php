<?php

namespace Common\Logic;

use Think\Model\RelationModel;

/**
 * 分销逻辑
 * User: Administrator
 * @author xxx
 */
class DistributLogic
{
    // 生成分成记录
    public function rebate_log($order){
        $user_id     = $order['user_id']; //下单用户id
        $goods_price = $order['goods_price']; //下单金额不包含邮费
        $order_sn    = $order['order_sn']; //订单号
        $store_id    = $order['store_id']; //店铺id
        $Model       = M();
        
        $user = M('users');
        //查询下单会员信息
        $user_info = $user->where(['user_id' => $user_id])->field(['user_id', 'nick_name', 'first_leader', 'second_leader', 'third_leader'])->find();
        //查询店铺是否开启分销模式
        $store_distribut = M('store_distribut')->where(['store_id' => $store_id])->find();
        if (!$store_distribut['switch']) return false;  //未开启分销功能
        
        //查询下单用户
        $data                = array();
        $data['nickname']    = $user_info['nick_name'];
        $data['buy_user_id'] = $user_info['user_id'];
        $data['order_sn']    = $order_sn;
        $data['order_id']    = $order['order_id'];
        $data['goods_price'] = $goods_price;
        $data['create_time'] = time();
        $data['status']      = 0;
        $data['store_id']    = $store_id;
        
        //一级
        if (!empty($user_info['first_leader'])) {
            $res = $user->where(['user_id' => $user_info['first_leader']])->field(['nick_name'])->find();
            if ($res) {
                $data['level']   = 1;
                $data['money']   = ($store_distribut['first_rate'] / 100) * $goods_price;  // 分销比例除以100 乘以 商品价格
                $data['user_id'] = $user_info['first_leader']; // 获佣用户
                $update          = "UPDATE __PREFIX__users SET d_money = d_money + {$data['money']} WHERE user_id = {$data['user_id']}";  // 添加未结算金额
                $Model->execute($update);
                $this->add_log($data);
            }
        }
        //二级
        if (!empty($user_info['second_leader'])) {
            $data['level']   = 2;
            $data['money']   = ($store_distribut['second_rate'] / 100) * $goods_price;  // 分销比例除以100 乘以 商品价格
            $data['user_id'] = $user_info['second_leader']; // 获佣用户
            $update          = "UPDATE __PREFIX__users SET d_money = d_money + {$data['money']} WHERE user_id = {$data['user_id']}";  // 添加未结算金额
            $Model->execute($update);
            $this->add_log($data);
        }
        //三级
        if (!empty($user_info['third_leader'])) {
            $data['level']   = 3;
            $data['money']   = ($store_distribut['third_rate'] / 100) * $goods_price;  // 分销比例除以100 乘以 商品价格
            $data['user_id'] = $user_info['third_leader']; // 获佣用户
            $update          = "UPDATE __PREFIX__users SET d_money = d_money + {$data['money']} WHERE user_id = {$data['user_id']}";  // 添加未结算金额
            $Model->execute($update);
            $this->add_log($data);
        }
        return true;
    }
    
    // 添加分成记录
    private function add_log($data){
        $res = M('rebate_log')->add($data);
        return $res;
    }
    
    public function auto_confirm($store_id){
        // 确认收货多少天后 自动结算给 商家
        $today_time = time();
        $pay_date   = tpCache('shopping.pay_date');
        $pay_date   = $pay_date * (60 * 60 * 24); // 1天的时间戳
        
        $sql = "select id,user_id,money from __PREFIX__rebate_log where store_id = $store_id and status = 2 and (($today_time - confirm ) >  $pay_date) ";
        
        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $list  = $Model->query($sql);
        
        if (empty($list)) // 没有数据直接跳出
            return false;
        
        $data = array(
            'status' => 3, // 结算截止时间
            'confirm_time' => time(), // 确定分成时间
        );
        
        foreach ($list as $key => $val) {
            // 修改分销信息
            M('rebate_log')->where(['id' => $val['id'], 'store_id' => $store_id])->save($data);
            $info = array();
            // 修改金额
            $update = "UPDATE __PREFIX__users SET out_money = out_money + {$val['money']} , d_money = d_money - {$val['money']} WHERE user_id = {$val['user_id']}";
            
            $Model->execute($update);
        }
    }
}