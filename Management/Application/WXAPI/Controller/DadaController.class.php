<?php

namespace WXAPI\Controller;

use Common\Logic\DadaLogic;
use Think\Controller;

/**
 * 达达回调
 * User: Administrator
 * @author xxx
 */
class DadaController extends Controller
{
    
    public function Callback(){
        $postStr = file_get_contents("php://input");
        $postStr = json_decode($postStr, true);
        
        $dadaLogic = new DadaLogic();
        $dadaLogic->Callback($postStr);  // 调用回调
        
        file_put_contents("./dada.txt", $postStr, FILE_APPEND);
    }
}