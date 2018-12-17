<?php
namespace Model;
class ArticleModel extends \Core\Model {
	// 获取用户自己的文章
	public function getList(){
		$sql='select a.*,c.cat_name from article a inner join category c using(cat_id) where is_recycle=0 and user_id='. $_SESSION['user']['user_id'];
		return $this->db->fetchAll($sql);
	}
	// 获取所有公开的,不在回收站内的文章
	public function getHomeList(){
		$sql="select a.*,c.cat_name,u.user_name from article a inner join category c using(cat_id) inner join user u using(user_id) where is_recycle=0 and is_public=1 order by is_top desc";
		return $this->db->fetchAll($sql);
	}
	// 通过类别id获取对应的文章
	public function getHomeArticleByCatId($cat_id){
		$cat_model=new CategoryModel();  //实例化Category模型
		$sub_cat_list=$cat_model->getCategoryTree($cat_id); //获取cat_id的子级
		$ids[]=$cat_id;   //$ids数组保存自己和子级的cat_id
		foreach($sub_cat_list as $rows){
			$ids[]=$rows['cat_id'];
		}
		$ids=implode(',',$ids); //将所有的cat_id用逗号连接成字符串
		$sql="select a.*,c.cat_name,u.user_name from article a inner join Category c using(cat_id) inner join user u using (user_id) where is_recycle=0 and is_public=1 and cat_id in($ids) order by is_top desc";
		return $this->db->fetchAll($sql);
	}
	// 软删除
	public function softDel($art_id){
		$sql="update article set is_recycle=1 where art_id in ($art_id)";
		return $this->db->exec($sql);
	}
	// 检索文章
	public function searchList(){
		$sql='select a.*,c.cat_name from article a inner join category c using(cat_id) where is_recycle=0 and user_id='.$_SESSION['user']['user_id'];
		if($_POST['cat_id']!=''){
			$cat_model=new CategoryModel();
			$sub_cat_list=$cat_model->getCategoryTree($_POST['cat_id']);  //找出cat_id的子级
			$sub_cat_ids[0]=$_POST['cat_id'];
			foreach($sub_cat_list as $rows){
				$sub_cat_ids[]=$rows['cat_id'];
			}
			$ids=implode(',',$sub_cat_ids);   //自己和子级的cat_id数组
			$sql.=" and cat_id in ({$ids})";
		}
		if($_POST['title']!=='')
			$sql.=" and art_title like '%{$_POST['title']}%'";
		if($_POST['content']!=='')
			$sql.=" and art_content like '%{$_POST['content']}%'";
		if($_POST['is_public']!=='')
			$sql.=" and is_public={$_POST['is_public']}";
		if($_POST['is_top']!=='')
			$sql.=" and is_top={$_POST['is_top']}";
		return $this->db->fetchAll($sql);
	}
	// 阅读数加1
	public function updateArticleReadCount($art_id){
		// art_1表示标号是1的文章
		if(isset($_SESSION['art_'.$art_id]))
			return;
		$_SESSION['art_'.$art_id]=1;  //表示编号是$art_id的文章已经读过了,用来防止灌水
		$sql="update article set art_read=art_read+1 where art_id=$art_id";
		return $this->db->exec($sql);
	}
	/*
	 *踩和赞
	 *@param $art_id int 文章编号
	 *@param @flag int 1表示赞,0表示踩
	 */
	public function setUpDown($art_id,$flag){
		if(isset($_SESSION['updown_'.$art_id]))  // 已经赞过或者踩过了
			return false;
		// 执行踩或赞
		if($flag){  //赞
			$sql="update article set art_up=art_up+1 where art_id=$art_id";
		}else{
			$sql="update article set art_down=art_down+1 where art_id=$art_id";
		}
		$_SESSION['updown_'.$art_id]=1;   //表示已经踩或者赞
		return $this->db->exec($sql);
	}
	/*
	 * 获取上一篇或下一篇的文章编号
	 * @param $art_id int 当前文章id
	 * @param $type  int 0上一篇 1下一篇
	 * @return int 上一篇或下一篇的编号
	 */
	public function getPrevOrNextArtId($art_id,$type){
		if($type){   //下一篇
			$sql="select art_id from article where art_id>$art_id and is_recycle=0 and is_public=1 order by art_id limit 1";
		}else {    //下一篇
			$sql="select art_id from article where art_id<$art_id and is_recycle=0 and is_public=1 order by art_id desc limit 1";
		}
		return $this->db->fetchColumn($sql);
	}
	/*
	 * 获取Article分页
	 * @param $startno int 起始位置
	 * @param $pagesize int 页面大小
	 * @return array 当前页面的记录
	 */
	public function getPageList($startno,$pagesize){
		$sql="select a.*,c.cat_name,u.user_name from article a inner join category c using (cat_id) inner join user u using(user_id) where is_recycle=0 and is_public=1 order by is_top desc";
		$sql.=" limit $startno,$pagesize";
		return $this->db->fetchAll($sql);
	}
	// 获取总记录数
	public function getArticleCount(){
		$sql="select count(*) from article where is_recycle=0 and is_public=1";
		return $this->db->fetchColumn($sql);
	}
	// 寻找所有标记删除的文章
	public function getRecyclePaper(){
		$sql='select a.*,c.cat_name from article a inner join category c using(cat_id) where is_recycle=1 and user_id='. $_SESSION['user']['user_id'];
		return $this->db->fetchAll($sql);
	}
	// 还原回收站文章
	public function  reBack($art_id){
		$sql="update article set is_recycle=0 where art_id=$art_id;";
		return $this->db->exec($sql);
	}
	// 回收站永久删除文章
	public function fdel($art_id){
		$sql="delete from article where art_id=$art_id;";
		return $this->db->exec($sql);
	}
	// 首页文章搜索
	public function indexPaperSearch($keyword){
		$sql="select a.*,c.cat_name,u.user_name from article a inner join category c using(cat_id) inner join user u using(user_id) where is_recycle=0 and is_public=1 and art_title like '%{$keyword}%' order by is_top desc";
		return $this->db->fetchAll($sql);
	}
}
