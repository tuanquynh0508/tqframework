<?php
/**
 * Tập tin TQDatabase class.
 *
 * @author Nguyễn Như Tuấn <tuanquynh0508@gmail.com>
 * @link http://tqframework.i-designer.net/
 * @copyright 2014-2014 I-Designer
 * @license http://tqframework.i-designer.net/license/
 * @package system
 * @see TQClassBase
 * @since 1.0
 */

namespace TQCore;

use TQFramework\TQBase as TQBase;
use TQCore\TQException as TQExceptionHandle;

class TQDatabase extends TQClassBase{
	/*
	 * @var string mã encoding
	 */		
	private $_charset='UTF-8';
	
	/*
	 * @var object đối tượng {@link mysqli}
	 */
	private $_con=NULL;	
	
	/*
	 * @var string chuỗi Select
	 */
	private $_select='*';
	
	/*
	 * @var string chuỗi From
	 */
	private $_from='';
	
	/*
	 * @var string chuỗi Where
	 */
	private $_where='';
	
	/*
	 * @var array mảng Join
	 */
	private $_join=array();
	
	/*
	 * @var string chuỗi Order
	 */
	private $_order='';
	
	/*
	 * @var array mảng tham số
	 */
	private $_params=array();
	
	/*
	 * @var number giới hạn Limit
	 */
	private $_limit=0;
	
	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm khởi tạo
	 * 
	 * Thực hiện kết nối database thông qua thư viện mysqli, và set encoding cho database
	 * 
	 * @param string $connect_db tên của host database
	 * @param string $connect_host tên của host database
	 * @param string $connect_username user của host database
	 * @param string $connect_password mật khẩu của host database
	 * @param number $connect_port tên port host database
	 * @param string $connect_charset mã encoding của host database
	 * @throws TQExceptionHandle nếu kết nối database thất bại
	 */
	function __construct($connect_db='', $connect_host='localhost', $connect_username='root', $connect_password='', $connect_port=3306, $connect_charset='utf8') {
		if(empty($connect_db)) return false;
		parent::__construct();
		$this->_charset = $connect_charset;		
		$this->_con = @new \mysqli($connect_host, $connect_username, $connect_password, $connect_db, $connect_port);
		if($this->_con->connect_error) {			
			throw new TQExceptionHandle("Connect Error {$this->_con->connect_errno}: {$this->_con->connect_error}", 500);
		} else {
			$this->_con->query("SET NAMES $connect_charset");
		}
	}
	
	/*
	 * Hàm __destruct
	 * 
	 * Hủy kết nối database nếu kết thúc hàm
	 */
	public function __destruct() {
		//@mysqli_close($this->getCon());
		$this->getCon()->close();
	}
	////////////////////////////////////////////////////////////////////////////
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @return array object trả về một mảng các đối tượng record
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function queryAll() {
		$sql = '';
		$sql .= $this->getSelect();
		$sql .= $this->getFrom();
		$sql .= $this->getJoin();
		$sql .= $this->getWhere();
		$sql .= $this->getOrder();
		$sql .= $this->getLimit();	
		$list = array();
		if ($result = $this->getCon()->query($sql)) {
			while($obj = $result->fetch_object()){ 
				$list[] = $obj; 				
			}
			$result->close();
		} else {			
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $list;
	}
	
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @return object trả về đối tượng record
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function queryRow() {
		$sql = '';
		$sql .= $this->getSelect();
		$sql .= $this->getFrom();
		$sql .= $this->getJoin();
		$sql .= $this->getWhere();		
		$sql .= 'LIMIT 0,1';
		$obj = NULL;
		if ($result = $this->getCon()->query($sql)) {
			$obj = $result->fetch_object();
			$result->close();
		} else {			
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $obj;
	}
	
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @return number trả về số lượng record
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function queryCount() {
		$sql = '';
		$sql .= 'SELECT COUNT(1) ';
		$sql .= $this->getFrom();
		$sql .= $this->getJoin();
		$sql .= $this->getWhere();		
		$field = 0;
		if ($result = $this->getCon()->query($sql)) {
			$obj = $result->fetch_row();
			if(!empty($obj[0])) {
				$field = $obj[0];
			}
			$result->close();
		} else {			
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $field;
	}
	
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @return trả về field đầu tiên trong Select
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function queryScalar() {
		$sql = '';
		$sql .= $this->getSelect();
		$sql .= $this->getFrom();
		$sql .= $this->getJoin();
		$sql .= $this->getWhere();		
		$sql .= 'LIMIT 0,1';		
		$field = NULL;
		if ($result = $this->getCon()->query($sql)) {
			$obj = $result->fetch_row();
			if(!empty($obj[0])) {
				$field = $obj[0];
			}
			$result->close();
		} else {
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $field;
	}
	
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @param string $sql câu truy vấn SQL cần thực hiện
	 * @return array object trả về một mảng các đối tượng record
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function querySQL($sql) {		
		$list = array();
		if ($result = $this->getCon()->query($sql)) {
			while($obj = $result->fetch_object()){ 
				$list[] = $obj; 				
			}
			$result->close();
		} else {			
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $list;		
	}
	
	/*
	 * Hàm thực hiện truy vấn database
	 * 
	 * @param string $sql câu truy vấn SQL cần thực hiện
	 * @return number số lượng record được thực hiện truy vấn
	 * @throws TQExceptionHandle nếu truy vấn database thất bại
	 */
	public function executeSQL($sql) {		
		$result = 0;
		if ($this->getCon()->query($sql)) {
			$result = $this->getCon()->affected_rows;
		} else {			
			$mes = TQBase::getApp()->t('tqbase','Query [{sql}]. Has error: {error}',array('sql'=>$sql,'error'=>$this->getCon()->error));
			throw new TQExceptionHandle($mes, 500);
		}
		$this->resetOption();
		return $result;		
	}
	//--------------------------------------------------------------------------
	/*
	 * Hàm thực hiện bắt đầu một transaction	 
	 */
	public function beginTransaction() {
		$this->getCon()->autocommit(FALSE);
	}
	
	/*
	 * Hàm thực hiện comit một transaction	 
	 */
	public function commit() {
		$this->getCon()->commit();
	}
	
	/*
	 * Hàm thực hiện rollback một transaction	 
	 */
	public function rollback() {
		$this->getCon()->rollback();
	}
	
	/*
	 * Hàm thực hiện kết thúc một transaction	 
	 */
	public function endTransaction() {
		$this->getCon()->autocommit(TRUE);
	}
	//--------------------------------------------------------------------------
	
	/*
	 * Gán giá trị cho select
	 * 
	 * @param string $value chuỗi select, chú ý không có chữ SELECT ở trong chuỗi
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function select($value) {
		$this->setSelect($value);
		return $this;
	}
	
	/*
	 * Gán giá trị cho from
	 * 
	 * @param string $value chuỗi from, chú ý không có chữ FROM ở trong chuỗi
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function from($value) {
		$this->setFrom($value);
		return $this;
	}	
	
	/*
	 * Gán giá trị cho where
	 * 
	 * @param string $value chuỗi where, chú ý không có chữ WHERE ở trong chuỗi, các field và param có thể viết id=:id AND is_public=1
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function where($value) {		
		$this->setWhere($value);
		return $this;
	}
	
	/*
	 * Gán giá trị cho where
	 * 
	 * Format của mảng join như ví dụ sau
	 * array(
	 *		array(
	 *		'join' => 'LEFT JOIN',
	 *		'table' => 'tbl_category c',
	 *		'on' => 'e.category_id = c.id'
	 *		),
	 * )
	 * 
	 * @param array $value mảng join
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function join($value) {		
		$this->setJoin($value);
		return $this;
	}
	
	/*
	 * Gán giá trị cho order
	 * 
	 * @param string $value chuỗi order
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function order($value) {		
		$this->setOrder($value);
		return $this;
	}
	
	/*
	 * Gán giá trị cho param
	 * 
	 * @param array $value mảng giá trị parram, ví dụ array(':id'=>1)
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function params($value) {		
		$this->setParams($value);
		return $this;
	}
	
	/*
	 * Gán giá trị cho limit
	 * 
	 * @param number $value giá trị giới hạn limit
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function limit($value) {		
		$this->setLimit($value);
		return $this;
	}
	
	/*
	 * Thêm điều kiện where
	 * 
	 * @param string $field chuỗi điều kiện, ví dụ id=:id
	 * @param array $value mảng tham số kèm theo, ví dụ array(':id'=>1)
	 * @param string $type kiểu điều kiện AND hoặc OR
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function addWhere($field,$value,$type='AND') {
		if(trim($this->_where)!='') {
			$this->_where .= ' '.$type;
		}
		$this->_where .= ' '.$field;
		$this->_params = array_merge_recursive($this->_params,$value);
		return $this;
	}
	
	/*
	 * Thêm điều kiện where in
	 * 
	 * @param string $field tên trường, ví dụ e.id
	 * @param array $value mảng tham số kèm theo, ví dụ array(1,2,3,4)
	 * @param string $type kiểu điều kiện AND hoặc OR
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function addWhereIn($field,$value,$type='AND') {
		if(trim($this->_where)!='') {
			$this->_where .= ' '.$type;
		}
		$name_field = ':'.str_replace('.', '_', $field).'_'.count($this->_params);
		$this->_where .= ' '.$field.' IN ('.$name_field.')';
		$this->_params[$name_field] = "'".implode("','", $value)."'";
		return $this;
	}
	
	/*
	 * Thêm điều kiện where not in
	 * 
	 * @param string $field tên trường, ví dụ e.id
	 * @param array $value mảng tham số kèm theo, ví dụ array(1,2,3,4)
	 * @param string $type kiểu điều kiện AND hoặc OR
	 * @return object trả về đối tượng TQDatabase hiện tại
	 */
	public function addWhereNotIn($field,$value,$type='AND') {
		if(trim($this->_where)!='') {
			$this->_where .= ' '.$type;
		}
		$name_field = ':'.str_replace('.', '_', $field).'_'.count($this->_params);
		$this->_where .= ' '.$field.' NOT IN ('.$name_field.')';
		$this->_params[$name_field] = "'".implode("','", $value)."'";
		return $this;
	}
	
	/*
	 * Hàm reset các giá trị về mặc định
	 */
	protected function resetOption() {
		$this->setSelect('*');
		$this->setFrom('');
		$this->setWhere('');
		$this->setJoin(array());
		$this->setOrder('');
		$this->setParams(array());
		$this->setLimit(0);		
	}
	////////////////////////////////////////////////////////////////////////////
	
	/*
	 * @return string trả về giá trị cảu biến {@link $_charset}
	 */
	public function getCharset() {
		return $this->_charset;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_charset}
	 * 
	 * @param string $charset
	 */
	public function setCharset($charset) {
		$this->_charset = $charset;
	}
	
	/*
	 * @return object trả về đối tượng {@link Mysqli}
	 */
	public function getCon() {
		return $this->_con;
	}
	
	/*
	 * @return string trả về chuỗi select
	 */
	public function getSelect() {
		return (!empty(trim($this->_select)))?'SELECT '.trim($this->_select).' ':'SELECT * ';
	}
	
	/*
	 * @return string trả về chuỗi from
	 */
	public function getFrom() {
		return (!empty(trim($this->_from)))?'FROM '.trim($this->_from).' ':'';
	}
	
	/*
	 * @return string trả về chuỗi where
	 */
	public function getWhere() {
		if(empty(trim($this->_where))) {
			return '';
		}
		
		$sql = '';		
		$sql .= 'WHERE '.trim($this->_where).' ';		
		
		//Assign params
		$params = $this->getParams();
		$sql = preg_replace_callback(
		'/:(\w+)/',
		function($m) use ($params) {
			if(isset($params[$m[0]])) {
				//return $this->getCon()->real_escape_string($params[$m[0]]);
				return $params[$m[0]];
			}			
		},
		$sql);
		
		return $sql;
	}
	
	/*
	 * @return string trả về chuỗi join
	 */
	public function getJoin() {
		if(empty($this->_join)) {
			return '';
		}
		$sql = '';
		foreach ($this->_join as $item) {
			$sql .= trim($item['join']).' ';
			$sql .= trim($item['table']).' ';
			$sql .= 'ON '.trim($item['on']).' ';
		}
		return $sql;
	}
	
	/*
	 * @return string trả về giá trị order
	 */
	public function getOrder() {
		return (!empty(trim($this->_order)))?'ORDER BY '.trim($this->_order).' ':'';
	}
	
	/*
	 * @return array trả về giá trị cảu biến {@link $_params}
	 */
	public function getParams() {
		return $this->_params;
	}
	
	/*
	 * @return string trả về limit
	 */
	public function getLimit() {
		return (!empty(trim($this->_limit)))?'LIMIT 0,'.trim($this->_limit):'';
	}	
	//--------------------------------------------------------------------------
	/*
	 * Hàm gán giá trị biến {@link $_con}
	 * 
	 * @param object $con {@link Mysqli}
	 */
	public function setCon($con) {
		$this->_con = $con;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_select}
	 * 
	 * @param string $select
	 */
	public function setSelect($select) {
		$this->_select = $select;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_where}
	 * 
	 * @param string $where
	 */
	public function setWhere($where) {
		$this->_where = $where;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_join}
	 * 
	 * @param array $join
	 */
	public function setJoin($join) {
		$this->_join = $join;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_order}
	 * 
	 * @param string $order
	 */
	public function setOrder($order) {
		$this->_order = $order;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_params}
	 * 
	 * @param array $params
	 */
	public function setParams($params) {
		$this->_params = $params;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_limit}
	 * 
	 * @param numnber $limit
	 */
	public function setLimit($limit) {
		$this->_limit = $limit;
	}
	
	/*
	 * Hàm gán giá trị biến {@link $_from}
	 * 
	 * @param string $from
	 */
	public function setFrom($from) {
		$this->_from = $from;
	}	

}
