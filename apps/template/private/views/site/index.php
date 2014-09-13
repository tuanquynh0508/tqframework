<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<h2>Index page</h2>

<?php echo $this->partial('site.partial.menu',array('active'=>1)); ?>

<?php $lastest = $this->widget('test',array()); ?>