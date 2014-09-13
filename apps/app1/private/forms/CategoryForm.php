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
class CategoryForm extends BaseForm {
	//put your code here		
	public $title='';	
	
	public $rules=array(
		'title' => array(
			'handler'=>'validString',			
			'valids' => array(
				array('check'=>'required', 'valid'=>true,'message'=>'Cần nhập tiêu đề.', 'message_category'=>'category_form'),
				array('check'=>'max', 'valid'=>255,'message'=>'Tiêu đề không vượt quá {valid} ký tự.', 'message_category'=>'category_form'),
			),
		),		
	);		
}
