<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h1>Category - Update</h1>
<p><a href="<?php echo $TQApp->createUrl('category/index'); ?>">Quay lại</a></p>

<?php echo $this->partial('category._form',array('form'=>$form)); ?>
