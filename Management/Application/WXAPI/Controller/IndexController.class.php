<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */

namespace WXAPI\Controller;

use Think\Controller;

class IndexController extends BaseController
{
    public function index(){
        // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }
    
    /*
     * 获取首页数据
     */
    public function home(){
        // 获取优惠卷
        $time       = time();
        $couponlist = M('coupon')->where(array('send_start_time' => array('LT', $time), 'send_end_time' => array('GT', $time)))->select();
        
        // 获取用户优惠卷
        foreach ($couponlist as $key => $value) {
            $couponlist[$key]['use_start_time'] = date('Y.m.d', $value['use_start_time']);
            $couponlist[$key]['use_end_time']   = date('Y.m.d', $value['use_end_time']);
            $couponlist[$key]['money']          = number_format($value['money']);
            $couponlist[$key]['condition']      = number_format($value['condition']);
        }
        
        // 获取轮播图
        $data = M('ad')->where(array('pid'=>1,'enabled'=>1))->field(array('ad_link', 'ad_name', 'ad_code', 'pid'))->cache(true, TPSHOP_CACHE_TIME)->select();
        // 广告地址转换
        foreach ($data as $k => $v) {
            $data[$k]['ad_code'] = SITE_URL . $v['ad_code'];
        }
        
        // 促销推荐
        $cuxiao = M('ad')->where(array('pid'=>2,'enabled'=>1))->field(array('ad_link', 'ad_name', 'ad_code', 'pid'))->cache(true, TPSHOP_CACHE_TIME)->select();
        foreach ($cuxiao as $key => $value) {
            $cuxiao[$key]['ad_code'] = SITE_URL . $value['ad_code'];
        }
        
        // 每日市价更新
        $newlist = M('ad')->where(array('pid'=>3,'enabled'=>1))->field(array('ad_link', 'ad_name', 'ad_code', 'pid'))->cache(true, TPSHOP_CACHE_TIME)->select();
        foreach ($newlist as $key => $value) {
            $newlist[$key]['ad_code'] = SITE_URL . $value['ad_code'];
        }
        
        // 推荐公司信息
        $hot            = M('article')->where(array('article_id' => 2))->field(array('article_id', 'content', 'link', 'description'))->cache(true, TPSHOP_CACHE_TIME)->find();
        $hot['content'] = str_replace('/Public/upload/', SITE_URL . "/Public/upload/", $hot['content']);
        
        // 公司介绍
        $about            = M('article')->where(array('article_id' => 1))->field(array('article_id', 'content', 'link', 'description'))->cache(true, TPSHOP_CACHE_TIME)->find();
        $about['content'] = str_replace('/Public/upload/', SITE_URL . "/Public/upload/", $about['content']);
        
        //获取分类
        $category = M('article_cat')->where(array('parent_id' => 2, 'show_in_nav' => 1))->field('cat_name,thumb,cat_desc')->cache(true, TPSHOP_CACHE_TIME)->order('sort_order')->select();
        foreach ($category as $key => $value) {
            $category[$key]['thumb'] = SITE_URL . $value['thumb'];
        }
        
        $category2 = M('article_cat')->where(array('parent_id' => 9, 'show_in_nav' => 1))->field('cat_name,thumb,cat_desc,type')->cache(true, TPSHOP_CACHE_TIME)->order('sort_order')->select();
        foreach ($category2 as $key => $value) {
            $category2[$key]['thumb'] = SITE_URL . $value['thumb'];
        }
        
        exit(json_encode(array('status' => 1, 'msg' => '获取成功', 'result' => array('about' => $about, 'hot' => $hot, 'category' => $category, 'category2' => $category2, 'coupon' => $couponlist, 'newlist' => $newlist, 'ad' => $data, 'cuxiao' => $cuxiao))));
    }
    
    /**
     * 获取服务器配置
     */
    public function getConfig(){
        $config_arr = M('config')->select();
        exit(json_encode(array('status' => 1, 'msg' => '获取成功', 'result' => $config_arr)));
    }
    
    /**
     * 获取插件信息
     */
    public function getPluginConfig(){
        $data = M('plugin')->where("type='payment' OR type='login'")->select();
        $arr  = array();
        foreach ($data as $k => $v) {
            unset($data[$k]['config']);
            unset($data[$k]['config']);
            
            $data[$k]['config_value'] = unserialize($v['config_value']);
            if ($data[$k]['type'] == 'payment') {
                $arr['payment'][] = $data[$k];
            }
            if ($data[$k]['type'] == 'login') {
                $arr['login'][] = $data[$k];
            }
        }
        exit(json_encode(array('status' => 1, 'msg' => '获取成功', 'result' => $arr ? $arr : '')));
    }
}