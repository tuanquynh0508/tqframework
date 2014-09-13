<?php
//http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/
//http://viralpatel.net/blogs/21-very-useful-htaccess-tips-tricks/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<base href="http://172.16.30.62/mymvc/">
<title>Untitled Document</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1><?php echo "My MVC"; ?></h1>
<p>
	Param: <?php echo $_GET['q']; ?>
</p>
</body>
</html>