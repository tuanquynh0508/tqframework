<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h1>Test - Form</h1>

<?php
if(!empty($form->error)) {
	foreach ($form->error as $item) {
		foreach ($item as $mes) {
			echo '<p class="error">'.$mes.'</p>';
		}
	}
}
?>

<form method="post" action="">
<?php echo $form->hiddenField('delete_img'); ?>

<p>
	Fullname (*): <br/>
	<?php
	echo $form->textField('fullname',array(
		//'style' => 'width: 300px;',
		'classError' => 'customClassError',
	));
	?>
</p>

<p>
	Sex: <br/>
	<?php echo $form->radioField('sex',array('0'=>'Nữ', '1'=>'Nam', '2'=>'Pede')); ?>
</p>

<p>
	Job: <br/>
	<?php echo $form->checkboxField('job',array('1'=>'Câu hỏi 1', '2'=>'Câu hỏi 2', '3'=>'Câu hỏi 3'),array(
		//'itemTemplate' => '<p><label>{input}{label}</label></p>'
		'itemTemplate' => '{input}<b>{label}</b> | '
	)); ?>
</p>

<p>
	Danh mục: <br/>
	<?php echo $form->selectField('category',
	array(
		'1'=>'Kỹ thuật',
		'2'=>'Kinh doanh',
		'3'=>'Nhân sự'
	),array(
		//'multiple' => 'multiple'
	)); ?>
</p>

<p>
	Age: <br/>
	<?php echo $form->textField('age'); ?>
</p>

<p>
	Email (*): <br/>
	<?php echo $form->textField('email'); ?>
</p>

<p>
	Url: <br/>
	<?php echo $form->textField('url'); ?>
</p>

<p>
	Message: <br/>
	<?php echo $form->textareaField('message'); ?>
</p>

<p><button type="submit">Submit</button></p>

</form>