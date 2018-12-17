<?php
namespace Controller\Admin;
class ArticleController extends \Controller\Admin\BaseController{
	// 文章列表
	public function listAction(){
		$art_model=new \Model\ArticleModel();
		if(!empty($_POST)){  //获取搜索的文章
			// var_dump($_POST);die();
			$art_list=$art_model->searchList();
		}else{               //获取自己的所有文章
			$art_list=$art_model->getList();
		}

		$cat_model=new \Model\CategoryModel();
		$cat_list=$cat_model->getCategoryTree();
		$this->smarty->assign('data',array(
			'art_list'  =>  $art_list,
			'cat_list'  =>  $cat_list
		));
		$this->smarty->display('article_list.html');
	}
	// 添加文章
	public function addAction(){
		// 执行添加逻辑
		if(!empty($_POST)){
			$data['art_title']=$_POST['title'];
			$data['art_content']=$_POST['content'];
			$data['art_time']=time();
			$data['cat_id']=$_POST['cat_id'];
			$data['user_id']=$_SESSION['user']['user_id'];
			$data['is_top']=$_POST['is_top'];
			$data['is_public']=$_POST['is_public'];
			$art_model=new \Core\Model('article');
			if($art_model->insert($data))
				$this->success('index.php?p=Admin&c=Article&a=list','添加成功');
			else
				$this->error('index.php?p=Admin&c=Article&a=add','添加失败');
		}
		// 显示添加页面
		$cat_model=new \Model\CategoryModel();
		$cat_list=$cat_model->getCategoryTree();

		$this->smarty->assign('cat_list',$cat_list);
		$this->smarty->display('article_add.html');
	}
	// 修改文章
	public function editAction(){
		$art_id=(int)$_GET['art_id'];
		$art_model=new \Core\Model('article');
		//更改逻辑
		if(!empty($_POST)){
			$data['art_title']=$_POST['title'];
			$data['art_content']=$_POST['content'];
			$data['cat_id']=$_POST['cat_id'];
			$data['is_top']=$_POST['is_top'];
			$data['is_public']=$_POST['is_public'];
			$data['art_id']=$art_id;
			$rs=$art_model->update($data);
			if($rs)
				$this->success('index.php?p=Admin&c=Article&a=list','修改成功');
			else
				$this->error('index.php?p=Admin&c=Article&a=edit&art_id='.$art_id,'修改失败');
		}
		// 显示修改页面
		$art_info=$art_model->Find($art_id);  //修改的文章信息
		$cat_model=new \Model\CategoryModel();
		$cat_list=$cat_model->getCategoryTree();
		$this->smarty->assign('data',array(
			'cat_list'  => $cat_list,
			'art_info'  => $art_info
		));
		$this->smarty->display('article_edit.html');
	}
	// 删除文章
	public function delAction(){
		$art_id=$_GET['art_id'];
		$art_model=new \Model\ArticleModel();
		if($art_model->softDel($art_id))
			$this->success('index.php?p=Admin&c=Article&a=list','删除成功');
		else
			$this->error('index.php?p=Admin&c=Article&a=list','删除失败');
	}
	// 文章置顶
	public function topAction(){
		$data['art_id']=(int)$_GET['art_id'];   //文章编号
		$data['is_top']=(int)$_GET['is_top'];   //是否置顶
		$art_model=new \Core\Model('article');
		if($art_model->update($data))
			$this->success('index.php?p=Admin&c=Article&a=list','更新成功');
		else
			$this->error('index.php?p=Admin&c=Article&a=list','更新失败');
	}
	// 回收站还原删除
	public function reAction(){
		if(isset($_GET['q'])){
			$art_id=$_GET['art_id'];
		}
		$art_model=new \Model\ArticleModel();
		$art_list=$art_model->getRecyclePaper();
		if(isset($_GET['q']) && $_GET['q'] == '0'){    //还原
			if($art_model->reBack($art_id))
			$this->success('index.php?p=Admin&c=Article&a=re','还原成功');
		else
			$this->error('index.php?p=Admin&c=Article&a=re','还原失败');
		}elseif(isset($_GET['q']) && $_GET['q'] == '1'){    //永久删除
			if($art_model->fdel($art_id))
			$this->success('index.php?p=Admin&c=Article&a=re','永久删除成功');
		else
			$this->error('index.php?p=Admin&c=Article&a=re','永久删除失败');
		}

		$this->smarty->assign('art_list',$art_list);
		$this->smarty->display('article_recycle.html');
	}
}
