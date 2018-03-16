<?php

namespace Index\model;

use Think\Db;
use Think\Model;


define('UC_AUTH_KEY', 'UC_AUTH_KEY');

/**
 * 用户模型
 * User: Administrator
 * @author xxx
 */
class CourierModel extends Model
{

    /**
     * 用户登录认证
     * @param  string  $user_name 用户名
     * @param  string  $user_pwd 用户密码
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($user_name, $user_pwd){
        ///<$nXbx95>ErKjehs:I0LNMGoS;#-ORDqBJzU"T+
        /* 获取用户数据 */
        $map['courier'] = $user_name;
        $user = $this->where($map)->find();

        if ($user && $user['status']) {
            /* 验证用户密码 */

            if (md5($user_pwd) === $user['courier_pwd']) {
                $this->updateLogin($user['courier_id'],$user['logins']); //更新用户登录信息
                return $user['courier_id']; //登录成功，返回用户ID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

     /**
     * 更新用户登录信息
     * @param  integer $courier_id 用户ID
     */
    protected function updateLogin($courier_id,$logins){
        $data = array(
            'last_login' => time(),
            'logins' => $logins.'+1'
        );
        $this->where(['courier_id' => $courier_id])->update($data);
    }

    /**
     * 查询用户信息
     * @param  integer $user 用户信息数组
     */
    public function getuser($courier_id) {
        /* 查询用户信息 */
        $user = $this->field(true)->find($courier_id);
        $courier_img = '/template/home/images/userimg.png';
        $user['courier_img'] = $user['courier_img'] ? $user['courier_img']:$courier_img;
        $user['courier_address_s'] = mb_substr($user['courier_address'],0,15,'utf-8');
        return $user;
    }

    /**
     * 更新用户信息
     * @param  integer $courier_id 用户ID
     */
    public function updateuser($courier_id,$data){
        $rs = $this->where(['courier_id' => $courier_id])->update($data);
        return $rs;
    }

    /**
     * 余额提现申请
     * @param  integer $courier_id 用户ID
     */
    public function getmoney($courier_id){
        $user = $this->getuser($courier_id);

        $data['courier_id'] = $courier_id;
        $data['money_x'] = $user['money'];
        $data['time_now'] = time();
        $data['status_x'] = 1; 
        $data['courier_wx'] = $user['courier_wx'];  
        $data['courier_name'] = $user['courier_name'];  
        $data['bank_name'] = $user['bank_name']; 
        $data['card_no'] = $user['card_no']; 

        $rs = Db::name('courier_money')->insert($data);
        if($rs){
            $money['money'] = 0;
            $rs = $this->where(['courier_id' => $courier_id])->update($money);
            if($rs) {
                return -3;
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    /**
     * 订单完成添加金额
     * @param  integer $courier_id 用户id
     */
    public function inset_money($order_id){
        $courier_id = session('courier.courier_id');
        $money = Db::name('order')-> where(['order_id' => $order_id]) -> field('shipping_price') -> find();
        $rs = Db::name('courier')-> where(['courier_id' => $courier_id]) -> setInc('money', $money['shipping_price']);
        return $rs;
    }

    /**
     * 记录session保持登录
     * @param  integer $courier_id 用户id
     */
    public function autoLogin($courier_id){

        $courier = $this->getuser($courier_id);
        /* 记录登录SESSION和COOKIES */
        $arr = array(
            'courier_id' => $courier['courier_id'],
            'courier_name' => $courier['courier_name'],
            'courier_img' => $courier['courier_img'],
            'last_login' => $courier['last_login']
        );
        
        session('courier', $arr); 
        return true;       
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('courier', null);
    }

}