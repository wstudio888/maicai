<?php

namespace Index\Controller;

use Think\Cache;
use Think\Config;
use Think\Controller;
use Think\Db;

use Index\Model\CourierModel;
/**
 * 基础控制器
 * User: Administrator
 * @author xxx
 */
class LoginController extends Controller {
	
	/* 用户登录 */
	 public function login($username = null, $password = null, $verify = null){
        if (request()->isPost()) {

            // 验证数据
            $data   = $this->request->post();
            /* 调用User登录接口登录 */
            $Courier = new Courier();
            $uid  = $Courier->login($data['user_name'], $data['user_pwd']);
            if (0 < $uid) {
                /* 登陆用户 */
                if ($Courier->autoLogin($uid)) { //登录用户
                    $this->success('登录成功！', url('Index/index'));
                } else {
                    $this->error('登录失败');
                }
            } else {
                switch ($uid) {
                    case -1:
                        $error = '用户不存在或被禁用！';
                        break; //系统级别禁用
                    case -2:
                        $error = '密码错误！';
                        break;
                    default:
                        $error = '未知错误！';
                        break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }

        } else {
            if (is_login()) {
                $this->redirect('Index/index');
            }
            return $this->fetch();
        }
    }
    
    /* 退出登录 */
    public function logout(){
        if (is_login()) {
            (new Courier)->logout();
            session('[destroy]');
            $this->redirect('Login/login');
        } else {
            $this->redirect('Login/login');
        }
    }
    

    /*修改密码*/
    public function ds(){
       
         if (request()->isPost()) {
            
            
         }
       
    }

     /**
     * 修改管理员密码
     * @return \think\mixed
     */
    public function pwd(){
        $uid = session('courier.courier_id');
        $courier = (new Courier)->getuser($uid);       

         if($this->request->isPost()){
            $old_pwd = $this->request->post('old_pwd', '');
            $new_pwd = $this->request->post('new_pwd', '');
            $new2_pwd = $this->request->post('new2_pwd', '');
            $courier_id = $this->request->post('courier_id', '');
            //修改密码
            $courier = Db::name('courier')->where('courier_id' , $courier_id)->find();
            if($courier['courier_pwd'] != md5($old_pwd)){
               $this->error('旧密码不正确');
            }else if($new_pwd != $new2_pwd){
               $this->error('新密码不一致');
            }else{
                $row = Db::name('courier')->where('courier_id' , $courier_id)->update(['courier_pwd' => $new_pwd]);
                if($row){
                    $this->success('修改成功！', url('Index/user'));
                }else{
                   $this->error('修改失败');
                }
            }
        }
        $this->assign('courier', $courier);
        return $this->fetch();
    }

     /**
     * 新用户注册
     * @return \think\mixed
     */
    public function add(){
        if (request()->isPost()) {

            $data['courier'] = $this->request->post('user_name', '');
            $pwd = $this->request->post('user_pwd', '');
            $data['courier_pwd'] = md5($pwd);
            $data['status'] = 1;
            $data['courier_idle']  = 1;      
            $rs = Db::name('courier')->insert($data);
            if ($rs) { 
                    $this->success('注册成功！', url('Login/login'));
                } else {
                    $this->error('注册失败');
                }
        }
        return $this->fetch();
    }


}