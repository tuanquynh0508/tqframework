<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TQAPP;

use TQFramework\TQBase as TQWebapp;
use TQCore\BaseController as BaseController;

/**
 * Description of UserController
 *
 * @author tuannn6447
 */
class TestController extends BaseController {
	//put your code here	
	//public $layout = NULL;
	
	public function actionIndex() {
		$this->render('index');
	}
	
	public function actionContact() {
		$form = new ContactForm('contact');
		
		if($this->request->getIsPost()===true) {
			$form->bind($_POST[$form->formId]);
			if($form->validate()) {
				echo "Save data";
			} 	
		}
		
		//var_dump($form->error);
		
		$this->render('contact',array(
			'form' => $form,
		));
	}
	
}