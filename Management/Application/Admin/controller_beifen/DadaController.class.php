<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;
use Common\Logic\DadaLogic;

/**
 * 达达门店管理
 * User: Administrator
 * @author xxx
 */
class DadaController extends BaseController
{
    public function dada_list(){
        $model = M('Dada');
        $count = $model->where()->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->order('store_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display();
    }
    
    public function dada_edit(){
        if (IS_POST) {
            $dadaModel = M('dada');
            $data      = $dadaModel->create();
            if ($data) {
                $DadaLogic = new DadaLogic();
                $res       = $DadaLogic->update_store($data);
            }
            if ($res !== false) {
                $this->success('修改达达店铺成功', U('Dada/dada_list'));
            } else {
                $this->error('修改达达店铺失败', U('Dada/dada_list'));
            }
        } else {
            $id = I('get.id', 0, 'intval');
            empty($id) && $this->error('参数错误');
            $info = M('dada')->where(['id' => $id])->find();
            
            $this->assign('info', $info);
            $this->display();
        }
        
    }
}