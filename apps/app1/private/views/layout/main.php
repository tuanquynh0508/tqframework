<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="<?php echo $TQApp->getWebpath(); ?>js/jquery-2.1.1.min.js"></script>
<script src="<?php echo $TQApp->getWebpath(); ?>js/script.js"></script>
<link href="<?php echo $TQApp->getWebpath(); ?>css/style.css" rel="stylesheet" type="text/css"/>
</head>

<body>
	<h1>Layout</h1>
	
	<?php if($TQApp->checkFlashMessage('success')):?>
	<?php echo '<p class="message-success">'.$TQApp->getFlashMessage('success').'</p>'; ?>
	<?php endif; ?>
	
	<?php if($TQApp->checkFlashMessage('warning')):?>
	<?php echo '<p class="message-warning">'.$TQApp->getFlashMessage('warning').'</p>'; ?>
	<?php endif; ?>	
	
	<?php echo $content; ?>
</body>
</html>