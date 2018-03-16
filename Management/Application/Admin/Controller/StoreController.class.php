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
 * Date: 2016-05-27
 */

namespace Admin\Controller;

use Admin\Logic\StoreLogic;
use Common\Logic\DadaLogic;

class StoreController extends BaseController
{
    
    //店铺等级
    public function store_grade(){
        $model = M('store_grade');
        $count = $model->where('1=1')->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->order('sg_id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display();
    }
    
    public function grade_info(){
        $sg_id = I('sg_id');
        if ($sg_id) {
            $info = M('store_grade')->where("sg_id=$sg_id")->find();
            $this->assign('info', $info);
        }
        $this->display();
    }
    
    public function grade_info_save(){
        $data = I('post.');
        if ($data['sg_id'] > 0 || $data['act'] == 'del') {
            if ($data['act'] == 'del') {
                if (M('store')->where(array('grade_id' => $data['del_id']))->count() > 0) {
                    respose('该等级下有开通店铺，不得删除');
                } else {
                    $r = M('store_grade')->where("sg_id=" . $data['del_id'])->delete();
                    respose(1);
                }
            } else {
                $r = M('store_grade')->where("sg_id=" . $data['sg_id'])->save($data);
            }
        } else {
            $r = M('store_grade')->add($data);
        }
        if ($r) {
            $this->success('编辑成功', U('Store/store_grade'));
        } else {
            $this->error('提交失败');
        }
    }
    
    public function store_class(){
        $model = M('store_class');
        $count = $model->where('1=1')->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->order('sc_id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display();
    }
    
    //店铺分类
    public function class_info(){
        $sc_id = I('sc_id');
        if ($sc_id) {
            $info = M('store_class')->where("sc_id=$sc_id")->find();
            $this->assign('info', $info);
        }
        $this->display();
    }
    
    public function class_info_save(){
        $data = I('post.');
        if ($data['sc_id'] > 0 || $data['act'] == 'del') {
            if ($data['act'] == 'del') {
                if (M('store')->where(array('sc_id' => $data['del_id']))->count() > 0) {
                    respose('该分类下有开通店铺，不得删除');
                } else {
                    $r = M('store_class')->where("sc_id=" . $data['del_id'])->delete();
                    respose(1);
                }
            } else {
                $r = M('store_class')->where("sc_id=" . $data['sc_id'])->save($data);
            }
        } else {
            $r = M('store_class')->add($data);
        }
        if ($r) {
            $this->success('编辑成功', U('Store/store_class'));
        } else {
            $this->error('提交失败');
        }
    }
    
    //普通店铺列表
    public function store_list(){
        $model              = M('store');
        $map['is_own_shop'] = 0;
        $grade_id           = I("grade_id");
        if ($grade_id > 0) $map['grade_id'] = $grade_id;
        $sc_id = I('sc_id');
        if ($sc_id > 0) $map['sc_id'] = $sc_id;
        $store_state = I("store_state");
        if ($store_state > 0) $map['store_state'] = $store_state;
        $seller_name = I('seller_name');
        if ($seller_name) $map['seller_name'] = array('like', "%$seller_name%");
        $store_name = I('store_name');
        if ($store_name) $map['store_name'] = array('like', "%$store_name%");
        $count = $model->where($map)->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->where($map)->order('store_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        
        $show = $Page->show();
        $this->assign('page', $show);
        $store_grade = M('store_grade')->getField('sg_id,sg_name');
        $this->assign('store_grade', $store_grade);
        $this->assign('store_class', M('store_class')->getField('sc_id,sc_name'));
        $this->display();
    }
    
    /*添加店铺*/
    public function store_add(){
        if (IS_POST) {
            // 接收到达达数据
            $data = M('dada')->create();
            
            $store_name  = I('store_name');
            $user_name   = I('user_name');
            $seller_name = I('seller_name');
            if (M('store')->where("store_name='$store_name'")->count() > 0) {
                $this->error("店铺名称已存在");
            }
            if (M('seller')->where("seller_name='$seller_name'")->count() > 0) {
                $this->error("此名称已被占用");
            }
            $user_id = M('users')->where("email='$user_name' or mobile='$user_name'")->getField('user_id');
            
            if ($user_id) {
                if (M('store')->where(array('user_id' => $user_id))->count() > 0) {
                    $this->error("该会员已经申请开通过店铺");
                }
            }
            $store = array('store_name' => $store_name, 'user_name' => $user_name, 'store_state' => 1,
                'seller_name' => $seller_name, 'password' => I('password'),
                'store_time' => time(), 'is_own_shop' => I('is_own_shop')
            );
            M()->startTrans();
            $storeLogic = new StoreLogic();
            $store_id   = $storeLogic->addStore($store);
            if ($store_id) {
                // 处理自己这方面达达信息
                $dadaModel = D('Dada');
                $dadares   = $dadaModel->getOne($store_id); // 查询是否存在
                if (empty($dadares)) {
                    $data['store_id'] = $store_id;
                    // 处理达达
                    $dadaLogic = new DadaLogic();
                    if (!$dadaLogic->addstore($data)) {
                        M()->rollback();
                        $this->error("达达门店添加失败");
                    }
                }
                M()->commit();
                if (I('is_own_shop') == 1) {
                    $this->success('店铺添加成功', U('Store/store_own_list'));
                } else {
                    $this->success('店铺添加成功', U('Store/store_list'));
                }
                exit;
            } else {
                $this->error("店铺添加失败");
            }
        }
        $is_own_shop = I('is_own_shop', 1);
        $this->assign('is_own_shop', $is_own_shop);
        $this->display();
    }
    
    /*验证店铺名称，店铺登陆账号*/
    public function store_check(){
        $store_name  = I('store_name');
        $seller_name = I('seller_name');
        $user_name   = I('user_name');
        $res         = array('stat' => 'ok');
        if ($store_name && M('store')->where("store_name='$store_name'")->count() > 0) {
            $res = array('stat' => 'fail', 'msg' => '店铺名称已存在');
        }
        
        if (!empty($user_name)) {
            if (!check_email($user_name) && !check_mobile($user_name)) {
                $res = array('stat' => 'fail', 'msg' => '店主账号格式有误');
            }
            if (M('users')->where("email='$user_name' or mobile='$user_name'")->count() > 0) {
                $res = array('stat' => 'fail', 'msg' => '会员名称已被占用');
            }
        }
        
        if ($seller_name && M('seller')->where("seller_name='$seller_name'")->count() > 0) {
            $res = array('stat' => 'fail', 'msg' => '此账号名称已被占用');
        }
        respose($res);
    }
    
    /*编辑自营店铺*/
    public function store_edit(){
        if (IS_POST) {
            $data = I('post.');
            if (M('store')->where("store_id=" . $data['store_id'])->save($data)) {
                $this->success('编辑店铺成功', U('Store/store_own_list'));
                exit;
            } else {
                $this->error('编辑失败');
            }
        }
        $store_id = I('store_id', 0);
        $store    = M('store')->where("store_id=$store_id")->find();
        $this->assign('store', $store);
        $this->display();
    }
    
    //编辑外驻店铺
    public function store_info_edit(){
        if (IS_POST) {
            $map   = I('post.');
            $store = $map['store'];
            unset($map['store']);
            $a = M('store')->where(array('store_id' => $map['store_id']))->save($store);
            $b = M('store_apply')->where(array('user_id' => $map['user_id']))->save($map);
            if ($b || $a) {
                if ($store['store_state'] == 0) {
                    //关闭店铺，同时下架店铺所有商品
                    M('goods')->where(array('store_id' => $map['store_id']))->save(array('is_on_sale' => 0));
                }
                $this->success('编辑店铺成功', U('Store/store_list'));
                exit;
            } else {
                $this->error('编辑失败');
            }
        }
        $store_id = I('store_id');
        if ($store_id > 0) {
            $store = M('store')->where("store_id=$store_id")->find();
            $this->assign('store', $store);
            $apply = M('store_apply')->where('user_id=' . $store['user_id'])->find();
            $this->assign('apply', $apply);
        }
        $this->assign('store_grade', M('store_grade')->getField('sg_id,sg_name'));
        $this->assign('store_class', M('store_class')->getField('sc_id,sc_name'));
        $province = M('region')->where(array('parent_id' => 0, 'level' => 1))->select();
        $this->assign('province', $province);
        $this->display();
    }
    
    /*删除店铺*/
    public function store_del(){
        $store_id = I('del_id');
        if ($store_id > 1) {
            $store = M('store')->where("store_id=$store_id")->find();
            if (M('goods')->where("store_id=$store_id")->count() > 0) {
                respose('该店铺有发布商品，不得删除');
            } else {
                M('store')->where("store_id=$store_id")->delete();
                M('seller')->where("store_id=$store_id")->delete();
                adminLog("删除店铺" . $store['store_name']);
                respose(1);
            }
        } else {
            respose('基础自营店，不得删除');
        }
    }
    
    //店铺信息
    public function store_info(){
        $store_id = I('store_id');
        $store    = M('store')->where("store_id=" . $store_id)->find();
        $this->assign('store', $store);
        $apply = M('store_apply')->where("user_id=" . $store['user_id'])->find();
        $this->assign('apply', $apply);
        $bind_class_list = M('store_bind_class')->where("store_id=" . $store_id)->select();
        $goods_class     = M('goods_category')->getField('id,name');
        for ($i = 0, $j = count($bind_class_list); $i < $j; $i++) {
            $bind_class_list[$i]['class_1_name'] = $goods_class[$bind_class_list[$i]['class_1']];
            $bind_class_list[$i]['class_2_name'] = $goods_class[$bind_class_list[$i]['class_2']];
            $bind_class_list[$i]['class_3_name'] = $goods_class[$bind_class_list[$i]['class_3']];
        }
        $this->assign('bind_class_list', $bind_class_list);
        $this->display();
    }
    
    //自营店铺列表
    public function store_own_list(){
        $model              = M('store');
        $map['is_own_shop'] = 1;
        $grade_id           = I("grade_id");
        if ($grade_id > 0) $map['grade_id'] = $grade_id;
        $sc_id = I('sc_id');
        if ($sc_id > 0) $map['sc_id'] = $sc_id;
        $store_state = I("store_state");
        if ($store_state > 0) $map['store_state'] = $store_state;
        $seller_name = I('seller_name');
        if ($seller_name) $map['seller_name'] = array('like', "%$seller_name%");
        $store_name = I('store_name');
        if ($store_name) $map['store_name'] = array('like', "%$store_name%");
        $count = $model->where($map)->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->where($map)->order('store_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $show = $Page->show();
        $this->assign('page', $show);
        $store_grade = M('store_grade')->getField('sg_id,sg_name');
        $this->assign('store_grade', $store_grade);
        $this->assign('store_class', M('store_class')->getField('sc_id,sc_name'));
        $this->display();
    }
    
    //店铺申请列表
    public function apply_list(){
        $model = M('seller_apply');
        
        $grade_id = I("grade_id");
        if ($grade_id > 0) $map['grade_id'] = $grade_id;
        $sc_id = I('sc_id');
        if ($sc_id > 0) $map['sc_id'] = $sc_id;
        $seller_name = I('seller_name');
        if ($seller_name) $map['seller_name'] = array('like', "%$seller_name%");
        $store_name = I('store_name');
        if ($store_name) $map['store_name'] = array('like', "%$store_name%");
        $count = $model->where($map)->count();
        $Page  = new \Think\Page($count, 10);
        $list  = $model->where($map)->order('add_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        //print_r($list);
        foreach ($list as $key => $value) {
            //print_r(M('users')->where(array("user_id"=>$value['user_id']))->find());
            $list[$key]['nick_name'] = M('users')->where(array("user_id" => $value['user_id']))->getField("nick_name");
        }
        $this->assign('list', $list);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('store_grade', M('store_grade')->getField('sg_id,sg_name'));
        $this->assign('store_class', M('store_class')->getField('sc_id,sc_name'));
        $this->display();
    }
    
    public function apply_del(){
        $id = I('del_id');
        M('seller_apply')->where(array('id' => $id))->delete();
        $this->success('操作成功', U('Store/apply_list'));
        
    }
    
    //经营类目申请列表
    public function apply_class_list(){
        $state = I('state');
        if ($state != "") {
            $bind_class = M('store_bind_class')->where(array('state' => $state))->select();
        } else {
            $bind_class = M('store_bind_class')->select();
        }
        $goods_class = M('goods_category')->getField('id,name');
        for ($i = 0, $j = count($bind_class); $i < $j; $i++) {
            $bind_class[$i]['class_1_name'] = $goods_class[$bind_class[$i]['class_1']];
            $bind_class[$i]['class_2_name'] = $goods_class[$bind_class[$i]['class_2']];
            $bind_class[$i]['class_3_name'] = $goods_class[$bind_class[$i]['class_3']];
            $store                          = M('store')->where("store_id=" . $bind_class[$i]['store_id'])->find();
            $bind_class[$i]['store_name']   = $store['store_name'];
            $bind_class[$i]['seller_name']  = $store['seller_name'];
        }
        $this->assign('bind_class', $bind_class);
        $this->display();
    }
    
    //查看-添加店铺经营类目
    public function store_class_info(){
        $store_id = I('store_id');
        $store    = M('store')->where(array('store_id' => $store_id))->find();
        $this->assign('store', $store);
        if (IS_POST) {
            $data          = I('post.');
            $data['state'] = empty($store['is_own_shop']) ? 0 : 1;
            $where         = 'class_3 =' . $data['class_3'] . ' and store_id=' . $store_id;
            if (M('store_bind_class')->where($where)->count() > 0) {
                $this->error('该店铺已申请过此类目');
            }
            if (M('store_bind_class')->add($data)) {
                adminLog('添加店铺经营类目，类目编号:' . $data['class_3'] . ',店铺编号:' . $data['store_id']);
                $this->success('添加经营类目成功');
                exit;
            } else {
                $this->error('操作失败');
            }
        }
        $bind_class_list = M('store_bind_class')->where('store_id=' . $store_id)->select();
        $goods_class     = M('goods_category')->getField('id,name');
        for ($i = 0, $j = count($bind_class_list); $i < $j; $i++) {
            $bind_class_list[$i]['class_1_name'] = $goods_class[$bind_class_list[$i]['class_1']];
            $bind_class_list[$i]['class_2_name'] = $goods_class[$bind_class_list[$i]['class_2']];
            $bind_class_list[$i]['class_3_name'] = $goods_class[$bind_class_list[$i]['class_3']];
        }
        $this->assign('bind_class_list', $bind_class_list);
        $cat_list = M('goods_category')->where("parent_id = 0")->select();
        $this->assign('cat_list', $cat_list);
        $this->display();
    }
    
    public function apply_class_save(){
        $data = I('post.');
        if ($data['act'] == 'del') {
            $r = M('store_bind_class')->where("bid=" . $data['del_id'])->delete();
            respose(1);
        } else {
            $data = I('get.');
            $r    = M('store_bind_class')->where("bid=" . $data['bid'])->save(array('state' => 1));
        }
        if ($r) {
            $this->success('操作成功', U('Store/apply_class_list'));
        } else {
            $this->error('提交失败');
        }
    }
    
    //店铺申请信息详情
    public function apply_info(){
        $id          = I('id');
        $apply       = M('store_apply')->where("id=$id")->find();
        $goods_cates = M('goods_category')->getField('id,name,commission');
        if (!empty($apply['store_class_ids'])) {
            $store_class_ids = unserialize($apply['store_class_ids']);
            foreach ($store_class_ids as $val) {
                $cat               = explode(',', $val);
                $bind_class_list[] = array('class_1' => $goods_cates[$cat[0]]['name'], 'class_2' => $goods_cates[$cat[1]]['name'],
                    'class_3' => $goods_cates[$cat[2]]['name'] . '(分佣比例：' . $goods_cates[$cat[2]]['commission'] . '%)',
                    'value' => $val,
                );
            }
            $this->assign('bind_class_list', $bind_class_list);
        }
        $this->assign('apply', $apply);
        $apply_log = M('admin_log')->where(array('log_type' => 1))->select();
        $this->assign('apply_log', $apply_log);
        $this->assign('store_grade', M('store_grade')->select());
        $this->display();
    }
    
    //审核店铺开通申请
    public function review(){
        $data = I('post.');
        if ($data['id']) {
            $apply = M('store_apply')->where(array('id' => $data['id']))->find();
            if (M('store_apply')->where("id=" . $data['id'])->save($data)) {
                if ($data['apply_state'] == 1) {
                    $users = M('users')->where(array('user_id' => $apply['user_id']))->find();
                    if (empty($users)) $this->error('申请会员不存在或已被删除，请检查');
                    $store    = array('user_id' => $apply['user_id'], 'seller_name' => $apply['seller_name'],
                        'user_name' => empty($users['email']) ? $users['mobile'] : $users['email'],
                        'grade_id' => $data['sg_id'], 'store_name' => $apply['store_name'], 'sc_id' => $apply['sc_id'],
                        'company_name' => $apply['company_name'], 'store_phone' => $apply['store_person_mobile'],
                        'store_address' => empty($apply['store_address']) ? '待完善' : $apply['store_address'],
                        'store_time' => time(), 'store_state' => 1, 'store_qq' => $apply['store_person_qq'],
                    );
                    $store_id = M('store')->add($store);//通过审核开通店铺
                    if ($store_id) {
                        $seller = array('seller_name' => $apply['seller_name'], 'user_id' => $apply['user_id'],
                            'group_id' => 0, 'store_id' => $store_id, 'is_admin' => 1
                        );
                        M('seller')->add($seller);//点击店铺管理员
                        //绑定商家申请类目
                        if (!empty($apply['store_class_ids'])) {
                            $goods_cates     = M('goods_category')->where(array('level' => 3))->getField('id,name,commission');
                            $store_class_ids = unserialize($apply['store_class_ids']);
                            foreach ($store_class_ids as $val) {
                                $cat        = explode(',', $val);
                                $bind_class = array('store_id' => $store_id, 'commis_rate' => $goods_cates[$cat[2]]['commission'],
                                    'class_1' => $cat[0], 'class_2' => $cat[1], 'class_3' => $cat[2], 'state' => 1);
                                M('store_bind_class')->add($bind_class);
                            }
                        }
                    }
                    adminLog($apply['store_name'] . '开店申请审核通过', 1);
                } else if ($data['apply_state'] == 2) {
                    adminLog($apply['store_name'] . '开店申请审核未通过，备注信息：' . $data['review_msg'], 1);
                }
                $this->success('操作成功', U('Store/apply_list'));
            } else {
                $this->error('提交失败');
            }
        }
    }
    
    public function reopen_list(){
        $this->assign('store_class', M('store_class')->getField('sc_id,sc_name'));
        $this->display();
    }
    
    public function change(){
        D('seller_apply')->where(array("id" => $_GET['id']))->save($_GET);
    }
	
	/*
		开发者：W
		更新时间：20180314
	
	*/
	
	//店铺活动列表
    public function store_article_list(){
		
		$store_id = I('store_id');
		$map['store_id'] = $store_id;
		
		$this->assign('store_id',$store_id);
		
        $Article =  M('Store_article'); 
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
		
        $where = " 1 = 1 ";
		$where .= " and store_id = ".$store_id;    
        $keywords = trim(I('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";

        $res = $Article->where($where)->order('article_id desc')->page("$p,$size")->select();
        $count = $Article->where($where)->count();// 查询满足要求的总记录数
        $pager = new \Think\Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        $page = $pager->show();//分页显示输出
		
		if($res){
        	foreach ($res as $val){
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }
		
		$this->assign('store_id',$store_id);
        $this->assign('cat_id',$cat_id);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$page);// 赋值分页输出
        $this->display();
    }
    
    /*添加店铺活动*/
    public function store_article_add(){
		
		$store_id = I('store_id');
		$store_name = M('store')->field('store_name')->where(array('store_id'=>$store_id))->find();
	
		$this->assign('store_id',$store_id);
		$this->assign('store_name',$store_name['store_name']);
		$this->assign('act','add');
		
        $this->display();
    }
    
    /*编辑店铺活动*/
    public function store_article_edit(){
		
        $info = array();
        $info['publish_time'] = time()+3600*24;
		
        if(I('GET.article_id')){
           $article_id = I('GET.article_id');
           $info = D('store_article')->where('article_id='.$article_id)->find();
        }
		
		$store_id = I('get.store_id');
      
		$this->assign('store_id',$store_id);
        $this->assign('act','edit');
        $this->assign('info',$info);
        $this->initEditor();
        $this->display();
    }
	
	public function store_article_handle(){
		$data = I('post.');
        $data['publish_time'] = strtotime($data['publish_time']);
        //$data['content'] = htmlspecialchars(stripslashes($_POST['content']));
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Store/store_article_list');
        if($data['act'] == 'add'){
//            if(array_key_exists($data['cat_id'],$this->article_main_system_id)){
//                $this->error("不能在系统保留分类下添加文章,操作失败",$referurl);
//            }
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time(); 
            $r = D('store_article')->add($data);
        }
        
        if($data['act'] == 'edit'){ 
            $r = D('store_article')->where('article_id='.$data['article_id'])->save($data);
        }
       
        if($data['act'] == 'del'){
        	$r = D('store_article')->where('article_id='.$data['article_id'])->delete();
        	if($r) exit(json_encode(1));       	
        }
        if($r){
            $this->success("操作成功",$referurl);
        }else{
            $this->error("操作失败",$referurl);
        }
	}
    
    /*删除店铺活动*/
    public function store_article_del(){
        $store_id = I('del_id');
        if ($store_id > 1) {
            $store = M('store')->where("store_id=$store_id")->find();
            if (M('goods')->where("store_id=$store_id")->count() > 0) {
                respose('该店铺有发布商品，不得删除');
            } else {
                M('store')->where("store_id=$store_id")->delete();
                M('seller')->where("store_id=$store_id")->delete();
                adminLog("删除店铺" . $store['store_name']);
                respose(1);
            }
        } else {
            respose('基础自营店，不得删除');
        }
    }
	
	    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'article')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'article')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'article')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'article')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'article')));
        $this->assign("URL_Home", "");
    }
}