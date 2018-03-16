<?php if (!defined('THINK_PATH')) exit(); if(is_array($goods_category_list)): foreach($goods_category_list as $k=>$v): ?><ul>
        <h5 id="category_id_<?php echo ($v[id]); ?>"><strong><?php echo ($v[name]); ?></strong></h5>
        <?php if(is_array($list)): foreach($list as $k2=>$v2): if($v[id] == $v2['cat_id1']): ?><li><input type="checkbox" name="brand_id[]" value="<?php echo ($v2['id']); ?>" <?php if($type_id === $v2['type_id']): ?>checked="checked"<?php endif; ?> />&nbsp;&nbsp;<?php echo ($v2['name']); ?></li><?php endif; endforeach; endif; ?>
    </ul><?php endforeach; endif; ?>