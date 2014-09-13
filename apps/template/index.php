<?php
session_start();

define('DS',DIRECTORY_SEPARATOR);
define('TQAPP_DEBUG',true);

include dirname(__FILE__).DS.'..'.DS.'..'.DS.'tqframework'.DS.'TQBase.php';
$config = dirname(__FILE__).DS.'private'.DS.'config'.DS.'config.php';

use TQFramework\TQBase as TQWebapp;
TQWebapp::runApp($config)->run()->importByConfig()->dispatch();