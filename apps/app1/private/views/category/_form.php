<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

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

<p>
	Fullname (*): <br/>
	<?php
	echo $form->textField('title',array(
		'style' => 'width: 300px;',		
	));
	?>
</p>

<p><button type="submit">Submit</button></p>

</form>