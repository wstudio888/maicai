<?php if (!defined('THINK_PATH')) exit();?><div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <td style="width: 1px;" class="text-center">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
            </td>
            <td class="text-center">
                用户
            </td>
            <td class="text-center">
                评论内容
            </td>
            <td class="text-center">
                商品
            </td>            
            <td class="text-center">
                评论时间
            </td>
            <td class="text-center">
                ip地址
            </td>
            <td class="text-center">操作</td>
        </tr>
        </thead>
        <tbody>

        <?php if(is_array($comment_list)): $i = 0; $__LIST__ = $comment_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                <td class="text-center">
                    <input type="checkbox" name="selected[]" value="<?php echo ($list["comment_id"]); ?>">
                </td>
                <td class="text-center"><?php echo ($list["nick_name"]); ?></td>
                <td class="text-left"><?php echo ($list["content"]); ?></td>
                <td class="text-left"><a target="_blank" href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$list[goods_id]));?>"><?php echo ($goods_list[$list[goods_id]]); ?></a></td>               
                <td class="text-center"><?php echo (date('Y-m-d H:i',$list["add_time"])); ?></td>
                <td class="text-center"><?php echo ($list["ip_address"]); ?></td>

                <td class="text-center">
                    <a href="<?php echo U('comment/detail',array('id'=>$list[comment_id]));?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-eye"></i></a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>

        </tbody>
    </table> 
</div>
<div class="row">
    <div class="col-sm-6 text-left"></div>
    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
</div>
<script>
    $(".pagination a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
</script>