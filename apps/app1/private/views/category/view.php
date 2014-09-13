<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h1><?php echo $model->title; ?></h1>

<p><a href="<?php echo $TQApp->createUrl('category/index'); ?>">Quay lại</a></p>

<p>Ngày tạo: <?php echo $model->created; ?></p>
<p>Cập nhật: <?php echo $model->modified; ?></p>
