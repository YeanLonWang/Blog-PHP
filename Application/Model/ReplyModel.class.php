<?php
// 回复模块
namespace Model;
class ReplyModel extends \Core\Model{
	private $tree=array();
	// 获取回复树
	public function getReplyTree($art_id){
		$sql="select r.*,u.user_name,u.user_face from reply r natural join user u where art_id=$art_id";
		$list=$this->db->fetchAll($sql);
		$this->createTree($list);
		return $this->tree;
	}
	// 创建树形结构
	private function createTree($list,$parent_id=0,$deep=0){
		foreach($list as $rows){
			if($rows['parent_id']==$parent_id){
				$rows['deep']=$deep;
				$this->tree[]=$rows;
				$this->createTree($list,$rows['reply_id'],$deep+1);
			}
		}
	}
}