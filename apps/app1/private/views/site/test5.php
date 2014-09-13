<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Test - Create URL, Redirect</h2>

<p><a href="<?php echo $this->createUrl('site/test',array('id'=>1,'page'=>2,'a'=>3)); ?>">Goto Test</a></p>

<p><a href="<?php echo $TQApp->createUrl('site/json',array('id'=>1,'page'=>2,'a'=>3)); ?>">Goto JSON</a></p>

