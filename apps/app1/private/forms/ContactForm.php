<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TQAPP;

use TQFramework\TQBase as TQWebapp;
use TQCore\BaseForm as BaseForm;

/**
 * Description of ContactForm
 *
 * @author tuannn6447
 */
class ContactForm extends BaseForm {
	//put your code here		
	public $delete_img='no';
	
	public $fullname='';
	public $sex;
	public $job;
	public $age=0;
	public $email='@gmail.com';
	public $url='http://';
	public $message='';
	public $category;
	
	public $rules=array(
		'fullname' => array(
			'handler'=>'validString',			
			'valids' => array(
				array('check'=>'required', 'valid'=>true,'message'=>'Cần nhập Họ và tên.', 'message_category'=>'contact_form'),
				array('check'=>'min', 'valid'=>2,'message'=>'Họ và tên không ít hơn {valid} ký tự.', 'message_category'=>'contact_form'),
				array('check'=>'max', 'valid'=>5,'message'=>'Họ và tên Không vượt quá {valid} ký tự.', 'message_category'=>'contact_form'),
			),
		),
		'age' => array(
			'handler'=>'validNumber',			
			'valids' => array(
				array('check'=>'type', 'message'=> 'Nhập không đúng định dạng số.', 'message_category'=>'contact_form'),
				array('check'=>'min', 'valid'=>18,'message'=>'Giá trị Tuổi không nhỏ hơn {valid}.', 'message_category'=>'contact_form'),
				array('check'=>'max', 'valid'=>100,'message'=>'Giá trị Tuổi không lớn hơn {valid}.', 'message_category'=>'contact_form'),
			),			
		),
		/*'email' => array(
			'handler'=>'validString',			
			'valids' => array(
				array('check'=>'required', 'valid'=>true,'message'=>'Cần nhập Email.', 'message_category'=>'contact_form'),				
				array('check'=>'email', 'message'=> 'Nhập không đúng định dạng Email.', 'message_category'=>'contact_form'),
			),			
		),*/
		'email' => array(
			'handler'=>'customValid',			
			'valids' => array(				
				array('check'=>'only', 'message'=> 'Email này đã tồn tại', 'message_category'=>'contact_form'),
			),			
		),
		'url' => array(
			'handler'=>'validString',			
			'valids' => array(				
				array('check'=>'url', 'message'=> 'Nhập không đúng định dạng Url.', 'message_category'=>'contact_form'),
			),			
		),		
		'message' => array(
			'handler'=>'validString',			
			'valids' => array(				
				array('check'=>'max', 'valid'=>2, 'message'=> 'Nội dung không vượt quá {valid} ký tự.', 'message_category'=>'contact_form'),
				//array('check'=>'pattern', 'valid'=>'/\d+/', 'message'=> 'Không đúng định dạng.', 'message_category'=>'contact_form'),
			),			
		),
		'sex' => array(
			'handler'=>'validRange',			
			'valids' => array(				
				array('check'=>'range', 'valid'=>array(0,1), 'message'=> 'Cần nhập giá trị ({valid}).', 'message_category'=>'contact_form'),				
			),			
		),
	);
	
	public function customValid($field, $datas) {
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
				$errors[] = TQWebapp::getApp()->t($messageCategory,$message);				
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
					case "only":
						if($value=='abc@gmail.com') {
							$errors[] = TQWebapp::getApp()->t($messageCategory,$message,array('valid'=> $valid));
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
}
