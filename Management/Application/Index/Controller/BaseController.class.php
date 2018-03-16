<?php

namespace Index\Controller;

use Think\Cache;
use Think\Config;
use Think\Controller;
use Think\Db;

/**
 * 基础控制器
 * User: Administrator
 * @author xxx
 */
class BaseController extends Controller
{
    
    protected function _initialize(){
        // 获取当前用户ID
        if (defined('UID')) return;
        define('UID', is_login());
        if (!UID) {// 还没登录 跳转到登录页面
            $this -> redirect('Login/login');
        }

    }
}