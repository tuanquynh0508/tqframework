<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of foot
 *
 * @author tuannn6447
 */
class foot {
	//put your code here
	private $_name;
	function getName() {
		return $this->_name;
	}

	function setName($name) {
		$this->_name = $name;
	}

	public function __construct($name) {
		$this->setName($name);
	}
	
	public function __toString() {
		return $this->getName();
	}
	
}
