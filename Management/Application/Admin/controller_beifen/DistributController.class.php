<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace Admin\Controller;

use Think\Page;

class DistributController extends BaseController
{
    // 分销商列表
    public function distributor_list(){
        $User  = M('users');
        $count = $User->where('1=1')->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list  = $User->field('user_id,nick_name,user_money,frozen_money,distribut_money,underling_number')->order('distribut_money desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        $user_id_arr = get_arr_column($list, 'user_id');
        
        if (!empty($user_id_arr)) {
            $first_leader = M('users')->query("select first_leader,count(1) as count  from __PREFIX__users where first_leader in(" . implode(',', $user_id_arr) . ")  group by first_leader");
            $first_leader = convert_arr_key($first_leader, 'first_leader');
            
            $second_leader = M('users')->query("select second_leader,count(1) as count  from __PREFIX__users where second_leader  in(" . implode(',', $user_id_arr) . ")  group by second_leader");
            $second_leader = convert_arr_key($second_leader, 'second_leader');
            
            $third_leader = M('users')->query("select third_leader,count(1) as count  from __PREFIX__users where third_leader in(" . implode(',', $user_id_arr) . ")  group by third_leader");
            $third_leader = convert_arr_key($third_leader, 'third_leader');
        }
        $this->assign('first_leader', $first_leader);
        $this->assign('second_leader', $second_leader);
        $this->assign('third_leader', $third_leader);
        
        $this->assign('list', $list);// 赋值数据集
        $show = $Page->show();// 分页显示输出
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }
    
    public function tree(){
        $user_id = I('post.user_id', 0);
        $User    = M('users');
        if ($user_id) {
            $list = $User->field('user_id,nick_name')->where(['is_distribut' => 1, 'first_leader' => $user_id])->order('total_amount desc')->select();
        } else {
            $list = $User->field('user_id,nick_name')->where(['is_distribut' => 1, 'first_leader' => 0])->order('total_amount desc')->select();
        }
        $this->assign('list', $list);
        $this->display();
    }
    
    public function rebate_log(){
        $model       = M("rebate_log");
        $status      = I('status');
        $user_id     = I('user_id');
        $order_sn    = I('order_sn');
        $create_time = I('create_time');
        $create_time = $create_time ? $create_time : date('Y-m-d', strtotime('-1 year')) . ' - ' . date('Y-m-d', strtotime('+1 day'));
        
        $create_time2 = explode(' - ', $create_time);
        $where        = " store_id = " . STORE_ID . " and create_time >= '" . strtotime($create_time2[0]) . "' and create_time <= '" . strtotime($create_time2[1]) . "' ";
        
        if ($status === '0' || $status > 0)
            $where .= " and status = $status ";
        $user_id && $where .= " and user_id = $user_id ";
        $order_sn && $where .= " and order_sn like '%{$order_sn}%' ";
        
        $count = $model->where($where)->count();
        $Page  = new  \Think\Page($count, 10);
        $list  = $model->where($where)->order("`id` desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        //nick_name
        $get_user_id = get_arr_column($list, 'user_id'); // 获佣用户
      
        $buy_user_id = get_arr_column($list, 'user_id'); // 购买用户
        $user_id_arr = array_merge($get_user_id, $buy_user_id);
  
        if (!empty($user_id_arr))
            $user_arr = M('users')->where("user_id in (" . implode(',', $user_id_arr) . ")")->getfield('user_id,nick_name,sex',true);
     
        $this->assign('user_arr', $user_arr);
        
        $this->assign('create_time', $create_time);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }
    
    public function withdrawals(){
        $model = M('');
        $status = I('status');
        $user_id = I('user_id');
        $account_bank = I('account_bank');
        $account_name = I('account_name');
        $create_time = I('create_time');
        $create_time = str_replace("+"," ",$create_time);
        $create_time2 = $create_time  ? $create_time  : date('Y-m-d',strtotime('-1 year')).' - '.date('Y-m-d',strtotime('+1 day'));
        $create_time3 = explode(' - ',$create_time2);

        $where['w.create_time'] =  array(array('gt', strtotime(strtotime($create_time3[0])), array('lt', strtotime($create_time3[1]))));
        if($status === '0' || $status > 0)
            $where['w.status'] = $status;
        $user_id && $where['u.user_id'] = $user_id;
        $account_bank && $where['w.account_bank'] = array('like','%'.$account_bank.'%');
        $account_name && $where['w.account_name'] = array('like','%'.$account_name.'%');

        $count = $model->table(C('DB_PREFIX').'withdrawals w')->join('INNER JOIN __USERS__ u ON u.user_id = w.user_id')->where($where)->count();
        $Page  = new  Page($count,2);
        $list = $model->table(C('DB_PREFIX').'withdrawals w')->join('INNER JOIN __USERS__ u ON u.user_id = w.user_id')->where($where)->order("w.id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('create_time',$create_time2);
        $show  = $Page->show();
        $this->assign('show',$show);
        $this->assign('list',$list);
        C('TOKEN_ON',false);
        $this->display();
    }
    
    public function ajax_lower(){
        $id   = I('get.id', 0, 'intval');
        $user = M('users')->where(['is_distribut' => 1, 'first_leader' => $id])->field('user_id,nick_name')->select();
        $str  = '';
        $str  .= "<ul>";
        foreach ($user as $key => $val) {
            $str .= "<li><span class='tree_span' data-id='{$val['user_id']}'>" . " <i class='icon-folder-open'></i>  {$val['user_id']}:   {$val['nick_name']}   </span>   </li> ";
        };
        $str .= "</ul>";
        
        echo $str;
    }
}