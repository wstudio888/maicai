<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace WXAPI\Controller;

class DistributionController extends BaseController
{
    
    // 佣金申请提现
    public function submitmoney(){
        $object = file_get_contents('php://input');
        $_POST  = (json_decode($object, true));
        
        $this->user_id = $_POST['user_id'];
        
        if (!$this->user_id)
            exit(json_encode(array('status' => -1, 'msg' => '参数有误', 'result' => '')));
        
        // 查询这个用户
        $userinfo = M('users')->where(['user_id' => $this->user_id])->field(['out_money', 'y_money'])->find();
        if ($userinfo) {
            if ($userinfo['out_money'] < 1) {
                exit(json_encode(array('status' => -1, 'msg' => '没有可提现的佣金', 'result' => '')));
            }
            
            $subtype = $_POST['subtype'];
            $data    = array();
            
            if ($subtype == 1) { //提现到微信
                $data                 = array();
                $data['bank_name']    = '微信';
                $data['account_bank'] = $_POST['account_bank'];
                $data['status']       = 0;
                $data['money']        = $userinfo['out_money'];
                $data['create_time']  = time();
                $data['user_id']      = $this->user_id;
                
                if (!$data['account_bank']) exit(json_encode(array('status' => -1, 'msg' => '微信号不能为空', 'result' => '')));
            } else if ($subtype == 2) { //提现到银行卡
                $data                 = array();
                $data['bank_name']    = $_POST['bank_name'];
                $data['account_name'] = $_POST['account_name'];
                $data['account_bank'] = $_POST['account_bank'];
                $data['status']       = 0;
                $data['money']        = $userinfo['out_money'];
                $data['create_time']  = time();
                $data['user_id']      = $this->user_id;
                if (!$data['account_bank'] || !$data['account_name'] || !$data['account_bank']) exit(json_encode(array('status' => -1, 'msg' => '微信号不能为空', 'result' => '')));
            }
            $res = M('withdrawals')->add($data);
            if ($res !== false) {
                // 减去提交金额,添加已申请佣金。
                $sql = "UPDATE __PREFIX__users SET out_money = out_money - {$data['money']},y_money = y_money + {$data['money']} WHERE user_id = $this->user_id ";
                M()->execute($sql);
                exit(json_encode(array('status' => 200, 'msg' => '提交申请成功', 'result' => '')));
            }
        } else {
            exit(json_encode(array('status' => -1, 'msg' => '没有可提现的佣金', 'result' => '')));
        }
    }
    
    //
    public function money(){
        $this->user_id = I('user_id', 0);
        if (!$this->user_id)
            exit(json_encode(array('status' => -1, 'msg' => '参数有误', 'result' => '')));
        
        // 查询这个用户
        $userinfo = M('users')->where(['user_id' => $this->user_id])->field('out_money,y_money')->find();
        if ($userinfo) {
            exit(json_encode(array('status' => 200, 'msg' => '', 'result' => $userinfo)));
        } else {
            exit(json_encode(array('status' => -1, 'msg' => '没有可提现的佣金', 'result' => '')));
        }
    }
}