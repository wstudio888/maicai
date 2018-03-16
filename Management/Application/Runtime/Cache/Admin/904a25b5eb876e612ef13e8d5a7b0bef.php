<?php if (!defined('THINK_PATH')) exit();?><form method="post" enctype="multipart/form-data" target="_blank" id="goods_list_form">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td style="width: 1px;" class="text-center">

                </td>
                <td class="text-right">
                    <a href="javascript:sort('goods_id');">ID</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('goods_name');">商品名称</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('goods_sn');">货号</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('cat_id');">分类</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('shop_price');">价格</a>
                </td>
                <td class="text-center">
                    <a href="javascript:sort('is_recommend');">推荐</a>
                </td>
                <td class="text-center">
                    <a href="javascript:sort('is_new');">新品</a>
                </td>
                <td class="text-center">
                    <a href="javascript:sort('is_hot');">热卖</a>
                </td>
                <td class="text-left">
                    <a href="javascript:void(0);">库存</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('is_on_sale');">上/下架</a>
                </td>
                <td class="text-left">
                    <a href="javascript:sort('goods_state');">审核状态</a>
                </td>
                <td class="text-right">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($goodsList)): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                    <td class="text-center">
                       <input type="checkbox" name="goods_id[]" value="<?php echo ($list["goods_id"]); ?>"/>
                    </td>
                    <td class="text-right"><?php echo ($list["goods_id"]); ?></td>
                    <td class="text-left"><?php echo (getSubstr($list["goods_name"],0,33)); ?></td>
                    <td class="text-left"><?php echo ($list["goods_sn"]); ?></td>
                    <td class="text-left"><?php echo ($catList[$list[cat_id1]][name]); ?></td>
                    <td class="text-left"><?php echo ($list["shop_price"]); ?></td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_recommend] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_recommend',this)"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_new] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_new',this)"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_hot] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_hot',this)"/>
                    </td>
                    <td class="text-left"><?php echo ($list["store_count"]); ?></td>
                    <td class="text-left">
                        <?php if($list[is_on_sale] == 0): ?>下架<?php endif; ?>
                        <?php if($list[is_on_sale] == 1): ?>上架<?php endif; ?>
                    </td>
                    <td class="text-left">
                    <?php if($list[goods_state] == 0): ?>待审核<?php endif; ?>
                    <?php if($list[goods_state] == 1): ?>审核通过<?php endif; ?>
                    <?php if($list[goods_state] == 2): ?>审核失败<?php endif; ?>
                    <?php if($list[goods_state] == 3): ?>违规下架<?php endif; ?>
                    </td>
                    <td class="text-right">
                        <a href="javascript:void(0);" onclick="del('<?php echo ($list[goods_id]); ?>')" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</form>
<div class="row">
    <div class="navbar-form form-inline">
        全选
        <input type="checkbox" onclick="$('input[name=\'goods_id\[\]\']').prop('checked', this.checked);">
        <div class="form-group">
            <select id="func_id" class="form-control" style="width: 120px;" onchange="fuc_change(this);">
                <option value="-1">请选择...</option>
                <option value="0">推荐</option>
                <option value="1">新品</option>
                <option value="2">热卖</option>
                <option value="3">审核商品</option>
            </select>
        </div>
        <div class="form-group" id="state_div" >
            <select id="state_id" class="form-control" style="display: none" onchange="state_change(this);">
                <option value="">请选择...</option>
                <?php if(is_array($goods_state)): foreach($goods_state as $key=>$vo): ?><option value="<?php echo ($key); ?>"><?php echo ($goods_state[$key]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
        <button id="act_button" type="button" onclick="act_submit();" class="btn btn-primary disabled"><i class="fa"></i> 确定</button>
    </div>
    <div class="col-sm-9 text-right"><?php echo ($page); ?></div>
</div>
<script>
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_table('search-form2',cur_page);
    });
</script>