<?php
/**
 * Tập tin TQException class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @see Exception
 * @since 1.0
 */

namespace TQCore;

class TQException extends \Exception {
	
	/*
	 * Hàm khởi tạo __construct
	 * 
	 * Đăng ký hàm xử lý lỗi {@link TQException::getStaticException} với hệ thống
	 * 
	 * @param string $message thông điệp
	 * @param number $code mã lỗi ví dụ như 404, 403, 500...
	 */
	public function __construct($message, $code=NULL)
    {
		set_exception_handler(array("TQCore\TQException", "getStaticException"));
        parent::__construct($message, $code);
    }
    
	/*
	 * Hàm chuyển đối đối tượng thành chuỗi __toString
	 * 
	 * Chuyển đổi đối tượng thành chuỗi {@link TQException}	 
	 */
    public function __toString()
    {
		http_response_code($this->getCode());		
		$message = "<h1>Error {$this->getCode()}</h1>";
		if(TQAPP_DEBUG===true) {
			$message .=  "<p>".htmlentities($this->getMessage())." in <strong>{$this->getFile()}</strong> on line <strong>{$this->getLine()}</strong></p>";
		}
		return $message;
    }
    
	/*
	 * Định nghĩa hàm getException
	 * 
	 * Trả về đối tượng {@link TQException}	
	 */
    public function getException()
    {
        print $this; // This will print the return from the above method __toString()
    }
    
	/*
	 * Hàm tĩnh getStaticException
	 * 
	 * Gọi đến hàm {@link getException()}
	 * 
	 * @param objac $exception đối tượng lỗi {@link Exception}
	 */
    public static function getStaticException($exception)
    {
        $exception->getException(); // $exception is an instance of this class
    }
}
