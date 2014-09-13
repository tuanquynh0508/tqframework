<?php
/**
 * Tập tin BaseController class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package base
 * @see TQApplication
 * @since 1.0
 */

namespace TQCore;

use TQFramework\TQBase as TQWebapp;
use TQCore\TQApplication as TQApplication;
use TQCore\TQException as TQExceptionHandle;

class BaseController extends TQApplication {
	/*
	 * @var string layout hiện tại
	 */
	public $layout = 'main';
	
	/*
	 * Hàm init
	 */
	public function init() {}
	
	/*
	 * Hàm beforeRequest
	 * 
	 * Thực hiện trước khi reuquest
	 */
	public function beforeRequest() {}
	
	/*
	 * Hàm beforeRequest
	 * 
	 * Thực hiện sau khi reuquest
	 */
	public function afterRequest() {}
		
	/*
	 * Hàm run
	 * 
	 * Thực hiện khi chạy Controller, gọi action tương ứng với đường link
	 * @throws TQExceptionHandle nếu mà không tìm thấy action
	 */
	public function run() {
		parent::run();		
		$aName = 'action'.ucwords($this->request->getActionName());
		$this->init();
		if(method_exists($this,$aName)) {
			$this->beforeRequest();
			$this->$aName();
			$this->afterRequest();
		} else {
			$mes = $this->t('tqbase','Action {action} not exist',array('action'=>$this->request->getActionName()));
			throw new TQExceptionHandle($mes, 404);
		}
	}
	
	/*
	 * Hàm thực hiện render ra dạng Json File
	 * 
	 * @param array $datas mảng Json
	 * @param string $allowAccess cho phép cross domain nếu có giá trị là *
	 * @param string $allowMethod cho phép phương thức truy vấn đến là GET hoặc POST hoặc cả hai
	 * @return string chuỗi JSON
	 */
	public function renderJson($datas=array(),$allowAccess=NULL, $allowMethod='GET, POST') {
		if(!empty($allowAccess)) {
			header('Access-Control-Allow-Origin: *');
		}
		header('Access-Control-Allow-Methods: '.$allowMethod);
		header('Content-Type: application/json');
		echo json_encode($datas);
	}
	
	/*
	 * Hàm thực hiện render HTML
	 * 
	 * Thực hiện render HTML layout và view cho controller
	 * 
	 * @param string $vFile tên của view, viết dưới dạng path alias, ví dụ index
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 */
	public function render($vFile, $datas=array()) {		
		$cview = $this->getCid().'.'.$vFile;
		$view = $this->fetchView($cview, $datas, false);
		if(empty($this->layout)) {
			echo $view;
		} else {
			$this->fetchView('layout.'.$this->layout, array(
				'content' => $view,
				'TQApp' => TQWebapp::getApp()
			));
		}
	}
	
	/*
	 * Hàm thực hiện fetch HTML của file view
	 * 
	 * Thực hiện render HTML view cho controller
	 * 
	 * @param string $vFile tên của view, viết dưới dạng path alias, ví dụ index
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @param boolean $showHtml là true nếu muốn echo luôn và false nếu muốn return về
	 * @return string chuỗi HTML của file view nếu $showHtml là false
	 */
	public function fetchView($vFile, $datas=array(), $showHtml=true) {		
		$viewFile = $this->parseAliasPath('tqapp.views.'.$vFile);		
		if(!file_exists($viewFile))	{			
			$mes = $this->t('tqbase','Unable to load {file}',array('file'=>$vFile));
			throw new TQExceptionHandle($mes, 500);
		}
		
		//Include TQApp
		$datas['TQApp'] = TQWebapp::getApp();
		
		// Extract the variables to be used by the view
		if(!empty($datas)) {
			extract($datas);
		}				
		
		//Get View file
		ob_start();		
		include $viewFile;
		$contents = ob_get_contents();
		ob_end_clean();
		
		if($showHtml==false) {
			return $contents;
		} else {
			echo $contents;
		}
	}
	
	/*
	 * Hàm thực hiện fetch HTML của file partial
	 * 
	 * @param string $pFile tên của partial, viết dưới dạng path alias, ví dụ category._form
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @return string chuỗi HTML của partial
	 */
	public function partial($pFile, $datas=array()) {				
		return $this->fetchView($pFile, $datas, false);		
	}
	
	/*
	 * Hàm thực hiện fetch HTML của widget
	 * 
	 * @param string $wName tên của widget
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @param boolean $showHtml là true nếu muốn echo luôn và false nếu muốn return về
	 * @return string chuỗi HTML của partial
	 */
	public function widget($wName, $datas=array(), $showHtml=true) {
		$wName = ucwords($wName).'Widget';
		$this->_tqRequire('tqapp.widgets.'.$wName);
		return TQWebapp::createApp("TQAPP\\$wName",$this->getConfig())->run($datas, $showHtml);
	}
	
	/*
	 * Hàm thực hiện redirect
	 * 
	 * @param string $alias chuỗi router gồm comtroller/action
	 * @param array $params mảng tham số kèm theo, ví dụ array('model'=>$model)
	 */
	public function redirect($alias,$params=array()) {
		$url = $this->createUrl($alias, $params);
		header('Location: '.$url);
		exit;
	}
	
	/*
	 * Hàm thực hiện fetch HTML của widget
	 * 
	 * @param string $alias chuỗi router gồm comtroller/action
	 * @param array $params mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @return string chuỗi url đầy đủ
	 */
	public function createUrl($alias,$params=array()) {
		return $this->parseAliasRouter($alias, $params);
	}
}
