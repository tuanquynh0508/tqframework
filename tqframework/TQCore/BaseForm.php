<?php
/**
 * Tập tin BaseForm class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package base
 * @see TQClassBase
 * @since 1.0
 */

namespace TQCore;

use TQFramework\TQBase as TQBase;
use TQCore\TQException as TQExceptionHandle;

class BaseForm extends TQClassBase {
	/*
	 * @var string ID của form
	 */
	public $formId='TQForm';
	
	/*
	 * @var array mảng lỗi
	 */
	public $error=array();
	
	/*
	 * @var array mảng validate, xem ví dụ ở demo
	 */
	public $rules=array();	
	
	/*
	 * Hàm khởi tạo
	 * 
	 * @param string $id tên mã của Form
	 */
	public function __construct($id) {
		parent::__construct();
		$this->formId = $id;
	}	
	//--------------------------------------------------------------------------
	/*
	 * Hàm Validate giữ liệu
	 * 
	 * @return boolean trả về true nếu giữ liệu được validte, và false nếu giữ liệu sai
	 */
	public function validate() {
		if(!empty($this->rules)) {
			foreach ($this->rules as $key => $value) {
				$handler = $value['handler'];
				$valids = $value['valids'];
				if(method_exists($this, $handler)) {
					$this->$handler($key,$valids);
				}
			}
		}
		
		if(!empty($this->error)) {
			return false;
		} else {
			return true;
		}
	}
	
	/*
	 * Hàm gán giá trị cho các thuộc tính
	 * 
	 * @param array $datas mảng giá trị truyền vào, mảng này thường có key là các thuộc tính của form. Ví dụ array('fullname'=>'Nguyễn Như Tuấn', 'age'=>30)
	 */
	public function bind($datas) {
		//var_dump($datas);
		if(!empty($datas)) {
			foreach ($datas as $key => $value) {
				if(property_exists($this, $key)) {
					$this->$key = $value;
				}
			}
		}
	}
	//--------------------------------------------------------------------------
	/*
	 * Hàm thực hiện validate cho giữ liệu String
	 * 
	 * Thực hiện validate giữ liệu cho kiểu string, nếu có lỗi thì thêm vào mảng error
	 * 
	 * @param string $field tên của trường
	 * @param array $datas mảng valids của trường được định nghĩa trong rules
	 */
	public function validString($field, $datas) {
		if(!property_exists($this, $field)) return false;
		//Get value
		$value = trim($this->$field);
				
		$errors = array();
		
		if(!empty($datas)) {
			//Check Required
			$required = false;
			$messageCategory='';
			$message='';
			
			foreach ($datas as $r) {
				$check = $r['check'];
				if($r['check']=='required') {
					$messageCategory=$r['message_category'];
					$message=$r['message'];
					$required=true;
					break;
				}
			}
			
			//Check empty value
			if($required===true && $value=='') {
				$errors[] = TQBase::getApp()->t($messageCategory,$message);				
			}
			
			//Check other valid
			foreach ($datas as $r) {
				if(!empty($errors)) break;
				$check = $r['check'];
				$valid = '';
				if(!empty($r['valid'])) $valid=$r['valid'];
				$message = $r['message'];
				$messageCategory = $r['message_category'];
				switch ($check) {					
					case "min":
						if($value!='' && strlen($value)<$valid) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}
						break;
					case "max":
						if($value!='' && strlen($value)>$valid) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}
						break;
					case "email":
						if ($value!='' && !preg_match("/^\w(\.?[\w-])*@\w(\.?[-\w])*\.[a-z]{2,4}$/i", $value)) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}						
						break;
					case "url":
						if ($value!='' && !preg_match("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $value)) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}						
						break;
					case "pattern":
						if ($value!='' && !preg_match($valid, $value)) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message);
						}						
						break;
					default :
						break;
				}
			}
		}
		
		if(!empty($errors)) {
			$this->error[$field] = $errors;
		}
	}
	
	/*
	 * Hàm thực hiện validate cho giữ liệu Number
	 * 
	 * Thực hiện validate giữ liệu cho kiểu Number, nếu có lỗi thì thêm vào mảng error
	 * 
	 * @param string $field tên của trường
	 * @param array $datas mảng valids của trường được định nghĩa trong rules
	 */
	public function validNumber($field, $datas) {
		if(!property_exists($this, $field)) return false;
		//Get value
		$value = trim($this->$field);
				
		$errors = array();
		
		if(!empty($datas)) {
			//Check Required
			$required = false;
			$messageCategory='';
			$message='';
			
			foreach ($datas as $r) {
				$check = $r['check'];
				if($r['check']=='required') {
					$messageCategory=$r['message_category'];
					$message=$r['message'];
					$required=true;
					break;
				}
			}
			
			//Check empty value
			if($required===true && $value=='') {
				$errors[] = TQBase::getApp()->t($messageCategory,$message);				
			}
			
			//Check other valid
			foreach ($datas as $r) {
				if(!empty($errors)) break;
				$check = $r['check'];
				$valid = '';
				if(!empty($r['valid'])) $valid=$r['valid'];
				$message = $r['message'];
				$messageCategory = $r['message_category'];
				switch ($check) {
					case "type":
						if($value!='' && !is_numeric($value)) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}
						break;
					case "min":
						if($value!='' && $value<$valid) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}
						break;
					case "max":
						if($value!='' && $value>$valid) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=>$valid));
						}
						break;					
					default :
						break;
				}
			}
		}
		
		if(!empty($errors)) {
			$this->error[$field] = $errors;
		}
	}
	
	/*
	 * Hàm thực hiện validate cho giữ liệu Range
	 * 
	 * Thực hiện validate giữ liệu cho kiểu Range, nếu có lỗi thì thêm vào mảng error
	 * 
	 * @param string $field tên của trường
	 * @param array $datas mảng valids của trường được định nghĩa trong rules
	 */
	public function validRange($field, $datas) {
		if(!property_exists($this, $field)) return false;
		//Get value
		$value = trim($this->$field);
				
		$errors = array();
		
		if(!empty($datas)) {
			//Check Required
			$required = false;
			$messageCategory='';
			$message='';
			
			foreach ($datas as $r) {
				$check = $r['check'];
				if($r['check']=='required') {
					$messageCategory=$r['message_category'];
					$message=$r['message'];
					$required=true;
					break;
				}
			}
			
			//Check empty value
			if($required===true && $value=='') {
				$errors[] = TQBase::getApp()->t($messageCategory,$message);				
			}
			
			//Check other valid
			foreach ($datas as $r) {
				if(!empty($errors)) break;
				$check = $r['check'];
				$valid = '';
				if(!empty($r['valid'])) $valid=$r['valid'];
				$message = $r['message'];
				$messageCategory = $r['message_category'];
				switch ($check) {
					case "range":
						if($value!='' && !in_array($value,$valid)) {
							$errors[] = TQBase::getApp()->t($messageCategory,$message,array('valid'=> implode(',', $valid)));
						}
						break;										
					default :
						break;
				}
			}
		}
		
		if(!empty($errors)) {
			$this->error[$field] = $errors;
		}
	}
	//--------------------------------------------------------------------------
	/*
	 * Hàm tạo hidden element
	 * 
	 * @param string $name tên của trường
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function hiddenField($name, $option=array()) {
		$lOption = array();				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		$html = '<input type="hidden" name="'.$this->formId.'['.$name.']" value="'.$value.'" '.implode(' ', $lOption).'/>';
		return $html;
	}
	
	/*
	 * Hàm tạo textbox element
	 * 
	 * @param string $name tên của trường
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;','classError'=>'error')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function textField($name, $option=array()) {
		$lOption = array();
		
		$classError = 'tqapp-field-error';
		$classStyle = '';
		if(array_key_exists('classError', $option)) {
			$classError = $option['classError'];
			unset($option['classError']);
		}		
		if(array_key_exists('class', $option)) {
			$classStyle = $option['class'];
		}
		if(array_key_exists($name, $this->error)) {
			$classStyle .=' '.$classError;
		}
		$option['class'] = $classStyle;
				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		$html = '<input type="text" name="'.$this->formId.'['.$name.']" value="'.$value.'" '.implode(' ', $lOption).'/>';
		return $html;
	}
	
	/*
	 * Hàm tạo textarea element
	 * 
	 * @param string $name tên của trường
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;','classError'=>'error')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function textareaField($name, $option=array()) {
		$lOption = array();
		
		$classError = 'tqapp-field-error';
		$classStyle = '';
		if(array_key_exists('classError', $option)) {
			$classError = $option['classError'];
			unset($option['classError']);
		}		
		if(array_key_exists('class', $option)) {
			$classStyle = $option['class'];
		}
		if(array_key_exists($name, $this->error)) {
			$classStyle .=' '.$classError;
		}
		$option['class'] = $classStyle;
				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		$html = '<textarea name="'.$this->formId.'['.$name.']" '.implode(' ', $lOption).'/>'.$value.'</textarea>';
		return $html;
	}
	
	/*
	 * Hàm tạo select element
	 * 
	 * Sử dụng option 'emptyValue' => 'Lựa chọn danh sách'
	 * 'multiple' => 'multiple' nếu là lựa chọn nhiều
	 * 
	 * @param string $name tên của trường
	 * @param array $list mảng giữ liệu, ví dụ array('0'=>'Nữ','1'=>'Nam')
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;','classError'=>'error')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function selectField($name, $list, $option=array()) {
		$lOption = array();
		
		$classError = 'tqapp-field-error';
		$classStyle = '';
		$emptyValue = '';
		$multi = false;
		if(array_key_exists('classError', $option)) {
			$classError = $option['classError'];
			unset($option['classError']);
		}
		if(array_key_exists('emptyValue', $option)) {
			$emptyValue = $option['emptyValue'];
			unset($option['emptyValue']);
		}
		if(array_key_exists('multiple', $option)) {
			$multi = true;			
		}
		if(array_key_exists('class', $option)) {
			$classStyle = $option['class'];
		}
		if(array_key_exists($name, $this->error)) {
			$classStyle .=' '.$classError;
		}
		$option['class'] = $classStyle;
				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		
		$html = '<select name="'.$this->formId.'['.$name.']'.(($multi===true)?'[]':'').'" '.implode(' ', $lOption).'>'."\n";
		if($emptyValue!='') {
			$selected = '';
			if($value=='') $selected='selected="selected"';
			$html .= '<option value="" '.$selected.'>'.$emptyValue.'</option>'."\n";
		}
		if(!empty($list)) {
			foreach ($list as $opValue => $opName) {
				$selected = '';
				if($multi===true) {
					if(!empty($value) && in_array($opValue, $value)) $selected='selected="selected"';
				} else {
					if($opValue == $value) $selected='selected="selected"';
				}
				$html .= '<option value="'.$opValue.'" '.$selected.'>'.$opName.'</option>'."\n";
			}
		}
		$html .= '</select>'."\n";
		return $html;
	}
	
	/*
	 * Hàm tạo radio element
	 * 
	 * Sử dụng option 'itemTemplate' => '<label>{input}{label}</label>' nếu muốn thay đổi template của một phần tử
	 * 
	 * @param string $name tên của trường
	 * @param array $list mảng giữ liệu, ví dụ array('0'=>'Nữ','1'=>'Nam')
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;','classError'=>'error')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function radioField($name, $list, $option=array()) {
		$lOption = array();
		
		$classError = 'tqapp-field-error';
		$classStyle = '';
		$itemTemplate = '<label>{input}{label}</label>';
		if(array_key_exists('classError', $option)) {
			$classError = $option['classError'];
			unset($option['classError']);
		}
		if(array_key_exists('itemTemplate', $option)) {
			$itemTemplate = $option['itemTemplate'];
			unset($option['itemTemplate']);
		}
		if(array_key_exists('class', $option)) {
			$classStyle = $option['class'];
		}
		if(array_key_exists($name, $this->error)) {
			$classStyle .=' '.$classError;
		}
		$option['class'] = $classStyle;
				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		
		$html = '';		
		if(!empty($list)) {
			foreach ($list as $opValue => $opName) {
				$selected = '';				
				if($opValue == $value) $selected='checked="checked"';
				$inputField = '<input type="radio" name="'.$this->formId.'['.$name.']" '.implode(' ', $lOption).' value="'.$opValue.'" '.$selected.'/>';				
				$input = str_replace('{input}', $inputField, $itemTemplate);
				$input = str_replace('{label}', $opName, $input);				
				$html .= $input."\n";
			}
		}		
		return $html;
	}
	
	/*
	 * Hàm tạo checkbox element
	 * 
	 * Sử dụng option 'itemTemplate' => '<label>{input}{label}</label>' nếu muốn thay đổi template của một phần tử
	 * 
	 * @param string $name tên của trường
	 * @param array $list mảng giữ liệu, ví dụ array('0'=>'Nữ','1'=>'Nam')
	 * @param array $option mảng thuộc tính, ví dụ array('class'=>'vidu1','style'=>'width:300px;','classError'=>'error')
	 * @return string trả về chuỗi HTMl của element
	 */
	public function checkboxField($name, $list, $option=array()) {
		$lOption = array();
		
		$classError = 'tqapp-field-error';
		$classStyle = '';
		$itemTemplate = '<label>{input}{label}</label>';
		if(array_key_exists('classError', $option)) {
			$classError = $option['classError'];
			unset($option['classError']);
		}
		if(array_key_exists('itemTemplate', $option)) {
			$itemTemplate = $option['itemTemplate'];
			unset($option['itemTemplate']);
		}
		if(array_key_exists('class', $option)) {
			$classStyle = $option['class'];
		}
		if(array_key_exists($name, $this->error)) {
			$classStyle .=' '.$classError;
		}
		$option['class'] = $classStyle;
				
		foreach ($option as $key => $value) {
			$lOption[] = $key.'="'.$value.'"';
		}
		
		$value = '';
		if(property_exists($this, $name)) {
			$value = $this->$name;
		}
		
		$html = '';		
		if(!empty($list)) {
			foreach ($list as $opValue => $opName) {
				$selected = '';				
				if(!empty($value) && in_array($opValue, $value)) $selected='checked="checked"';
				$inputField = '<input type="checkbox" name="'.$this->formId.'['.$name.'][]" '.implode(' ', $lOption).' value="'.$opValue.'" '.$selected.'>';
				$input = str_replace('{input}', $inputField, $itemTemplate);
				$input = str_replace('{label}', $opName, $input);				
				$html .= $input."\n";
				
			}
		}		
		return $html;
	}
	
}
