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
class LastestWidget extends BaseWidget {
	//put your code here		
	
	public function run($datas=array(), $showHtml=true) {
		parent::run();
		
		//Get list
		$list = $this->db
			->select('id, title')
			->from('tbl_example AS e')
			->where("is_public")
			->params(array(
				':is_public' => 1,
			))
			->order('id DESC')
			->limit(2)			
			->queryAll();
		
		return $this->render('lastest',array(
			'list' => $list
		),$showHtml);
	}
	
}
