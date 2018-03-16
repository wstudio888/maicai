<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-17
 */

namespace Admin\Controller;

use Think\AjaxPage;

use Admin\Logic\StoreLogic;
use Common\Logic\DadaLogic;

class CourierController extends BaseController
{
    /**----------------------------------------------*/
    /*               快递员控制器                  */
    /**----------------------------------------------*/

    /*
     * 快递员列表
     */
    public function courier(){
        $model = M('courier');
        $count = $model->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->order('last_login DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        
        $show = $Page->show();
        $this->assign('page', $show);
  
        $this->display();
    }
  
    /*
     * 快递员送单列表
     */
    public function express(){
        $model = M('express');
        $courier = M('courier');

        $count = $model->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model
            ->join('ty_courier ON ty_courier.courier_id = ty_express.courier_id')
            ->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        

        $show = $Page->show();
        $this->assign('page', $show);  
        $this->display();
    }

    /*
     * 快递员现金提现申请列表
     */
    public function courier_money(){
        $model = M('courier_money');
        $courier = M('courier');

        $count = $model->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model
            ->join('ty_courier ON ty_courier.courier_id = ty_courier_money.courier_id')
            ->order('id DESC')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();        
        $this->assign('list', $list);

        $id = I('get.id',0);  

        if( $id ){
            $data['status_x'] = 2;
            $data['terminal_time'] = time();
            $rs = $model->where(array('id' => $id))->save($data);
            if($rs) {
                 $this->success("确认成功");
            } else {
                 $this->error("确认失败");
            }
        }
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display();
    }
}