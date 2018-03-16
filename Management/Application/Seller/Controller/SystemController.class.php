<?php

namespace Seller\Controller;

/**
 * User: Administrator
 * @author xxx
 */
class SystemController extends BaseController
{
    public function adduser(){
        header("Content-type: text/html; charset=utf-8");
        $user_id = I('user_id');
        $openid = M('wx_user')->where(array('id' => $user_id))->getfield('openid');
        if (!$openid) {
            return $this->ajaxReturn('没有找到此用户');
        }
        
        M('wx_user')->where(array('id' => $user_id))->save(array('type' => 1, 'store_id' => STORE_ID));
        
        // $url     = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $this->access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->access_token();
        
        $template = array(
            'touser' => $openid,
            'template_id' => "DTyrFQe2GA2-IoIbgXgSvXhuVXbPcnejGaPyMlSxZlE",
            'data' => array(
                'first' => array('value' => "这是测试消息，收到该信息,表示成功绑定了发货通知", 'color' => "#173177"),
                'keyword1' => array('value' => "000000", 'color' => "#173177"),
                'keyword2' => array('value' => "000000", 'color' => "#173177"),
                'keyword3' => array('value' => "1", 'color' => "#173177"),
                'keyword4' => array('value' => "0", 'color' => "#173177"),
                'remark' => array('value' => "提示信息", 'color' => "#173177"),
            ),
        );
        $template = json_encode($template);
        $res = httpRequest($url, 'post', $template);
        
        $this->success('绑定成功');
        // $data    = array();
        // $content = "您已经成功绑定发货提醒功能";
        
        // $data['touser']  = $openid;
        // $data['msgtype'] = "text";
        // $data['text']    = array(
        //     'content' => $content
        // );
        // $data            = json_encode($data, JSON_UNESCAPED_UNICODE);
        // $res             = httpRequest($url, 'post', $data);
        // $res             = json_encode($res, true);
        // if ($res['errorcode'] > 0) {
        //     S('access_token', null);
        // }
    }
    
    public function deluser(){
        header("Content-type: text/html; charset=utf-8");
        $user_id = I('user_id');
        $openid = M('wx_user')->where(array('id' => $user_id))->getfield('openid');
        if (!$openid) {
            return $this->ajaxReturn('没有找到此用户');
        }
        
        M('wx_user')->where(array('id' => $user_id))->save(array('type' => 0));
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $this->access_token();
        $data = array();
        $content = "您取消发货提醒功能";
        
        $data['touser'] = $openid;
        $data['msgtype'] = "text";
        $data['text'] = array(
            'content' => $content,
        );
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = httpRequest($url, 'post', $data);
        $res = json_encode($res, true);
        if ($res['errorcode'] > 0) {
            S('access_token', null);
        }
    }
    
    function curl_post($url, $data = null){
        //创建一个新cURL资源
        $curl = curl_init();
        //设置URL和相应的选项
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行curl，抓取URL并把它传递给浏览器
        $output = curl_exec($curl);
        //关闭cURL资源，并且释放系统资源
        curl_close($curl);
        return $output;
    }
    
    public function checkuser(){
        $user = I('user');
        $sql = "SELECT * FROM ty_wx_user where mobile = '$user' or wxname = '$user' ";
        $res = M()->query($sql);
        if (!empty($res[0])) {
            $this->ajaxReturn(array('data' => $res[0], 'status' => 200));
        }
        //查询不到同步数据
        $this->synwx($user);
        
        $sql = "SELECT * FROM ty_wx_user where mobile = '$user' or wxname = '$user' ";
        $res = M()->query($sql);
    }
    
    private function synwx($username){
        // 获得access_token;
        $access_token = $this->access_token();
        // 获取本地用户
        $user_count = M('wx_user')->count();
        $user_next = M('wx_user')->order('create_time desc')->find();
        if ($user_count > 10000) {
            // 获取微信用户列表
            $wxList = $this->getwxList($access_token, $user_next['openid']);
        } else {
            $wxList = $this->getwxList($access_token);
        }
        
        if ($wxList['total'] > $user_count) {
            //进行新增操作
            foreach ($wxList['data']['openid'] as $k => $v) {
                $count = M('wx_user')->where(['openid' => $v])->count();
                if ($count)
                    continue;
                $data = array();
                $data['openid'] = $v;
                $data['create_time'] = time();
                M('wx_user')->add($data);
            }
        }
        // 同步用户信息
        $user = M('wx_user')->where(array('encode' => 0))->select();
        foreach ($user as $k => $v) {
            $wxUser = $this->getWxUser($v['openid'], $access_token);
            $data = array();
            $data['wxname'] = $wxUser['nickname'];
            $data['wx_img'] = $wxUser['headimgurl'];
            $data['encode'] = 1;
            if ($username == $wxUser['nickname']) {
                $data['store_id'] = STORE_ID;
            }
            M('wx_user')->where(array('openid' => $wxUser['openid']))->save($data);
        }
    }
    
    private function access_token(){
        $access_token = S('access_token');
        if ($access_token) {
            return $access_token;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf6ae46b6d8aa4051&secret=52c4c07dd6a97151f3bf6b55cf26242b";
        $res = httpRequest($url, 'get');
        $res = json_decode($res, true);
        if ($res['access_token']) {
            $access_token = $res['access_token'];
            S('access_token', $access_token, 7100);
            return $access_token;
        }
    }
    
    private function getwxList($access_token, $next_openid = null){
        if ($next_openid) {
            $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token . '&next_openid=' . $next_openid;
        } else {
            $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token;
        }
        $res = httpRequest($url, 'get');
        $res = json_decode($res, true);
        return $res;
    }
    
    private function getWxUser($openid, $access_token){
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $res = httpRequest($url, 'get');
        $res = json_decode($res, true);
        return $res;
    }
}