<?php
return array(
	'appRoot' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'.DS.'..',
	'appName' => 'TQ lightweight framework',
	
	'import' => array(		
		'tqapp.vendor.foot',
	),
	
	'appDB' => array(
		'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
		'port' => 3306,
		'database' => 'tqframework',
		'charset' => 'utf8',
	),
	
	'appEvn' => array(
		'language' => 'en',
		'time_zone' => 'Asia/Ho_Chi_Minh',
		'cookie_time_out' => 60*60*24*7,
		'cache' => array()
	),
	
	'appRouter' => array(		
		'/contact.html' => 'site/contact',
		'/longpolling/<lastid:\d+>/<timestamp:\w+>' => 'chat/longpoll',
		'/post/<slug:[A-Za-z0-9\-]+>.html' => 'post/detail',
		'/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',		
		'/<controller:\w+>/<id:\d+>' => '<controller>/view',
		'/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
		'/<controller:\w+>' => '<controller>/index',
		'/' => 'site/index',
	),
	
);