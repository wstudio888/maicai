<?php if (!defined('THINK_PATH')) exit();?><ul class="nav nav-tabs">
  <?php if(is_array($specList)): $k = 0; $__LIST__ = $specList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><li class="<?php echo ($key ==1?'active':''); ?>"><a data-toggle="tab" data-href="javascript:void(0);" onclick="ajax_get_data(<?php echo ($list[id]); ?>);"><?php echo ($list["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>    
</ul>          
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="spec_item_table">
            <thead>
            <tr>
                <th class="sorting text-left">规格名称</th>
                <th class="sorting text-left">规格项</th>
                <th class="sorting text-left">操作</th> 
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($specItemList)): $i = 0; $__LIST__ = $specItemList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list2): $mod = ($i % 2 );++$i;?><tr>
                    <td class="text-left"><?php echo ($specList[$list2[spec_id]][name]); ?></td>
                    <td class="text-right">                         
                        <input type="text" class="form-control input-sm"  value="<?php echo ($list2["item"]); ?>" name="item[<?php echo ($list2["id"]); ?>]" style="width:300px;"/>
                        <span style="color:#F00; display:none;">请填写内容</span>
                    </td>
                    <td class="text-right">
                        <a href="javascript:void(0);"  class="btn btn-danger delItem" data-spec_item_id="<?php echo ($list2["id"]); ?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
		<div class="pull-right">
             <input type="hidden" name="spec_id" value="<?php echo ($spec_id); ?>" />
             <button data-original-title="保存" type="submit"  class="btn btn-primary">提交保存规格值</button>
        </div>        
    </div>