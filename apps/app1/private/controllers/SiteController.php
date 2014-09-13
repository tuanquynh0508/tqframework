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
		/*$params = $this->request->getParams();
		echo $params['a']."<br/>";
		echo $params['b']."<br/>";
		
		echo TQWebapp::getApp()->getWebroot();
		
		$f = new \foot('Hoang');
		echo $f;*/
		
		/*
		//Example queryAll
		$list = $this->db
			->select('id, title')
			->from('tbl_example AS e')
			->where("id>:id AND title LIKE :title")
			->params(array(
				':id' => 1,
				':title' => "'%P%'",
			))
			->order('id DESC')
			->limit(2)			
			->queryAll();
		if(!empty($list)) {
			var_dump($list);
		}
		//Example queryRow
		$item = $this->db
			->select('id, title')
			->from('tbl_example AS e')
			->where("id=:id AND title LIKE :title")
			->params(array(
				':id' => 1,
				':title' => "'%P%'",
			))
			->order('id DESC')
			->queryRow();
		if(!empty($item)) {
			var_dump($item);
		}
		//Example queryScalar
		$result = $this->db
			->select('MAX(id)')
			->from('tbl_example AS e')
			->where("is_public=:is_public")
			->params(array(
				':is_public' => 1,
			))
			->queryScalar();
		if(!empty($result)) {
			echo $result."<br/>";
		}
		//Example queryCount
		$result = $this->db			
			->from('tbl_example AS e')
			->where("is_public=:is_public")
			->params(array(
				':is_public' => 0,
			))
			->queryCount();		
		echo $result."<br/>";
		//Example querySQL
		$sql = 'SHOW COLUMNS FROM tbl_example';
		$result = $this->db->querySQL($sql);
		if(!empty($result)) {
			var_dump($result);
		}
		*/
		
		//Example queryAll with JOIN
		$list = $this->db
			->select('e.id, e.title, c.title AS category')
			->from('tbl_example AS e')
			->where("e.id > :id")			
			->params(array(
				':id' => 10,
			))
			->join(array(
				array(
					'join' => 'LEFT JOIN',
					'table' => 'tbl_category c',
					'on' => 'e.category_id = c.id'
				),
			))
			->addWhere('e.title LIKE :title',array(':title' => "'%P%'"))
			//->addWhereIn('e.id',array(1,2,3,4),'OR')
			->order('e.id DESC')
			->limit(2)			
			->queryAll();
		if(!empty($list)) {
			var_dump($list);
		}
		
		$this->render('index',array(
			'name' => 'Nguyen Nhu Tuan',
			'age' => 30,			
		));
	}
	
	public function actionTest() {		
		$this->render('test');
	}
	
	public function actionTest2() {
		
		try {
			$this->db->beginTransaction();
			$list = $this->db
				->select('id, title')
				->from('tbl_example AS e')
				->where("title=:title")
				->params(array(
					//':is_public' => 1,
					':title' => "\"Bài viết mới 17's A\"",
				))
				->order('id DESC')
				->limit(10)			
				->queryAll();
			var_dump($list);
			$this->db->commit();
		} catch (Exception $e) {			
			throw new TQExceptionHandle("Transaction failed: {$e->getMessage()}", 500);
			$this->db->rollback();
		}
		$this->db->endTransaction();
		
		$this->render('test2');
	}
	
	public function actionTest3() {
		$insertId = 0;		
		try {
			$this->db->beginTransaction();
			$sql = "INSERT INTO tbl_category (title,created,modified) VALUES ('xxxx', NOW(), NOW())";
			$result = $this->db->executeSQL($sql);			
			if($result>0) {
				$insertId = $this->db->getCon()->insert_id;
			}
		} catch (Exception $e) {			
			throw new TQExceptionHandle("Transaction failed: {$e->getMessage()}", 500);
			$this->db->rollback();
		}
		$this->db->endTransaction();
		echo 'Last ID: '.$insertId;
		$this->render('test3');
	}
	
	public function actionTest4() {
		
		$example = new Example();
		/*
		//Example findAll
		$datas = $example->findAll(array(
			'select' => '*',
			'limit' => 2
		));
		var_dump($datas);
		
		//Example find
		$data = $example->find(array(
			'where' => 'id=:id',
			'params' => array(
				':id' => 11
			),
		));
		var_dump($data);
		
		//Example find
		$datas = $example->findByAttributes(
			'id=:id AND title=:title',
			array(
				':id' => 2,
				':title' => "'Post 2'"
			)
		);
		var_dump($datas);
		*/
		//Example findByPk
		/*$example->findByPk(10);
		echo $example->id."<br/>";
		echo $example->title."<br/>";		
		//var_dump($example);
		$example->title = "Tieu de moi 10";
		if($example->save()) {
			echo 'Save successful';
			//var_dump($example);
		} else {
			echo 'Save fail';
		}*/
		
		$example2 = new Example();				
		$example2->category_id = 1;
		$example2->title = "Bài viết mới 18's";
		$example2->is_public = 0;		
		if($example2->save()) {
			echo 'Add successful';
			//var_dump($example);
		} else {
			echo 'Add fail';
		}
		
		//var_dump($example2);
		$example2->title = "Bài viết mới 17's A";
		if($example2->save()) {
			echo 'Save successful';
			//var_dump($example);
		} else {
			echo 'Save fail';
		}
		
		/*
		 $example3 = new Example();
		$example3->findByPk(18);				
		if($example3->delete()) {
			echo 'Delete successful';
			//var_dump($example);
		} else {
			echo 'Delete fail';
		}
		 */
		
		$this->render('test4');
	}
	
	public function actionTest5() {		
		//$this->redirect('chat/longpoll',array('lastid'=>1, 'timestamp'=>10));
		//$this->redirect('site/test',array('lastid'=>1, 'timestamp'=>10));
		$this->render('test5');
	}
	
	public function actionTest6() {		
		$this->render('test6');
	}
	
	public function actionTest7() {
		//$this->request->setCookie('vidu1','value 1');
		//$this->request->removeCookie('vidu1');
		//echo $this->request->getCookie('vidu1');
		//var_dump($_COOKIE);
		
		//$this->request->setSession('vidu1', 'value 1');
		//echo $this->request->getSession('vidu1');
		//$this->request->removeSession('vidu1');
		//var_dump($_SESSION);
		
		//var_dump($this->request);
		
		$this->render('test7');
	}
	
	public function actionJson() {
		$list = $this->db
			->select('id, title')
			->from('tbl_example AS e')
			->where("is_public")
			->params(array(
				':is_public' => 1,
			))
			->order('id DESC')
			->limit(10)			
			->queryAll();
		$this->renderJson($list);
	}
	
}
