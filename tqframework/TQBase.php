<?php
/**
 * Tập tin TQBase class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @since 1.0
 */

namespace TQFramework;

use TQCore\TQException as TQExceptionHandle;

class TQBase {
	/*
	 * @var Lưu trữ đối tượng {@link TQApplication}
	 */		
	private static $_app;
	
	/*
	 * @return Trả về đối tượng TQApplication 
	 */
	public static function getApp() {
		return self::$_app;
	}
	
	/*
	 * @param TQApplication $app
	 */
	public static function setApp($app) {
		self::$_app = $app;
	}
	////////////////////////////////////////////////////////////////////////////
	/*
	 * @param array $config
	 * @return Trả về đối tượng TQApplication hiện tại đang được sử dụng
	 */
	public static function runApp($config) {			
		return self::createApp('TQCore\TQApplication',$config);
	}
	
	/*	 
	 * @param string $appName tên của Class TQApplication
	 * @param array $config
	 * @return Trả về đối tượng TQApplication hiện tại đang được sử dụng
	 */
	public static function createApp($appName,$config) {				
		return new $appName($config);
	}
	////////////////////////////////////////////////////////////////////////////
	/*	 
	 * Tự động load các thư viện core TQCore
	 * 
	 * @param string $className tên của Class cần load
	 * @throws TQExceptionHandle nếu mà không tìm thấy file
	 */
	public static function autoLoadCore($className) {
		if (!preg_match('/TQCore\\\\(.*)/', $className)) return false;
		$filename = dirname(__FILE__) . DS . str_replace('\\',DS,$className) .".php";		
		if (is_readable($filename)) {
			require $filename;
		} else {
			throw new TQExceptionHandle("Unable to load $filename", 500);				
		}
	}	
}

/*
 * Đăng ký ký hàm {@link TQBase::autoLoadCore} với hệ thống
 */
spl_autoload_register(array("TQFramework\TQBase", "autoLoadCore"));
