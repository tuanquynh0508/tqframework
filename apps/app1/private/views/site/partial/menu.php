<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<ul>
	<?php for($i=1;$i<=5;$i++): ?>		
		<li><a href="#" <?php echo ($i==$active)?'class="active"':'' ?>>Menu <?php echo $i; ?></a> | </li>	
	<?php endfor; ?>	
</ul>