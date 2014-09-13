<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace TQAPP;

use TQFramework\TQBase as TQWebapp;
use TQCore\BaseWidget as BaseWidget;
/**
 * Description of UserController
 *
 * @author tuannn6447
 */
class TestWidget extends BaseWidget {
	//put your code here		
	
	public function run($datas=array(), $showHtml=true) {
		parent::run();
		
		return $this->render('lastest',array(
			'date' => 'Ngày '.date('Y-m-d H:i:s')
		),$showHtml);
	}
	
}
