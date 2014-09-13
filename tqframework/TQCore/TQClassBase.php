<?php
/**
 * Tập tin TQClassBase class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @since 1.0
 */

namespace TQCore;

class TQClassBase {
	
	/*
	 * Định nghĩa hàm __construct
	 */
	public function __construct() {}
	
	/*
	 * Định nghĩa hàm __destruct
	 */
	public function __destruct() {}
	
	/*
	 * Định nghĩa hàm __set
	 * 
	 * Tự động gán các thuộc tính private
	 * 
	 * @param string $name tên của thuộc tính
	 * @param $value giá trị của thuộc tính
	 * @throws TQExceptionHandle nếu mà không tìm thấy thuộc tính trong object
	 */
	public function __set($name, $value) {
		$method = "set".str_replace(" ","",ucwords(str_replace("_", " ", $name)));
		if(function_exists($this->$method)) {
			$this->$method($value);
		} else {			
			$mes = $this->t('tqbase','Method {method} no exist',array('method'=>$method));
			throw new TQExceptionHandle($mes, 500);
		}
	}
	
	/*
	 * Định nghĩa hàm __get
	 * 
	 * Tự động lấy giá trị các thuộc tính private
	 * 
	 * @param string $name tên của thuộc tính
	 * @throws TQExceptionHandle nếu mà không tìm thấy thuộc tính trong object
	 */
	public function __get($name) {
		$method = "get".str_replace(" ","",ucwords(str_replace("_", " ", $name)));
		if(function_exists($this->$method)) {
			return $this->$method();
		} else {			
			$mes = $this->t('tqbase','Method {method} no exist',array('method'=>$method));
			throw new TQExceptionHandle($mes, 500);
		}
	}		
	
}
