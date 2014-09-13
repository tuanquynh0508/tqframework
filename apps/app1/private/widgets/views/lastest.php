<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php if(!empty($list)): ?>
<ol>
	<?php foreach($list as $item): ?>
	<li><a href="#"><?php echo $item->title; ?></a></li>
	<?php endforeach; ?>
</ol>
<?php endif; ?>

<?php echo $TQApp->getWebPath(); ?>