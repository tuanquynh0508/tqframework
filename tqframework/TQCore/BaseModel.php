<?php
/**
 * Tập tin BaseModel class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package base
 * @see TQClassBase
 * @since 1.0
 */

namespace TQCore;

use TQFramework\TQBase as TQBase;
use TQCore\TQDatabase as TQDatabase;
use TQCore\TQException as TQExceptionHandle;

class BaseModel extends TQClassBase {
	/*
	 * @var string tên của bảng
	 */
	public $tableName='';
	
	/*
	 * @var string alias của bảng
	 */
	public $tableAlias='t';
	
	/*
	 * @var object {@link Mysqli}
	 */
	protected $_con=NULL;
	
	/*
	 * @var boolean là true nếu là thêm mới và false nếu là cập nhật
	 */
	protected $_is_new=true;
	
	/*
	 * @var array mảng khóa
	 */
	protected $_pk=array();
	
	/*
	 * @var array mảng khóa tăng dần auto increment
	 */
	protected $_pkInc=array();
	
	/*
	 * @var array mảng thuộc tính
	 */
	protected $_attributes=array();
	
	/*
	 * @var array kiểu của thuộc tính
	 */
	protected $_attrType=array();
	
	/*
	 * Hàm khởi tạo
	 * 
	 * Thực hiện {@link init()}
	 * Khởi tạo đối tượng kết nối database {@link $_con}
	 */
	public function __construct() {
		parent::__construct();
		$this->setCon(TQBase::getApp()->db);
		$this->init();
	}		
	
	/*
	 * Hàm init
	 * 
	 * Thực hiện phân tích bảng, lấy ra các thuộc tính của bảng và các field
	 */
	public function init() {
		$con = $this->getCon();		
		$this->_attrType = $con->querySQL('SHOW COLUMNS FROM '.$this->getTableName());
		if(!empty($this->_attrType)) {
			foreach ($this->_attrType as $item) {
				$this->_attributes[$item->Field] = '';
				if($item->Key=='PRI') {
					$this->addPk($item->Field);
					if($item->Extra=='auto_increment') {
						$this->_pkInc[] = $item->Field;
					}
				}
			}
		}
	}
		
	//--------------------------------------------------------------------------
	/*
	 * Hàm afterSave
	 * 
	 * Thực hiện sau khi save
	 */
	public function afterSave() {}
	
	/*
	 * Hàm beforeSave
	 * 
	 * Thực hiện trước khi save
	 */
	public function beforeSave() {}
	
	/*
	 * Thực hiện Save
	 * 
	 * @return boolean trả về true nếu save thành công là false nếu không thực hiện được
	 * @throws TQExceptionHandle nếu mà không thực hiện được
	 */
	public function save() {
		$con = $this->getCon();
		$this->beforeSave();
		if(array_key_exists('modified', $this->_attributes)) {
			$this->_attributes['modified'] = date('Y-m-d H:i:s');
		}
			
		if($this->_is_new===true) {
			//If INSERT
			$fields = array();
			$values = array();
			if(array_key_exists('created', $this->_attributes)) {
				$this->_attributes['created'] = date('Y-m-d H:i:s');
			}			
			foreach ($this->_attributes as $key => $value) {
				if(in_array($key, $this->_pkInc)) continue;
				$fields[] = $key;
				$values[] = $con->getCon()->real_escape_string($value);
			}
			$sql = "INSERT INTO ".$this->getTableName()."(".implode(",", $fields).") VALUES ('".implode("','", $values)."')";
		} else {
			//If UPDATE
			$pks = $this->getPk();
			$sql = "UPDATE ".$this->getTableName()." SET ";
			$i=0;
			$fields = array();
			foreach ($this->_attributes as $key => $value) {
				if(in_array($key, $this->_pkInc)) continue;
				$fields[] = $key."=".((!empty($value))?"'".$con->getCon()->real_escape_string($value)."'":'NULL');				
			}
			$sql .= implode(', ', $fields);
			//Add Where
			$arrayPk = array();
			foreach($pks as $pk) {
				$arrayPk[] = "{$pk}='".$this->_attributes[$pk]."'";
			}
			$sql .= "WHERE ".  implode(' AND ', $arrayPk);
		}		
		
		$checkProcess = false;
		try {
			$con->beginTransaction();			
			$result = $con->executeSQL($sql);			
			if($result>0) {
				if($this->_is_new===true && count($this->_pkInc)>0) {
					$this->_attributes[$this->_pkInc[0]] = $con->getCon()->insert_id;
				}
				$this->setIsNew(false);
				$checkProcess = true;
				$this->afterSave();
			}			
		} catch (Exception $e) {
			$mes = TQBase::getApp()->t('tqbase','Transaction failed: {message}',array('message'=>$e->getMessage()));
			throw new TQExceptionHandle($mes, 500);
			$con->rollback();			
		}
		$con->endTransaction();		
		return $checkProcess;
	}
	
	/*
	 * Hàm afterDelete
	 * 
	 * Thực hiện sau khi delete
	 */
	public function afterDelete() {}
	
	/*
	 * Hàm beforeDelete
	 * 
	 * Thực hiện trước khi delete
	 */
	public function beforeDelete() {}
	
	/*
	 * Thực hiện Delete
	 * 
	 * @return boolean trả về true nếu delete thành công là false nếu không thực hiện được
	 * @throws TQExceptionHandle nếu mà không thực hiện được
	 */
	public function delete() {
		$con = $this->getCon();
		$this->beforeDelete();
		//Build SQL
		//Add Where
		$pks = $this->getPk();
		$sql = "DELETE FROM ".$this->getTableName()." ";
		$arrayPk = array();
		foreach($pks as $pk) {
			$arrayPk[] = "{$pk}='".$this->_attributes[$pk]."'";
		}
		$sql .= "WHERE ".  implode(' AND ', $arrayPk);
		
		$checkProcess = false;		
		try {
			$con->beginTransaction();			
			$result = $con->executeSQL($sql);			
			if($result>0) {				
				$checkProcess = true;
				$this->afterDelete();
			}			
		} catch (Exception $e) {			
			$mes = TQBase::getApp()->t('tqbase','Transaction failed: {message}',array('message'=>$e->getMessage()));
			throw new TQExceptionHandle($mes, 500);
			$con->rollback();			
		}
		$con->endTransaction();		
		return $checkProcess;
	}
	//--------------------------------------------------------------------------
	/*
	 * Hàm thực hiện tìm kiếm database
	 * 
	 * @param array $option mảng option, ví dụ array('select'=>'*','limit'=>5)
	 * @return array object trả về một mảng các đối tượng record
	 */
	public function findAll($option=array()) {
		$con = $this->getCon();
		//Load query option
		if(!empty($option)) {
			foreach ($option as $method => $value) {
				if(method_exists($con, $method)) {
					$con->$method($value);
				}
			}
		}
		$con->from($this->getTableName().' '.$this->getTableAlias());
		
		return $con->queryAll();
	}
	
	/*
	 * Hàm thực hiện tìm kiếm database
	 * 
	 * @param array $option mảng option, ví dụ array('select'=>'*','limit'=>5)
	 * @return object trả về đối tượng record
	 */
	public function find($option=array()) {
		$con = $this->getCon();
		//Load query option
		if(!empty($option)) {
			foreach ($option as $method => $value) {
				if(method_exists($con, $method)) {
					$con->$method($value);
				}
			}
		}
		$con->select('*');
		$con->from($this->getTableName().' '.$this->getTableAlias());
		
		return $con->queryRow();
	}
	
	/*
	 * Hàm thực hiện tìm kiếm database
	 * 
	 * @param string $attrs chuỗi thuộc tính, ví dụ id=:id AND title=:title
	 * @param array $value mảng tham số kèm theo, ví dụ array(':id'=>1,':title'=>'Nguyễn Như Tuấn')
	 * @param array $option mảng option, ví dụ array('select'=>'*','limit'=>5)
	 * @return array object trả về mảng đối tượng record
	 */
	public function findByAttributes($attrs, $params=array(), $option=array()) {
		$con = $this->getCon();
		//Load query option
		if(!empty($option)) {
			foreach ($option as $method => $value) {
				if(method_exists($con, $method)) {
					$con->$method($value);
				}
			}
		}
		$con->from($this->getTableName().' '.$this->getTableAlias());
		$con->addWhere($attrs,$params);
		
		return $con->queryAll();
	}
	
	/*
	 * Hàm thực hiện tìm kiếm database
	 * 
	 * Hàm thực hiện tìm kiếm bản ghi record với khóa có giá trị truyền vào, hàm trả về đối tượng hiện tại. Model hiện tại cũng mang các giá trị của record được tìm thấy
	 * 
	 * @param $param giá trị của khóa
	 * @param string $pkname tên của khóa
	 * @return object hiện tại
	 */
	public function findByPk($param, $pkname='id') {
		$con = $this->getCon();		
		$con->select('*');
		$con->from($this->getTableName().' '.$this->getTableAlias());
		$con->addWhere($pkname.'=:'.$pkname,array(':'.$pkname=>$param));
		$datas = (array)$con->queryRow();
		if(!empty($datas)) {
			$this->setAttributes($datas);
			$this->setIsNew(false);
			return $this;
		} else {
			return NULL;
		}
	}
	////////////////////////////////////////////////////////////////////////////
	/*
	 * @return object trả về đối tượng {@link Mysqli}
	 */
	public function getCon() {
		return $this->_con;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_con}
	 * 
	 * @param object $con {@link Mysqli}
	 */
	public function setCon($con) {
		$this->_con = $con;
	}
	
	/*
	 * @return string trả về tên bảng
	 */
	public function getTableName() {
		return $this->tableName;
	}
	
	/*
	 * @return string trả về alias bảng
	 */
	public function getTableAlias() {
		return $this->tableAlias;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $tableAlias}
	 * 
	 * @param string $alias
	 */
	public function setTableAlias($alias) {
		$this->tableAlias = $alias;
		return $this;
	}
	
	/*
	 * @return boolean trả về true hoặc false nếu là thêm mới hoặc cập nhật
	 */
	public function getIsNew() {
		return $this->_is_new;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_is_new}
	 * 
	 * @param boolean $is_new
	 */
	public function setIsNew($is_new) {
		$this->_is_new = $is_new;
	}
	
	/*
	 * @return trả về khóa
	 */
	public function getPk() {
		return $this->_pk;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_pk}
	 * 
	 * @param $_pk khóa
	 */
	public function setPk($_pk) {
		$this->_pk = $_pk;
	}
	
	/*
	 * Hàm thêm giá trị biến {@link $_pk}
	 * 
	 * @param string $_pk tên của khóa
	 */
	public function addPk($_pk) {
		$this->_pk[] = $_pk;
	}
	
	/*
	 * Hàm gán giá trị cho các field
	 * 
	 * @param array $datas mảng các giá trị, mảng này có khóa là tên của các field, ví dụ: array('name'=>'Nguyễn Như Tuấn', 'age'=>30)
	 */
	public function setAttributes($datas) {
		if(empty($datas)) return false;
		foreach ($datas as $key => $value) {
			if (array_key_exists($key, $this->_attributes)) {
				$this->_attributes[$key] = $datas[$key];
			} elseif(property_exists($this , $key)) {
				$this->$key = $datas[$key];
			}
		}
	}
	
	/*
	 * Thực hiện trả về giá trị của field
	 * 
	 * @param string $name tên của field
	 * @return giá trị của field
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->_attributes)) {
			return $this->_attributes[$name];
		}
		return null;
    }
	
	/*
	 * Thực hiện gán giá trị của field
	 * 
	 * @param string $name tên của field
	 * @param $value giá trị của field
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->_attributes)) {
			$this->_attributes[$name] = $value;
		}
    }
	
}
