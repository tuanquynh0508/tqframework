<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="wrapper col2">
  <div id="header" class="clear">
    <div class="fl_left">
      <h1><a href="index.html">PhotoProwess</a></h1>
      <p>Free CSS Website Template</p>
    </div>
    <div id="topnav">
      <ul>
        <li class="last"><a href="<?php echo $this->createUrl('site/gallery'); ?>">Gallery</a><span>Test Text Here</span></li>
        <li><a href="<?php echo $this->createUrl('site/portfolio'); ?>">Portfolio</a><span>Test Text Here</span></li>
        <li><a href="#">DropDown</a><span>Test Text Here</span>
          <ul>
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
          </ul>
        </li>
        <li><a href="<?php echo $this->createUrl('site/fullWidth'); ?>">Full Width</a><span>Test Text Here</span></li>
        <li><a href="<?php echo $this->createUrl('site/styleDemo'); ?>">Style Demo</a><span>Test Text Here</span></li>
        <li <?php // /echo ($i==$active)?'class="active"':'' ?>><a href="index.html">Homepage</a><span>Test Text Here</span></li>
      </ul>
    </div>
  </div>
</div>
