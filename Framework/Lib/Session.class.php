<?php
namespace Lib;
class Session{
	private $db;
	public function __construct(){
		session_set_save_handler(
			array($this,'open'),
			array($this,'close'),
			array($this,'read'),
			array($this,'write'),
			array($this,'destroy'),
			array($this,'gc')
		);
		session_start();  //开启session
	}
	public function open(){
		$this->db=\Core\MyPDO::getInstance($GLOBALS['config']['database']);
		return true;
	}
	public function close(){
		return true;
	}
	public function read($sess_id){
		$sql="select sess_value from session where sess_id='$sess_id'";
		return (string)$this->db->fetchColumn($sql);
	}
	public function write($sess_id,$sess_value){
		$time=time();
		$sql="insert into session values('$sess_id','$sess_value','$time') on duplicate key update sess_value='$sess_value',sess_time=$time";
		return $this->db->exec($sql)!==false; //使用不全等,因为会返回0
	}
	public function destroy($sess_id){
		$sql="delete from session where sess_id='$sess_id'";
		return $this->db->exec($sql)!==false;
	}
	public function gc($lifetime){
		$time=time()-$lifetime;   //会话过期的时间点
		$sql="delete from session where sess_time < $time";
		return $this->db->exec($sql)!==false;
	}
}
