<?php

namespace WXAPI\Controller;

use Api\Logic\GoodsLogic;

use Think\Page;

class GoodsController extends BaseController
{
    
    /**
     * 析构流函数
     */
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        // $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }
    
    /**
     * 获取商品分类列表
     */
    public function goodsCategoryList(){
        $parent_id = I("parent_id", 0);
        
        $goodsCategoryList = M('GoodsCategory')->where("parent_id = $parent_id AND is_show=1")->order("parent_id_path,sort_order desc")->select();
        
        foreach ($goodsCategoryList as $key => $value) {
            $goodsCategoryList[$key]['image'] = SITE_URL . $value['image'];
        }
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $goodsCategoryList);
        $json_str = json_encode($json_arr);
        exit($json_str);
    }
    
    /**
     * 获取商品分类列表
     */
    public function categoryBanner(){
        $banner_name = I("banner_name", 0);
        
        $position_id = M('ad_position')->where(array("position_name" => $banner_name))->getField("position_id");
        
        if ($position_id) {
            $banner = M('ad')->where(array("pid" => $position_id))->find();
            if ($banner) {
                $banner['image'] = SITE_URL . $banner['ad_code'];
                $json_arr        = array('status' => 1, 'msg' => '获取成功', "banner" => $banner);
                $json_str        = json_encode($json_arr);
                exit($json_str);
            }
        }
        
        $json_arr = array('status' => 0, 'msg' => '获取失败');
        $json_str = json_encode($json_arr);
        exit($json_str);
    }
    
    /**
     * 获取根据一级分类获取对应的二三级分类
     */
    public function goodsSecAndThirdCategoryList(){
        $parent_id = I("parent_id", 0);
        // SELECT * FROM `tp_goods_category` WHERE parent_id_path LIKE '%0\_1\_%'
        /** 一次查询所有二级和三级分类  **/
        
        $test_str = 'POST' . print_r($_POST, true);
        $test_str .= 'GET' . print_r($_GET, true);
        file_put_contents('D:\temp\a.html', $test_str);
        
        $list  = M('GoodsCategory')->where("parent_id_path LIKE '%0\\_{$parent_id}\\_%' AND is_show=1")->getField('id,mobile_name,image, level , parent_id');
        $list2 = array();
        foreach ($list as $k => $v) {
            if ($v['level'] == 3)
                continue;
            
            $arr                = array();
            $arr['mobile_name'] = $v['mobile_name'];
            $arr['image']       = $v['image'];
            $arr['id']          = $v['id'];
            $arr['level']       = $v['level'];
            $arr['parent_id']   = $v['parent_id'];
            
            $arr3 = array();
            foreach ($list as $k2 => $v2) {
                if ($v['id'] == $v2['parent_id']) {
                    $arr3['mobile_name']   = $v2['mobile_name'];
                    $arr3['image']         = $v2['image'];
                    $arr3['id']            = $v2['id'];
                    $arr3['level']         = $v2['level'];
                    $arr3['parent_id']     = $v2['parent_id'];
                    $arr['sub_category'][] = $arr3;
                }
            }
            //var_dump($arr3);
            
            $list2[] = $arr;
        }
        
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $list2);
        $json_str = json_encode($json_arr);
        exit($json_str);
    }
    
    /**
     * 商品列表页
     */
    public function goodsList(){
        C('URL_MODEL', 0); // 返回给手机app 生成路径格式 为 普通 index.php?=api&c=  最普通的路径格式
        
        $key  = md5($_SERVER['REQUEST_URI'] . $_POST['start_price'] . '_' . $_POST['end_price']);
        $html = S($key);
        if (!empty($html)) {
            exit($html);
        }
        $p            = I('get.p', 0); // 当前分类id
        $filter_param = array(); // 帅选数组                        
        $id           = I('get.id', 1); // 当前分类id
        $brand_id     = I('get.brand_id', 0);
        $spec         = I('get.spec', 0); // 规格
        $attr         = I('get.attr', ''); // 属性
        $sort         = I('get.sort', 'goods_id'); // 排序
        $sort_asc     = I('get.sort_asc', 'asc'); // 排序
        $price        = I('get.price', ''); // 价钱
        $start_price  = trim(I('post.start_price', '0')); // 输入框价钱
        $end_price    = trim(I('post.end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $spec && ($filter_param['spec'] = $spec); //加入帅选条件中
        $attr && ($filter_param['attr'] = $attr); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        
        $goodsLogic = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = M('GoodsCategory')->where("id = $id")->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.U('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);
        
        // 帅选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson($id);
        
        $filter_goods_id = M('goods')->where("is_on_sale=1 and cat_id2 in(" . implode(',', $cat_id_arr) . ") ")->cache(true)->getField("goods_id", true);
        
        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1      = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if ($spec)// 规格
        {
            $goods_id_2      = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if ($attr)// 属性
        {
            $goods_id_3      = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_3); // 获取多个帅选条件的结果 的交集
        }
        
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param, 'goodsList'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'goodsList'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选品牌
        $filter_spec  = $goodsLogic->get_filter_spec($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选规格
        $filter_attr  = $goodsLogic->get_filter_attr($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选属性
        
        $count          = count($filter_goods_id);
        $page           = new Page($count, 5);
        $page->firstRow = $p * $page->listRows;
        if ($count > 0) {
            $goods_list       = M('goods')->field('goods_id,cat_id2,goods_sn,goods_name,shop_price,comment_count,original_img,sales_sum')->where("cat_id2 = $id and is_on_sale = 1")->order("$sort $sort_asc")->limit($page->firstRow . ',' . $page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['image'] = SITE_URL . $value['original_img'];
        }
        
        $list['goods_list'] = $goods_list;
        $i                  = 1;
        //菜单
        foreach ($filter_menu as $k => $v) // 依照app端的要求 去掉 键名
        {
            $v['name'] = $v['text'];
            unset($v['text']);
            $list['filter_menu'][] = $v;  // 帅选规格
        }
        
        // 规格
        foreach ($filter_spec as $k => $v) // 依照app端的要求 去掉 键名
        {
            $items['name'] = $v['name'];
            foreach ($v['item'] as $k2 => $v2) {
                $items['item'][] = array('name' => $v2['item'], 'href' => $v2['href'], 'id' => $i++);
            }
            $list['filter_spec'][] = $items;
            $items                 = array();
        }
        
        // $list['filter_spec'] = $filter_spec;
        // 属性
        foreach ($filter_attr as $k => $v) // 依照app端的要求 去掉 键名
        {
            $items['name'] = $v['attr_name'];
            foreach ($v['attr_value'] as $k2 => $v2) {
                $items['item'][] = array('name' => $v2['attr_value'], 'href' => $v2['href'], 'id' => $i++);
            }
            
            $list['filter_attr'][] = $items;
            $items                 = array();
        }
        // 品牌
        foreach ($filter_brand as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_brand'][] = array('name' => $v['name'], 'hreg' => $v['href'], 'id' => $i++);
        }
        
        // 价格
        foreach ($filter_price as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_price'][] = array('name' => $v['value'], 'href' => $v['href'], 'id' => $i++);
        }
        
        $list['sort']                  = $sort;
        $list['sort_asc']              = $sort_asc;
        $sort_asc                      = $sort_asc == 'asc' ? 'desc' : 'asc';
        $list['orderby_default']       = urldecode(U("Goods/goodsList", $filter_param, '')); // 默认排序
        $list['orderby_sales_sum']     = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'sales_sum', 'sort_asc' => 'desc')), '')); // 销量排序
        $list['orderby_price']         = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'shop_price', 'sort_asc' => $sort_asc)), '')); // 价格
        $list['orderby_comment_count'] = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'comment_count', 'sort_asc' => 'desc')), '')); // 评论
        $list['orderby_is_new']        = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'is_new', 'sort_asc' => 'desc')), '')); // 新品
        C('TOKEN_ON', false);
        
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $list);
        $json_str = json_encode($json_arr, true);
        exit($json_str);
    }

     /**
     * 商品列表页
     */
    public function goodsLists(){
        C('URL_MODEL', 0); // 返回给手机app 生成路径格式 为 普通 index.php?=api&c=  最普通的路径格式
        
        $key  = md5($_SERVER['REQUEST_URI'] . $_POST['start_price'] . '_' . $_POST['end_price']);
        $html = S($key);
        if (!empty($html)) {
            exit($html);
        }
        $p            = I('get.p', 0); // 当前分类id
        $filter_param = array(); // 帅选数组                        
        $id           = I('get.id', 1); // 当前分类id
        $brand_id     = I('get.brand_id', 0);
        $spec         = I('get.spec', 0); // 规格
        $attr         = I('get.attr', ''); // 属性
        $sort         = I('get.sort', 'goods_id'); // 排序
        $sort_asc     = I('get.sort_asc', 'asc'); // 排序
        $price        = I('get.price', ''); // 价钱
        $start_price  = trim(I('post.start_price', '0')); // 输入框价钱
        $end_price    = trim(I('post.end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $spec && ($filter_param['spec'] = $spec); //加入帅选条件中
        $attr && ($filter_param['attr'] = $attr); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        
        $goodsLogic = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
        // 分类菜单显示
        $goodsCate = M('GoodsCategory')->where("id = $id")->find();// 当前分类
        //($goodsCate['level'] == 1) && header('Location:'.U('Home/Channel/index',array('cat_id'=>$id))); //一级分类跳转至大分类馆
        $cateArr = $goodsLogic->get_goods_cate($goodsCate);
        
        // 帅选 品牌 规格 属性 价格
        $cat_id_arr = getCatGrandson($id);
        
        $filter_goods_id = M('goods')->where("is_on_sale=1 and cat_id2 in(" . implode(',', $cat_id_arr) . ") ")->cache(true)->getField("goods_id", true);
        
        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1      = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if ($spec)// 规格
        {
            $goods_id_2      = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if ($attr)// 属性
        {
            $goods_id_3      = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_3); // 获取多个帅选条件的结果 的交集
        }
        
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param, 'goodsList'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'goodsList'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选品牌
        $filter_spec  = $goodsLogic->get_filter_spec($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选规格
        $filter_attr  = $goodsLogic->get_filter_attr($filter_goods_id, $filter_param, 'goodsList', 1); // 获取指定分类下的帅选属性
        
        $count          = count($filter_goods_id);
        $page           = new Page($count, 5);
        $page->firstRow = $p * $page->listRows;
        if ($count > 0) {
            $goods_list       = M('goods')->field('goods_id,cat_id2,goods_sn,goods_name,shop_price,comment_count,original_img,sales_sum')->where("cat_id1 = $id and is_on_sale = 1")->order("$sort $sort_asc")->limit($page->firstRow . ',' . $page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['image'] = SITE_URL . $value['original_img'];
        }
        
        $list['goods_list'] = $goods_list;
        $i                  = 1;
        //菜单
        foreach ($filter_menu as $k => $v) // 依照app端的要求 去掉 键名
        {
            $v['name'] = $v['text'];
            unset($v['text']);
            $list['filter_menu'][] = $v;  // 帅选规格
        }
        
        // 规格
        foreach ($filter_spec as $k => $v) // 依照app端的要求 去掉 键名
        {
            $items['name'] = $v['name'];
            foreach ($v['item'] as $k2 => $v2) {
                $items['item'][] = array('name' => $v2['item'], 'href' => $v2['href'], 'id' => $i++);
            }
            $list['filter_spec'][] = $items;
            $items                 = array();
        }
        
        // $list['filter_spec'] = $filter_spec;
        // 属性
        foreach ($filter_attr as $k => $v) // 依照app端的要求 去掉 键名
        {
            $items['name'] = $v['attr_name'];
            foreach ($v['attr_value'] as $k2 => $v2) {
                $items['item'][] = array('name' => $v2['attr_value'], 'href' => $v2['href'], 'id' => $i++);
            }
            
            $list['filter_attr'][] = $items;
            $items                 = array();
        }
        // 品牌
        foreach ($filter_brand as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_brand'][] = array('name' => $v['name'], 'hreg' => $v['href'], 'id' => $i++);
        }
        
        // 价格
        foreach ($filter_price as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_price'][] = array('name' => $v['value'], 'href' => $v['href'], 'id' => $i++);
        }
        
        $list['sort']                  = $sort;
        $list['sort_asc']              = $sort_asc;
        $sort_asc                      = $sort_asc == 'asc' ? 'desc' : 'asc';
        $list['orderby_default']       = urldecode(U("Goods/goodsList", $filter_param, '')); // 默认排序
        $list['orderby_sales_sum']     = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'sales_sum', 'sort_asc' => 'desc')), '')); // 销量排序
        $list['orderby_price']         = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'shop_price', 'sort_asc' => $sort_asc)), '')); // 价格
        $list['orderby_comment_count'] = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'comment_count', 'sort_asc' => 'desc')), '')); // 评论
        $list['orderby_is_new']        = urldecode(U("Goods/goodsList", array_merge($filter_param, array('sort' => 'is_new', 'sort_asc' => 'desc')), '')); // 新品
        C('TOKEN_ON', false);
        
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $list);
        $json_str = json_encode($json_arr, true);
        exit($json_str);
    }
    
    /**
     * 商品搜索列表页
     */
    public function search(){
        C('URL_MODEL', 0); // 返回给手机app 生成路径格式 为 普通 index.php?=api&c=  最普通的路径格式
        $filter_param = array(); // 帅选数组
        $p            = I('get.p', 0);
        $id           = I('get.id', 0); // 当前分类id
        $brand_id     = I('brand_id', 0);
        $sort         = I('sort', 'goods_id'); // 排序
        $sort_asc     = I('sort_asc', 'asc'); // 排序
        $price        = I('price', ''); // 价钱
        $start_price  = trim(I('start_price', '0')); // 输入框价钱
        $end_price    = trim(I('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        $q = urldecode(trim(I('keywords', ''))); // 关键字搜索
        $q && ($_GET['q'] = $filter_param['q'] = $q); //加入帅选条件中
        if (empty($q))
            exit(json_encode(array('status' => -1, 'msg' => '请输入搜索关键词'), true));
        
        $goodsLogic      = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
        $filter_goods_id = M('goods')->where("is_on_sale=1 and goods_name like '%{$q}%'  ")->cache(true)->getField("goods_id", true);
        
        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1      = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param, 'search'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'search'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'search', 1); // 获取指定分类下的帅选品牌
        
        $count          = count($filter_goods_id);
        $page           = new Page($count, 5);
        $page->firstRow = $p * $page->listRows;
        if ($count > 0) {
            $goods_list       = M('goods')->field('original_img,goods_id,cat_id2,goods_sn,goods_name,shop_price,comment_count,sales_sum')->where("goods_id in (" . implode(',', $filter_goods_id) . ")")->order("$sort $sort_asc")->limit($page->firstRow . ',' . $page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['image'] = SITE_URL . $value['original_img'];
        }
        
        $list['goods_list'] = $goods_list;
        
        $i = 1;
        //菜单
        foreach ($filter_menu as $k => $v) // 依照app端的要求 去掉 键名
        {
            $v['name'] = $v['text'];
            unset($v['text']);
            $list['filter_menu'][] = $v;  // 帅选规格
        }
        
        // 品牌
        foreach ($filter_brand as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_brand'][] = array('name' => $v['name'], 'href' => $v['href'], 'id' => $i++);
        }
        
        // 价格
        foreach ($filter_price as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_price'][] = array('name' => $v['value'], 'href' => $v['href'], 'id' => $i++);
        }
        
        $list['sort']                  = $sort;
        $list['sort_asc']              = $sort_asc;
        $sort_asc                      = $sort_asc == 'asc' ? 'desc' : 'asc';
        $list['orderby_default']       = urldecode(U("Goods/search", $filter_param, '')); // 默认排序
        $list['orderby_sales_sum']     = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'sales_sum', 'sort_asc' => 'desc')), '')); // 销量排序
        $list['orderby_price']         = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'shop_price', 'sort_asc' => $sort_asc)), '')); // 价格
        $list['orderby_comment_count'] = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'comment_count', 'sort_asc' => 'desc')), '')); // 评论
        $list['orderby_is_new']        = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'is_new', 'sort_asc' => 'desc')), '')); // 新品
        C('TOKEN_ON', false);
        
        $json_arr = array('status' => 1, 'msg' => '获取成功789', 'result' => $list);
        $json_str = json_encode($json_arr, true);
        exit($json_str);
    }
    
    /**
     * 商品搜索列表页
     */
    public function searchs(){
        C('URL_MODEL', 0); // 返回给手机app 生成路径格式 为 普通 index.php?=api&c=  最普通的路径格式
        $filter_param = array(); // 帅选数组
        $p            = I('get.p', 0);
        $id           = I('get.id', 0); // 当前分类id
        $brand_id     = I('brand_id', 0);
        $sort         = I('sort', 'goods_id'); // 排序
        $sort_asc     = I('sort_asc', 'asc'); // 排序
        $price        = I('price', ''); // 价钱
        $start_price  = trim(I('start_price', '0')); // 输入框价钱
        $end_price    = trim(I('end_price', '0')); // 输入框价钱
        if ($start_price && $end_price) $price = $start_price . '-' . $end_price; // 如果输入框有价钱 则使用输入框的价钱
        $filter_param['id'] = $id; //加入帅选条件中
        $brand_id && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $price && ($filter_param['price'] = $price); //加入帅选条件中
        $q = urldecode(trim(I('keywords', ''))); // 关键字搜索
        $q && ($_GET['q'] = $filter_param['q'] = $q); //加入帅选条件中
        if (empty($q))
            exit(json_encode(array('status' => -1, 'msg' => '请输入搜索关键词'), true));
        
        $goodsLogic      = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
        $filter_goods_id = M('goods')->where("is_on_sale=1 and goods_name like '%{$q}%'  ")->cache(true)->getField("goods_id", true);
        
        // 过滤帅选的结果集里面找商品
        if ($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1      = $goodsLogic->getGoodsIdByBrandPrice($brand_id, $price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id, $goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param, 'search'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id, $filter_param, 'search'); // 帅选的价格期间
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id, $filter_param, 'search', 1); // 获取指定分类下的帅选品牌
        
        $count          = count($filter_goods_id);
        $page           = new Page($count, 5);
        $page->firstRow = $p * $page->listRows;
        if ($count > 0) {
            $goods_list       = M('goods')->field('original_img,goods_id,cat_id2,goods_sn,goods_name,shop_price,comment_count,sales_sum')->where("cat_id1 = $q")->order("$sort $sort_asc")->limit($page->firstRow . ',' . $page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
        }
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['image'] = SITE_URL . $value['original_img'];
        }
        
        $list['goods_list'] = $goods_list;
        
        $i = 1;
        //菜单
        foreach ($filter_menu as $k => $v) // 依照app端的要求 去掉 键名
        {
            $v['name'] = $v['text'];
            unset($v['text']);
            $list['filter_menu'][] = $v;  // 帅选规格
        }
        
        // 品牌
        foreach ($filter_brand as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_brand'][] = array('name' => $v['name'], 'href' => $v['href'], 'id' => $i++);
        }
        
        // 价格
        foreach ($filter_price as $k => $v) // 依照app端的要求 去掉 键名
        {
            $list['filter_price'][] = array('name' => $v['value'], 'href' => $v['href'], 'id' => $i++);
        }
        
        $list['sort']                  = $sort;
        $list['sort_asc']              = $sort_asc;
        $sort_asc                      = $sort_asc == 'asc' ? 'desc' : 'asc';
        $list['orderby_default']       = urldecode(U("Goods/search", $filter_param, '')); // 默认排序
        $list['orderby_sales_sum']     = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'sales_sum', 'sort_asc' => 'desc')), '')); // 销量排序
        $list['orderby_price']         = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'shop_price', 'sort_asc' => $sort_asc)), '')); // 价格
        $list['orderby_comment_count'] = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'comment_count', 'sort_asc' => 'desc')), '')); // 评论
        $list['orderby_is_new']        = urldecode(U("Goods/search", array_merge($filter_param, array('sort' => 'is_new', 'sort_asc' => 'desc')), '')); // 新品
        C('TOKEN_ON', false);
        
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $list);
        $json_str = json_encode($json_arr, true);
        exit($json_str);
    }
    /*
     * 首页分类详情two
     */
    // public function searchs(){
    //     $keywords = I('get.keywords',0);
    //     $goods_list       = M('goods')->field('original_img,goods_id,cat_id2,goods_sn,goods_name,shop_price,comment_count,sales_sum')->where("cat_id1 = $keywords")->order('sort')->select();
    //     foreach ($goods_list as $key => $value) {
    //         $goods_list[$key]['image'] = SITE_URL . $value['original_img'];
    //     }
    //     $list['goods_list'] = $goods_list;
    //     $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $list);
    //     $json_str = json_encode($json_arr, true);
    //     exit($json_str);
    // }


    /**
     * 获取商品列表
     */
    public function goodsInfo(){
        
        //echo a['goods_spec_list'];
        
        //$http = SITE_URL; // 网站域名
        $goods_id          = $_REQUEST['id'];
        $where['goods_id'] = $goods_id;
        $model             = M('Goods');
        
        $goods = $model->where($where)->find();
        
        // 处理商品属性
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id = $goods_id")->select(); // 查询商品属性表
        foreach ($goods_attr_list as $key => $val) {
            $goods_attr_list[$key]['attr_name'] = $goods_attribute[$val['attr_id']];
        }
        $goods['goods_attr_list'] = $goods_attr_list ? $goods_attr_list : '';
        
        $goods['goods_attr_list'] = $hello = explode('，', $goods['goods_content']);
        
        // 处理商品规格
        $Model = new \Think\Model();
        // 商品规格 价钱 库存表 找出 所有 规格项id
        $keys = M('SpecGoodsPrice')->where("goods_id = $goods_id")->getField("GROUP_CONCAT(`key` SEPARATOR '_') ");
        if ($keys) {
            $specImage    = M('SpecImage')->where("goods_id = $goods_id and src != '' ")->getField("spec_image_id,src");// 规格对应的 图片表， 例如颜色
            $keys         = str_replace('_', ',', $keys);
            $sql          = "SELECT a.name,a.order,b.* FROM __PREFIX__spec AS a INNER JOIN __PREFIX__spec_item AS b ON a.id = b.spec_id WHERE b.id IN($keys) ORDER BY a.order";
            $filter_spec2 = $Model->query($sql);
            foreach ($filter_spec2 as $key => $val) {
                $filter_spec[] = array(
                    'spec_name' => $val['name'],
                    'item_id' => $val['id'],
                    'item' => $val['item'],
                    'src' => $specImage[$val['id']] ? SITE_URL . $specImage[$val['id']] : '',
                );
            }
            $goods['goods_spec_list'] = $filter_spec;
        }
        
        // print_r($filter_spec);
        //print_r($goods_attr_list);
        //print_r($filter_spec);
        
        $goods['goods_content'] = str_replace('/Public/upload/', SITE_URL . "/Public/upload/", $goods['goods_content']);
        $goods['goods_content'] = htmlspecialchars_decode($goods['goods_content']);
        $goods['original_img']  = SITE_URL . $goods['original_img'];
        
        $specs = $goods['goods_spec_list'];
        //print_r($specs);
        $pos  = 0;
        $keys = array();
        foreach ($specs as $key => $value) {
            //$spec_arrays[$value['spec_name']][] = $value;
            if (!array_key_exists($value['spec_name'], $keys))
                $keys[$value['spec_name']] = $pos++;
        }
        //print_r($keys);
        
        foreach ($specs as $key => $value) {
            $value['isClick']                          = 0;
            $spec_arrays[$keys[$value['spec_name']]][] = $value;
            
        }
        
        foreach ($spec_arrays as $key => $value) {
            $spec_arrays[$key][0]['isClick'] = 1;
            
        }
        
        $goods['goods_spec_list'] = $spec_arrays;
        
        $return['goods']            = $goods;
        $return['spec_goods_price'] = M('spec_goods_price')->where("goods_id = $goods_id")->getField("key,price,store_count"); // 规格 对应 价格 库存表
        $return['gallery']          = M('goods_images')->field('image_url')->where(array('goods_id' => $goods_id))->select();
        foreach ($return['gallery'] as $key => $val) {
            $return['gallery'][$key]['image_url'] = SITE_URL . $return['gallery'][$key]['image_url'];
        }
        //获取最近的两条评论
        $latest_comment = M('comment')->where("goods_id={$goods_id} AND is_show=1 AND parent_id=0")->limit(2)->select();
        
        foreach ($latest_comment as $key => $value) {
            $latest_comment[$key]['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
        }
        
        $return['comment'] = $latest_comment ? $latest_comment : '';
        
        if (!$goods) {
            $json_arr = array('status' => -1, 'msg' => '没有该商品', 'result' => '');
        } else {
            $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $return);
        }
        
        $json_str = json_encode($json_arr);
        exit($json_str);
    }
    
    /**
     *  goodsPriceBySpec 获取商品价格
     */
    /*
    public function goodsPriceBySpec()
    {        
        $goods_id = I("goods_id"); // 商品id
        $goods_num = I("goods_num");// 商品数量
        $goods = M('Goods')->where("goods_id = $goods_id")->find();
        $goods_price = $goods['shop_price']; // 商品价格 
        
        // 有选择商品规格
        if(!empty($_REQUEST['spec_list']))
        {
            $spec_item = explode(',', $_REQUEST['spec_list']);
            sort($spec_item);
            $spec_item_key = implode('_', $spec_item);
            $specGoodsPrice = M("SpecGoodsPrice")->where("goods_id = $goods_id and `key` = '$spec_item_key'")->find();
            if($specGoodsPrice)
                $goods_price = $specGoodsPrice['price'];
        }    
        $price = $goods_price * $goods_num; // 商品单价乘以数量        
        
        if(!$price){
            $json_arr = array('status'=>-1,'msg'=>'没有查询到价格','result'=>'');
        }else{
            $json_arr = array('status'=>1,'msg'=>'获取成功','result'=>$price);
        }
        $json_str = json_encode($json_arr);
        exit($json_str);         
    }
    */
    
    /**
     *  获取商品的缩略图
     */
    function goodsThumImages(){
        $goods_id = I('goods_id');
        $width    = I('width');
        $height   = I('height');
        $img_url  = SITE_URL . goods_thum_images($goods_id, $width, $height);
        $image    = file_get_contents($img_url);  //假设当前文件夹已有图片001.jpg
        header('Content-type: image/jpg');
        exit($image);
    }
    
    /**
     * 获取某个商品的评价
     */
    function getGoodsComment(){
        $goods_id  = I('goods_id');
        $where     = " goods_id = $goods_id  and is_show = 1";
        $count     = M('comment')->where($where)->count();
        $_GET['p'] = $_REQUEST['p'];
        $page      = new Page($count, 10);
        $list      = M('comment')->where($where)->order("comment_id desc")->limit("{$page->firstRow},{$page->listRows}")->select();
        foreach ($list as $key => $val) {
            if (empty($val['img'])) {
                $list[$key]['img'] = '';
                continue;
            }
            
            $val['img'] = unserialize($val['img']);
            
            foreach ($val['img'] as $k => $v) {
                $val['img'][$k] = SITE_URL . $v;
            }
            $list[$key]['img'] = $val['img'];
        }
        exit(json_encode(array('status' => 1, 'msg' => '获取成功', 'result' => $list)));
    }
    
    /**
     * 收藏商品
     */
    function collectGoods(){
        $this->user_id = I('user_id');
        $goods_id      = I('goods_id');
        $type          = I('type', 0);
        $count         = M('Goods')->where("goods_id = $goods_id")->count();
        if ($count == 0) exit(json_encode(array('status' => 1, 'msg' => '收藏商品不存在', 'result' => array())));
        //删除收藏商品
        if ($type == 1) {
            
            M('GoodsCollect')->where("user_id = {$this->user_id} and goods_id = $goods_id")->delete();
            exit(json_encode(array('status' => 1, 'msg' => '成功取消收藏', 'result' => array())));
        }
        $count = M('GoodsCollect')->where("user_id = {$this->user_id} and goods_id = $goods_id")->count();
        if ($count > 0) exit(json_encode(array('status' => 1, 'msg' => '您已收藏过该商品', 'result' => array())));
        M('GoodsCollect')->add(array(
            'goods_id' => $goods_id,
            'user_id' => $this->user_id,
            'add_time' => time(),
        ));
        exit(json_encode(array('status' => 1, 'msg' => '收藏成功', 'result' => array())));
    }

    /**
     * 收藏商品
     */
    function collectSeller(){
        $this->user_id = I('user_id');
        $seller_id      = I('seller_id');
        $type          = I('type', 0);
        $count         = M('store')->where("store_id = $seller_id")->count();
        if ($count == 0) exit(json_encode(array('status' => 1, 'msg' => '收藏店铺不存在', 'result' => array())));
        //删除收藏商品
        if ($type == 1) {
            M('StoreCollect')->where("user_id = {$this->user_id} and goods_id = $goods_id")->delete();
            exit(json_encode(array('status' => 1, 'msg' => '成功取消收藏', 'result' => array())));
        }
        $count = M('StoreCollect')->where("user_id = {$this->user_id} and store_id = $seller_id")->count();
        if ($count > 0) exit(json_encode(array('status' => 1, 'msg' => '您已收藏过该店铺', 'result' => array())));
        M('StoreCollect')->add(array(
            'store_id' => $seller_id,
            'user_id' => $this->user_id,
            'add_time' => time(),
        ));
        exit(json_encode(array('status' => 1, 'msg' => '收藏成功', 'result' => array())));
    }

    /**
     * 猜你喜欢/热门推荐
     */
    public function guessYouLike(){
        $p               = I('p', 1);
        $favourite_goods = M('goods')->where("is_recommend=1 and is_on_sale=1")->order('goods_id DESC')->page($p, 10)->getField('goods_id,cat_id,goods_sn,goods_name,shop_price,comment_count');//首页/购物车/我的 推荐商品
        $goods           = array();
        foreach ($favourite_goods as $k => $v) {
            $goods[] = $v;
        }
        $json_arr = array('status' => 1, 'msg' => '获取成功', 'result' => $goods);
        $json_str = json_encode($json_arr, true);
        
        exit($json_str);
    }
    
}