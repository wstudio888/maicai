<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace Admin\Model;

use Common\Logic\DadaLogic;
use Think\Model;

class DadaModel extends Model
{
    public function getOne($store_id){
        $res = $this->where(['store_id' => $store_id])->find();
        return $res;
    }
    
    public function insert($data){
        if (empty($data)) $this->create();
        $data['create_time'] = time();
        
        $res = $this->data($data)->add();
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}