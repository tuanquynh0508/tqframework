<?php
/**
 * Tập tin TQApplication class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @see TQClassBase
 * @since 1.0
 */

namespace TQCore;

use TQFramework\TQBase as TQBase;
use TQCore\TQException as TQExceptionHandle;
use TQCore\TQDatabase as TQDatabase;

class TQApplication extends TQClassBase {
	
	/**
	 * @var array, object đường link hoặc đối tượng config
	 */
	private $_config;
	
	/**
	 * @var string đường dẫn thư mục gốc đến ứng dụng
	 */
	private $_webroot;
	
	/**
	 * @var string đường dẫn gốc web ứng dụng
	 */
	private $_webpath;
	
	/**
	 * @var string đường dẫn thư mục ứng dụng
	 */
	private $_pagePath;
	
	/**
	 * @var string tên mã của Controller
	 */
	private $_cid='';
	
	/**
	 * @var string tên mã của Action
	 */
	private $_aid='';
	
	/**
	 * @var object {@link TQUrlResquest}
	 */
	public $request;
	
	/**
	 * @var object {@link TQDatabase}
	 */
	public $db;
	
	/**
	 * @var string ngôn ngữ hiện tại đang sử dụng
	 */
	public $language='en';
	
	/**
	 * @var string tên của ứng dụng
	 */
	public $appName='TQ Framework';
	
	/*
	 * @return string trả về phiên bản của TQFramework
	 */
	public function getVersion() {
		return '1.0.0';
	}
	
	/*
	 * @return string trả về ngày xuất xưởng của TQFramework
	 */
	public function getRelease() {
		return '2014/09/11';
	}
	
	/*
	 * @return string trả về tên tác giả của TQFramework
	 */
	public function getAuthor() {
		return 'Nguyen Nhu Tuan * tuanquynh0508@gmail.com * http://i-designer.net';
	}
	
	/*
	 * @return string trả về tên mã của Controller
	 */
	public function getCid() {
		return $this->_cid;
	}
	
	/*
	 * @param string $cid tên mã của Controller
	 */
	public function setCid($cid) {
		$this->_cid = $cid;
	}
	
	/*
	 * @return string trả về tên mã của Action
	 */
	public function getAid() {
		return $this->_aid;
	}
	
	/*
	 * @param string $aid tên mã của Action
	 */
	public function setAid($aid) {
		$this->_aid = $aid;
	}
	
	/*
	 * @return object trả về đối tượng Config {@link $_config}
	 */
	public function getConfig() {
		return $this->_config;
	}
	
	/*
	 * Kiểm tra sự tồn tại của biến flash
	 * 
	 * @param string $key tên của biến flash
	 * @return boolean true nếu mã flash tồn tại hoặc false ngược lại
	 */
	public function checkFlashMessage($key) {
		$mes = $this->request->getSession('flash_'.$key,'');
		return ($mes!='')?true:false;
	}
	
	/*
	 * Trả về giá trị của biến flash
	 * 
	 * @param string $key tên của biến flash
	 * @return string thông điệp của flash
	 */
	public function getFlashMessage($key) {
		$mes = $this->request->getSession('flash_'.$key,'');
		$this->request->removeSession('flash_'.$key);
		return $mes;
	}
	
	/*
	 * Gán giá trị của biến flash
	 * 
	 * @param string $key tên của biến flash
	 * @param $value giá trị của biến flash
	 */
	public function setFlashMessage($key, $value) {
		$this->request->setSession('flash_'.$key,$value);
	}
	
	/*
	 * Xóa tất cả các biến flash
	 * 	 
	 */
	public function clearFlashMessage() {
		if(!empty($_SESSION)) {
			foreach ($_SESSION as $key => $value) {
				if(preg_match('/flash_(.*)/', $key)) {
					unset($_SESSION[$key]);
				}
			}
		}
	}
	
	/*
	 * Gán giá trị cho biến config
	 * 
	 * Khởi tạo các biến môi trường và biến config cho ứng dụng
	 * 
	 * @param string, object $config đường dẫn hoặc là biến đối tượng config
	 * @throws TQExceptionHandle nếu mà không tìm thấy file config
	 */
	public function setConfig($config) {
		if(is_object($config)) {
			$this->_config = $config;
		} else {
			if(is_readable($config)) {			
					$this->_config = (object) include $config;			
			} else {
				$mes = $this->t('tqbase','Unable to load {file}',array('file'=>$config));
				throw new TQExceptionHandle($mes, 500);
			}
		}
		$this->appName = $this->_config->appName;
		$this->language = $this->_config->appEvn['language'];
		$this->setWebroot($this->_config->appRoot);
		$this->setWebpath();
		$this->setPagePath();
		date_default_timezone_set($this->_config->appEvn['time_zone']);
	}
	
	/*
	 * @return string trả về thư mục gốc ứng dụng
	 */
	public function getWebroot() {
		return $this->_webroot;
	}
	
	/*
	 * @param string $webroot đường dẫn thư mục gốc của ứng dụng
	 */
	public function setWebroot($webroot) {
		$this->_webroot = realpath($webroot).DS;
	}
	
	/*
	 * @return string trả về đường dẫn web của ứng dụng
	 */
	public function getWebpath() {
		return $this->_webpath;
	}
	
	/*
	 * Gán giá trị đường dẫn web của ứng dụng
	 */
	public function setWebpath() {
		$scriptName = str_replace("index.php", "", $_SERVER['PHP_SELF']);		
		$pageURL = 'http';
		if (@$_SERVER["HTTPS"] == "on") { $pageURL .= "s"; }
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$scriptName;
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$scriptName;
		}
		$this->_webpath = $pageURL;
	}
	
	/*
	 * @return string trả về đường dẫn thư mục web
	 */
	function getPagePath() {
		return $this->_pagePath;
	}
	
	/*
	 * Gán giá trị đường dẫn thư mục web
	 */
	function setPagePath() {
		$scriptName = str_replace("/index.php", "", $_SERVER['PHP_SELF']);
		$this->_pagePath = str_replace("$scriptName", "", $_SERVER['REQUEST_URI']);
	}

	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm khởi tạo TQApplication
	 * 
	 * @param string, object đường dẫn file hoặc đối tượng config
	 */
	public function __construct($config) {
		parent::__construct();
		$this->setConfig($config);		
	}
	
	/*
	 * Hàm hủy đối tượng TQApplication
	 */
	public function __destruct() {
		parent::__destruct();
	}
	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm chạy ứng dụng
	 * 
	 * Hàm lấy các thông tin Controller, Action Name
	 * Khởi tạo đối tượng database {@link $db}
	 * Tạo ra đối tượng tĩnh TQApp
	 * Và đăng ký hàm tự động nạp các thư viện của ứng dụng {@link autoLoadLib()}
	 * 
	 * @return object trả về đối tượng TQApplication hiện tại
	 */
	public function run() {
		$config = $this->getConfig();
		//Add Request
		$this->request = TQBase::createApp('TQCore\TQUrlResquest',$this->getConfig());
		$this->setCid($this->request->getControllerName());
		$this->setAid($this->request->getActionName());
		//Add Database connect
		if(!empty($config->appDB)) {
			$this->db = new TQDatabase($config->appDB['database'],$config->appDB['hostname'],$config->appDB['username'],$config->appDB['password'],$config->appDB['port'],$config->appDB['charset']);
		}
		//Add Static
		TQBase::setApp($this);
		//Add auto load lib
		spl_autoload_register(array($this, 'autoLoadLib'));
		return $this;
	}
	
	/*
	 * Thực hiện chạy ứng dụng Controller
	 */
	public function dispatch() {		
		$this->loadController()->run();		
	}
	
	/*
	 * Tự động nạp Controller hiện tại
	 * 
	 * @return object Controller
	 */
	public function loadController() {						
		$cName = ucwords($this->getCid()).'Controller';		
		$this->_tqRequire('tqapp.controllers.'.$cName);
		return TQBase::createApp("TQAPP\\$cName",$this->getConfig());
	}
	
	/*
	 * Nạp các thư viện đã đăng ký trong {@link config.import}
	 * 
	 * @return object trả về đối tượng TQApplication hiện tại
	 */
	public function importByConfig() {
		$import = $this->getConfig()->import;
		if(!empty($import)) {
			foreach ($import as $file) {
				$this->_tqRequire($file);
			}
		}
		return $this;
	}
	
	/*
	 * Hàm tự động nạp các thư viện dành cho ứng dụng
	 * 
	 * Hàm tự động nạp các Model, Form cho ứng dụng
	 * 
	 * @param string $className tên của class cần nạp
	 * @throws TQExceptionHandle nếu mà không tìm thấy file class
	 */
	public function autoLoadLib($className) {
		if (!preg_match('/TQAPP\\\\(.*)/', $className)) return false;
		$fileName = str_replace('TQAPP\\', '', $className);
		$filePath = $this->parseAliasPath('tqapp.models.'.$fileName.'Model');		
		if (is_readable($filePath)) {						
			require $filePath;
		} else {
			$filePath = $this->parseAliasPath('tqapp.forms.'.$fileName);
			if (is_readable($filePath)) {
				require $filePath;
			} else {
				$mes = $this->t('tqbase','Unable to load {file}',array('file'=>$filePath));
				throw new TQExceptionHandle($mes, 500);
			}
		}
	}
	
	/*
	 * Hàm nạp các thư viện vào code
	 * 
	 * Tương tự như các hàm include, require, require_one...Nhưng được viết dành riêng cho TQFramework
	 * 
	 * @param string $alias đường dẫn thư mục được viết dưới dạng alias path, các folde ngăn cách nhau bởi dấu phẩy
	 * @throws TQExceptionHandle nếu mà không tìm thấy file cần nạp
	 */
	protected function _tqRequire($alias) {
		$path = $this->parseAliasPath($alias);
		if(strpos($path, '*')!==false) {
			//Multi Load
			$listFile = glob($path);
			if(!empty($listFile)) {
				foreach ($listFile as $file) {
					//echo $file;
					require $file;
				}
			}
		} else {
			//Load file
			if (is_readable($path)) {
				//echo $path;
				require $path;
			} else {				
				$mes = $this->t('tqbase','Unable to load {file}',array('file'=>$path));
				throw new TQExceptionHandle($mes, 500);
			}
		}
	}
	
	/*
	 * Hàm phân tích alias path
	 * 
	 * @param string $alias đường dẫn thư mục được viết dưới dạng alias path, các folde ngăn cách nhau bởi dấu phẩy
	 * @return string trả về đường dẫn thư mục thật sự
	 */
	public function parseAliasPath($alias) {
		$path = '';
		$a = explode('.', $alias);
		$a[count($a)-1] .= '.php';
		$i = 0;
		foreach ($a as $item) {
			$i++;
			switch ($item) {
				case 'tqroot':
					$path .= $this->getWebroot();
					break;
				case 'tqapp':
					$path .= $this->getWebroot().'private';
					break;
				default:
					$path .= $item;
					break;
			}			
			if($i<count($a)) {
				$path .= DS;
			}			
		}
		return $path;
	}
	
	/*
	 * Hàm phân tích route
	 * 
	 * Hàm này phân tích tên và các biến của router để trả về đường dẫn web thực sự. Tức là từ controller, action và param ta sẽ có đường dẫn urlfriend.
	 * 
	 * @param string $alias router của trang cần load, thường là controller/action
	 * @param array $params mảng các param kèm theo ví dụ array('id'=>1)
	 * @return string trả về đường dẫn web đầy đủ cho router đó
	 */
	public function parseAliasRouter($alias='', $params=array()) {
		if(empty($alias)) return '#';
		
		$a = explode('/', $alias);
		$controller = $a[0];
		$action = $a[1];
		
		$url = '';
		$routers = $this->request->getRouter();
		
		foreach ($routers as $router) {
			$r = str_replace('<controller>', $controller, $router->router);
			$r = str_replace('<action>', $action, $r);
			if (preg_match('%'.$r.'%', $alias)) {
				$url = $router->config;
				break;
			}
		}
		
		$url = preg_replace('/(<controller:.*?>)/', $controller, $url);
		$url = preg_replace('/(<action:.*?>)/', $action, $url);
		
		$url = preg_replace_callback(
		'/<(.*?):.*?>/',
		function($m) use (&$params) {
			if(isset($params[$m[1]])) {
				$tmp = $params[$m[1]];
				unset($params[$m[1]]);
				return $tmp;
			}
		},
		$url);
		
		$getParam = array();
		if(!empty($params)) {
			foreach ($params as $key => $value) {
				$getParam[] = $key.'='.$value; 
			}
			$url.='?'.implode('&', $getParam);
		}		
		$url = str_replace('//', '/', $this->getWebpath().$url);
		return str_replace(':/', '://', $url);
	}
	
	/*
	 * Hàm ngôn ngữ t
	 * 
	 * Hàm này truyền vào một chuỗi cùng với param đi kèm, sau đó trả về một chuỗi theo ngôn ngữ hiện tại
	 * 
	 * @param string $category tên của tập tin ngôn ngữ trong thư mục private/i18n, nếu tập tin không có nó sẽ trả về nguyên thông điệp được truyền vào
	 * @param string $message thông điệp cần dịch, các param kèm theo thông điệp sẽ được ngăn cách trong cặp {}, ví dụ {name}
	 * @parram array $params mảng các biến kèm theo ví dụ array('name'=>'Nguyễn Như Tuấn')
	 * @return string trả về một chuỗi thông điệp đã được dịch
	 */
	public function t($category, $message, $params=array()) {
		$mes = $message;
		$mesCate = array();
		
		$mesFile = realpath(dirname(__FILE__).DS.'..').DS.'i18n'.DS.$this->language.DS.$category.'.php';		
		if(file_exists($mesFile))	{
			$mesCate = include $mesFile;			
		} else {
			$mesFile = $this->parseAliasPath('tqapp.i18n.'.$this->language.'.'.$category);
			if(file_exists($mesFile))	{
				$mesCate = include $mesFile;
			}
		}
		
		if (!empty($mesCate) && array_key_exists($message, $mesCate)) {
			$mes = $mesCate[$message];
		}
		
		$mes = preg_replace_callback(
		'/\{(.*?)\}/',
		function($m) use ($params) {
			if(isset($params[$m[1]])) {				
				return $params[$m[1]];
			}			
		},
		$mes);
		return $mes;
	}
	
}
