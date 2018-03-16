<?php

namespace Common\Logic;

/**
 * 达达第三方接口
 * User: Administrator
 * @author xxx
 */
class DadaLogic
{
    private $config = ['app_key' => 'dada0435222b1f7138d', 'app_secret' => 'ff5b7fb0f82de2c7df5aeab25f78e6b5', 'source_id' => '73753'];
    
    private $url = 'http://newopen.qa.imdada.cn/'; //测试
//    private $url = 'http://newopen.imdada.cn/';  //正式
    
    // 新增订单
    public function add_order($dada, $type){
        $data   = array(
            'shop_no' => $dada['shop_no'],
            'origin_id' => $dada['invoice_no'],
            'city_code' => $dada['city_code'],
            'cargo_price' => $dada['order_amount'],
            'is_prepay' => 0,
            'expected_fetch_time' => time() + 600,
            'receiver_name' => $dada['consignee'],
            'receiver_address' => $dada['dada_addres'],
            'receiver_lat' => $dada['dada_lat'],
            'receiver_lng' => $dada['dada_lng'],
//          'callback' => SITE_URL . '/index.php/WXAPI/Dada/Callback.html', //回调
            'callback' => 'https://foods.woshangapp.com/index.php/WXAPI/Dada/Callback', //回调
            'receiver_phone' => $dada['mobile'],
            'info' => $dada['info']
        );
        $config = $this->config;
        if ($type) { //订单存在重新发送
            $config['url'] = $this->url . '/api/order/reAddOrder';
        } else {
            $config['url'] = $this->url . 'api/order/addOrder';
        }
        $obj       = new DadaOpenApiLogic($config);
        $reqStatus = $obj->makeRequest($data);
        if (!$reqStatus) {
            //接口请求正常，判断接口返回的结果，自定义业务操作
            if ($obj->getCode() == 0) {
                $dada_order                 = array();
                $dada_order['origin_id']    = $dada['invoice_no'];
                $dada_order['order_status'] = 1;
                $dada_order['order_id']     = $dada['order_id'];
                
                $res = M('dada_order')->add($dada_order);
                return $res;
            } else {
                echo sprintf('code:%s，msg:%s', $obj->getCode(), $obj->getMsg());
                die;
            }
            
        } else {
            //请求异常或者失败
            echo 'except';
        }
    }
    
    // 取消订单
    
    //订单详情查询
    
    // 门店创建
    public function addstore($dada){
        // 门店数据
        $data = array(
            0 => array(
                'station_name' => $dada['dada_name'],
                'business' => $dada['dada_class'],
                'city_name' => str_replace('市', "", $dada['dada_city']),
                'area_name' => $dada['dada_district'],
                'station_address' => $dada['dada_addres'],
                'lng' => $dada['dada_lng'],
                'lat' => $dada['dada_lat'],
                'contact_name' => $dada['dada_contacts'],
                'phone' => $dada['dede_mobile'],
                'id_card' => $dada['dada_identity'],
            )
        );
        
        $config        = $this->config;
        $config['url'] = $this->url . 'api/shop/add';
        $obj           = new DadaOpenApiLogic($config);
        $reqStatus     = $obj->makeRequest($data);
        if (!$reqStatus) {
            //接口请求正常，判断接口返回的结果，自定义业务操作
            if ($obj->getCode() == 0) {
                $dadaModel              = D('Dada');
                $result                 = $obj->getResult();
                $dada['origin_shop_id'] = $result['successList'][0]['origin_shop_id']; //达达门店ID
                $res                    = $dadaModel->insert($dada);
                return $res;
            } else {
                echo sprintf('code:%s，msg:%s', $obj->getCode(), $obj->getMsg());
                die;
            }
        } else {
            //请求异常或者失败
            echo 'except';
        }
    }
    
    // 更新门店
    public function update_store($dada){
        // 门店数据
        $data = array(
            'origin_shop_id' => $dada['origin_shop_id'],
            'station_name' => $dada['dada_name'],
            'business' => $dada['dada_class'],
            'city_name' => str_replace('市', "", $dada['dada_city']),
            'area_name' => $dada['dada_district'],
            'station_address' => $dada['dada_addres'],
            'lng' => $dada['dada_lng'],
            'lat' => $dada['dada_lat'],
            'contact_name' => $dada['dada_contacts'],
            'phone' => $dada['dede_mobile'],
            'id_card' => $dada['dada_identity'],
        );
        
        $config        = $this->config;
        $config['url'] = $this->url . 'api/shop/update';
        $obj           = new DadaOpenApiLogic($config);
        $reqStatus     = $obj->makeRequest($data);
        if (!$reqStatus) {
            //接口请求正常，判断接口返回的结果，自定义业务操作
            if ($obj->getCode() == 0) {
                $dadaModel = D('Dada');
                $res       = $dadaModel->save($dada);
                return $res;
            } else {
                echo sprintf('code:%s，msg:%s', $obj->getCode(), $obj->getMsg());
                die;
            }
            
        } else {
            //请求异常或者失败
            echo 'except';
        }
    }
    
    // 回调处理
    public function Callback($data){
        if (empty($data)) return false;
        
        //查询达达订单
        $dada_order = M('dada_order')->where(array('origin_id' => $data['order_id'], 'cancel_from' => 0))->find();
        if ($dada_order) {
            $insert                  = array();
            $insert['client_id']     = $data['client_id'];
            $insert['order_status']  = $data['order_status'];
            $insert['cancel_reason'] = $data['cancel_reason'];
            $insert['cancel_from']   = $data['cancel_from'];
            $insert['update_time']   = $data['update_time'];
            $insert['dm_id']         = $data['dm_id'];
            $insert['dm_name']       = $data['dm_name'];
            $insert['dm_mobile']     = $data['dm_mobile'];
            if ($data['cancel_from']) { //达达订单取消
                $orderModel = M('order');
                // 改变订单状态
                $order_info = $orderModel->field('order_id')->where(array('order_id' => $dada_order['order_id']))->find();
                if ($order_info) {
                    $orderModel->where(array('order_id' => $order_info['order_id']))->save(array('shipping_status' => 0)); //订单修改成未发货
                    M('order_goods')->where(array('is_send' => 0, 'order_id' => $order_info['order_id']))->save(array('is_s  end' => 0)); //订单商品修改成为发货
                }
            }
            $res = M('dada_order')->where(array('origin_id' => $data['order_id'], 'cancel_from' => 0))->save($data);
            if ($res !== false) {
                $result = array(
                    'status' => 'success',
                    'code' => 0,
                    'msg' => '回调成功');
                echo json_encode($result);
            }
        }
    }
}