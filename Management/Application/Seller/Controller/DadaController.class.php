<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace Seller\Controller;

class DadaController extends BaseController
{
    public function dada_list(){
        $model = M('Dada_order');
        $count = $model->where()->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
  
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('DADA_REASON',C('DADA_REASON'));
        $this->assign('DADA_STATUS',C('DADA_STATUS'));
        $this->display();
    }
}