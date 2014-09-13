<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace TQAPP;

use TQFramework\TQBase as TQWebapp;
use TQCore\BaseController as BaseController;
use TQCore\TQException as TQExceptionHandle;

/**
 * Description of UserController
 *
 * @author tuannn6447
 */
class CategoryController extends BaseController {
	//put your code here	
	//public $layout = NULL;
	
	public function actionIndex() {
		$model = new Category();
		
		$list = $model->findAll();
		
		$this->render('index',array(
			'list' => $list
		));
	}
	
	public function actionView() {
		$model = new Category();
		$params = $this->request->getParams();
		
		$id = $params['id'];
		if(empty($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}
		
		if(!$model->findByPk($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}		
		
		$this->render('view',array(
			'model' => $model
		));
	}
	
	public function actionCreate() {
		$form = new CategoryForm('category');
		$model = new Category();
		
		if($this->request->getIsPost()===true) {
			$form->bind($_POST[$form->formId]);
			if($form->validate()) {
				$model->setAttributes($_POST[$form->formId]);
				$model->save();
				$this->setFlashMessage('success', 'Create success!');
				$this->redirect('category/index');
			} 	
		}
		
		$this->render('create',array(
			'form' => $form,
		));
	}
	
	public function actionUpdate() {
		$form = new CategoryForm('category');
		$model = new Category();
		$params = $this->request->getParams();
		
		$id = $params['id'];
		if(empty($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}
		
		if(!$model->findByPk($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}				
		
		if($this->request->getIsPost()===true) {
			$form->bind($_POST[$form->formId]);
			if($form->validate()) {
				$model->setAttributes($_POST[$form->formId]);
				$model->save();
				$this->setFlashMessage('success', 'Update success!');
				$this->redirect('category/update',array('id'=>$id));				
			} 	
		} else {
			$datas = array();
			$datas['title'] = $model->title;
			$form->bind($datas);
		}
		
		$this->render('update',array(
			'form' => $form,
		));
	}
	
	public function actionDelete() {
		$model = new Category();
		$params = $this->request->getParams();
		
		$id = $params['id'];
		if(empty($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}
		
		if(!$model->findByPk($id)) {
			throw new TQExceptionHandle("Record not found.", 404);
		}
		
		if($model->delete()) {
			$this->setFlashMessage('warning', 'Delete success!');
			$this->redirect('category/index');
		}
	}
	
}