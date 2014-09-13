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
class SiteController extends BaseController {
	//put your code here		
	
	//public $layout = NULL;
	
	public function actionIndex() {		
		$this->render('index');
	}
	
	public function actionStyleDemo() {		
		$this->render('style_demo');
	}
	
	public function actionFullWidth() {		
		$this->render('full_width');
	}
	
	public function actionPortfolio() {		
		$this->render('portfolio');
	}
	
	public function actionGallery() {		
		$this->render('gallery');
	}
	
}
