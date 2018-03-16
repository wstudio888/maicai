<?php
/**
 * User: Administrator
 * @author xxx
 */

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index(){
     
       echo wx_curl_post('https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=TNqyP0b5sRTsA_CE3L_kn9aj6ksDbZmVZs-l-85O2D0rQI--mrAUTFAeiSQ5ltP0D-r6oLrsgM4KoATLpQSm1Z1i6DWttCClagouMqRjdfcodWZFgbXnzT8X948dOcyCVSNfAGABYT',['path'=>'pages/index?query=1','width'=>400]);
        die;
        $fid = I('get.fid', 0);
        //收到上级分销者ID
        if ($fid) {
        
        }
    }
}