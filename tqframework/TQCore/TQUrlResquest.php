<?php
/**
 * Tập tin TQUrlResquest class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @see TQApplication
 * @since 1.0
 */

namespace TQCore;

class TQUrlResquest extends TQApplication {	
	/*
	 * @var array danh sách các router được lấy từ config file
	 */
	private $_router=array();
	
	/*
	 * @var boolean true là có sự kiện post, false là không
	 */
	private $_isPost=false;
	
	/*
	 * @var string tên mã của controller hiện tại
	 */
	private $_controller_name='';
	
	/*
	 * @var string tên mã của action hiện tại
	 */
	private $_action_name='';
	
	/*
	 * @var array danh sách các param được lấy từ GET, POST
	 */
	private $_params=array();
	
	/*
	 * @var string lưu trữ session_id
	 */
	public $sid='';
	
	/*
	 * @var string lưu trữ giá trị agent user
	 */
	public $uAgent='';
	
	/*
	 * @var string lưu trữ IP của client
	 */
	public $clientIp='';
	
	/*
	 * @var boolean là true nếu device hiện tại là mobile và tablet
	 */
	public $isMobile=false;
	
	/*
	 * @var string lưu trữ tên của thiết bị đang xem
	 */
	public $deviceType='Unknow';

	/*
	 * @return array trả về mảng router hiện tại được lấy ra từ config và đã được chế biến lại
	 */
	function getRouter() {
		return $this->_router;
	}
	
	/*
	 * Hàm phân tích các giá trị router được lấy ra từ config và gán vào biến {@link $_router}
	 */
	function setRouter() {
		$routers = array();		
		$config = $this->getConfig();
		foreach ($config->appRouter as $pattern => $router) {			
			$router_item = new \stdClass;
			$router_item->config = $pattern;
			$router_item->router = $router;
			$router_item->pattern = preg_replace('/<(.*?):(.*?)>/', '(${2})', $pattern);
			$router_item->pattern = preg_replace('/([<|>])/', '', $router_item->pattern);
			$router_item->pattern = '%'.$router_item->pattern.'%';			
			$this->_router[] = $router_item;
		}		 
	}
	
	/*
	 * @return boolean trả về giá trị cảu biến {@link $_isPost}
	 */
	function getIsPost() {
		return $this->_isPost;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_isPost}
	 * 
	 * @param boolean $isPost
	 */
	function setIsPost($isPost) {
		$this->_isPost = $isPost;
	}
	
	/*
	 * @return string trả về giá trị cảu biến {@link $_controller_name}
	 */
	function getControllerName() {
		return $this->_controller_name;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_controller_name}
	 * 
	 * @param string $controller_name
	 */
	function setControllerName($controller_name) {
		$this->_controller_name = $controller_name;
	}
	
	/*
	 * @return string trả về giá trị cảu biến {@link $_action_name}
	 */
	function getActionName() {
		return $this->_action_name;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_action_name}
	 * 
	 * @param string $action_name
	 */
	function setActionName($action_name) {
		$this->_action_name = $action_name;
	}
	
	/*
	 * @return string trả về giá trị cảu biến {@link $_params}
	 */
	function getParams() {
		return $this->_params;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_params}
	 * 
	 * @param array $params
	 */
	function setParams($params) {
		$this->_params = $params;
	}
	
	/*
	 * Hàm thêm phần tử vào biến {@link $_params}
	 * 
	 * @param string $name tên của biến
	 * @param $value giá trị của biến
	 */
	function addParams($name, $value) {
		$this->_params[$name] = $value;
	}
	
	/*
	 * Hàm thêm các biến $_REQUEST vào biến {@link $_params}	 
	 */
	function addRequestParams() {
		$this->_params = array_merge($this->_params,$_REQUEST);
	}

	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm khởi tạo đối tượng
	 * 
	 * Hàm khởi các các biến, phân tích router và phân tích tên của controller và action
	 * 
	 * @param string, object đường dẫn hoặc đối tượng config
	 */
	public function __construct($config) {
		parent::__construct($config);
		$this->addRequestParams();
		$this->setRouter();
		$this->match();
		
		if($_SERVER['REQUEST_METHOD']=='POST') {
			$this->setIsPost(true);
		}
		
		$this->sid = session_id();
		$this->uAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->clientIp = $this->getClientIp();
		$this->deviceType = $this->getUserDevice();
		$this->checkIsMobile();		
	}
	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm phân tích URL hiện tại và match nó với router trong config
	 */
	public function match() {
		$url = $this->getPagePath();		
		$routers = $this->getRouter();
		foreach ($routers as $router) {						
			if (preg_match($router->pattern, $url)) {				
				$this->routerParse($url,$router);
				break;
			}
		}
	}
	
	/*
	 * Hàm phân tích router được match tại {@link match()}
	 * 
	 * Hàm phân tích url được match để ra controller và action cũng như các param kèm theo
	 * 
	 * @param string $url url hiện tại
	 * @param array $router router được match với url
	 */
	public function routerParse($url, $router) {
		//echo "<h1>MATCH OBJECT:</h1>";
		//var_dump($router);
		$controller = 'site';
		$action = 'index';
		preg_match_all('/<(.*?):(.*?)>/', $router->config, $patternParse, PREG_SET_ORDER);		
		preg_match_all($router->pattern, $url, $urlParse, PREG_SET_ORDER);		
		if(!empty($patternParse)) {
			$i=1;
			foreach($patternParse as $item) {
				switch ($item[1]) {
					case "controller":
						$controller = $urlParse[0][$i];						
						break;
					case "action":
						$action = $urlParse[0][$i];
						break;
					default:
						$this->addParams($item[1],$urlParse[0][$i]);
						break;					
				}
				$i++;
			}			
		}		
		$routerCall = str_replace('<controller>', $controller, $router->router);
		$routerCall = str_replace('<action>', $action, $routerCall);
		$arrayRouterCall = explode('/', $routerCall);
		if(!empty($arrayRouterCall[0])) {
			$controller = $arrayRouterCall[0];
		}
		if(!empty($arrayRouterCall[1])) {
			$action = $arrayRouterCall[1];
		}
		$this->setControllerName($controller);
		$this->setActionName($action);
	}
	
	/*
	 * Hàm lấy ra ip của client
	 * 
	 * @return string trả về ip hiện tại của client
	 */
	public function getClientIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	/*
	 * Hàm lấy ra tên của thiết bị
	 * 
	 * @return string trả về tên của thiết bị truy cập
	 */
	public function getUserDevice() {		
		$oses   = array(
			'Win311' => 'Win16',
			'Win95' => '(Windows 95)|(Win95)|(Windows_95)',
			'WinME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
			'Win98' => '(Windows 98)|(Win98)',
			'Win2000' => '(Windows NT 5.0)|(Windows 2000)',
			'WinXP' => '(Windows NT 5.1)|(Windows XP)',
			'WinServer2003' => '(Windows NT 5.2)',
			'WinVista' => '(Windows NT 6.0)',
			'Windows 7' => '(Windows NT 6.1)',
			'Windows 8' => '(Windows NT 6.2)',
			'WinNT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'OpenBSD' => 'OpenBSD',
			'SunOS' => 'SunOS',
			'Ubuntu' => 'Ubuntu',
			'Android' => 'Android',
			'Linux' => '(Linux)|(X11)',
			'iPhone' => 'iPhone',
			'iPad' => 'iPad',
			'MacOS' => '(Mac_PowerPC)|(Macintosh)',
			'QNX' => 'QNX',
			'BeOS' => 'BeOS',
			'OS2' => 'OS/2',
			'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
		);
		$uagent = $_SERVER['HTTP_USER_AGENT'];
		foreach ($oses as $os => $pattern)
			if (preg_match('/' . $pattern . '/i', $uagent))
				return $os;
		return 'Unknown';		
	}
	
	/*
	 * Hàm kiểm tra thiết bị của phải là mobile hoặc tablet không
	 * 
	 * @return boolean trả về true nếu thiết bị là mobile hoặc tablet
	 */
	public function checkIsMobile() {
		$mobile = array('Android','iPhone','iPad');
		if(in_array($this->deviceType, $mobile)) {
			$this->isMobile = true;
		} else {
			$this->isMobile = false;
		}
	}
	//--------------------------------------------------------------------------	
	/*
	 * Hàm lấy giá trị cho biến cookie
	 * 
	 * @param string $key tên của biên
	 * @param $default giá trị mặc định nếu biến không tồn tại
	 */
	public function getCookie($key,$default='') {
		if(isset($_COOKIE[$key])) {
			return $_COOKIE[$key];
		} else {
			return $default;
		}
	}
	
	/*
	 * Hàm xóa biến cookie
	 * 
	 * @param string $key tên của biên
	 */
	public function removeCookie($key) {
		if(isset($_COOKIE[$key])) {
			setcookie($key, '', time() - 3600);
			unset($_COOKIE[$key]);
		}
	}
	
	/*
	 * Hàm gán giá trị cho biến cookie
	 * 
	 * Hàm gán giá trị cho biến cookie, thời gian hết hạn được lấy từ config {@link appEvn.cookie_time_out}
	 * 
	 * @param string $key tên của biên
	 * @param $value giá trị của biến
	 * @param string $path đường dẫn thư mục web
	 */
	public function setCookie($key,$value,$path='') {
		if($path=='') {
			$path = $this->getPagePath();
		}
		$timeOut = 60*60*24*7;
		if(!empty($this->getConfig()->appEvn['cookie_time_out'])) {
			$timeOut = $this->getConfig()->appEvn['cookie_time_out'];
		}		
		setcookie($key, $value, time() + $timeOut);//, $path, $_SERVER['SERVER_NAME'], 1);
	}
	//--------------------------------------------------------------------------
	/*
	 * Hàm lấy giá trị cho biến session
	 * 
	 * @param string $key tên của biên
	 * @param $default giá trị mặc định nếu biến không tồn tại
	 */
	public function getSession($key,$default='') {
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return $default;
		}
	}
	
	/*
	 * Hàm xóa biến session
	 * 
	 * @param string $key tên của biên
	 */
	public function removeSession($key) {
		if(isset($_SESSION[$key])) {			
			unset($_SESSION[$key]);
		}
	}
	
	/*
	 * Hàm gán giá trị cho biến session
	 * 
	 * @param string $key tên của biên
	 * @param $value giá trị của biến
	 */
	public function setSession($key,$value) {
		$_SESSION[$key] = $value;
	}
	
}
