<?php
/**
 * Tập tin BaseWidget class.
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

class BaseWidget extends TQApplication {	
	
	/*
	 * Hàm render ra HTML của widget
	 * 
	 * @param string $vFile tên của view, viết dưới dạng path alias, ví dụ index
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @param boolean $showHtml là true nếu muốn echo luôn và false nếu muốn return về
	 * @return string chuỗi HTML của file view nếu $showHtml là false
	 */
	public function render($vFile, $datas=array(), $showHtml=true) {
		return $this->fetchView($vFile, $datas, $showHtml);		
	}
	
	/*
	 * Hàm thực hiện fetch HTML của file view của widget
	 * 
	 * Thực hiện render HTML view cho widget
	 * 
	 * @param string $vFile tên của view, viết dưới dạng path alias, ví dụ index
	 * @param array $datas mảng tham số kèm theo, ví dụ array('model'=>$model)
	 * @param boolean $showHtml là true nếu muốn echo luôn và false nếu muốn return về
	 * @return string chuỗi HTML của file view nếu $showHtml là false
	 */
	public function fetchView($vFile, $datas=array(), $showHtml=true) {		
		$viewFile = $this->parseAliasPath('tqapp.widgets.views.'.$vFile);
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
	
}
