<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1>Category</h1>
<p><a href="<?php echo $TQApp->createUrl('category/create'); ?>">Thêm mới</a></p>

<hr/>
<?php if(!empty($list)): ?>
	<?php foreach ($list as $item): ?>
	<p>
		<a href="<?php echo $TQApp->createUrl('category/view',array('id'=>$item->id)); ?>"><?php echo $item->title?></a> 
		(
			<a href="<?php echo $TQApp->createUrl('category/update',array('id'=>$item->id)); ?>">Sửa</a> | 
			<a href="<?php echo $TQApp->createUrl('category/delete',array('id'=>$item->id)); ?>" class="btnDelete">Xóa</a>
		)
	</p>
	<?php endforeach;?>
<?php endif;?>
