<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h1>Test Partial, Widget</h1>

<h2>Partial</h2>
<?php echo $this->partial('site.partial.menu',array('active'=>1)); ?>

<h2>Widget</h2>
<?php $lastest = $this->widget('lastest',array('limit'=>2),false); ?>
<p>
	Lastest box
</p>
<?php echo $lastest; ?>
	