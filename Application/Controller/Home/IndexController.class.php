<?php
// 前台控制器
namespace Controller\Home;
class IndexController extends BaseController{
	// 显示首页
	public function indexAction(){
		$art_model=new \Model\ArticleModel();
		$recordcount=$art_model->getArticleCount();  //获取总记录数
		$page=new \Lib\Page($recordcount,$GLOBALS['config']['app']['pagesize']);
		// $art_list=$art_model->getHomeList();    //获取可以公开访问的文章
		$art_list=$art_model->getPageList($page->startno,$GLOBALS['config']['app']['pagesize']);
		$page_str=$page->show();
		$cat_model=new \Model\CategoryModel();
		$cat_list=$cat_model->getCategoryTree();  //获取类别树
		$link_model=new \Core\Model('link');
		$link_list=$link_model->select();    //获取所有友情链接
		$this->smarty->assign('data',array(
			'art_list'  => $art_list,
			'cat_list'  => $cat_list,
			'link_list' => $link_list,
			'page_str'  => $page_str
		));
		$this->smarty->display('index.html');
	}
	public function spAction(){
		/*
		 *  1,index页面传递过了一个关键字,
		 *  2,将关键字传递给model查找关键字相关内容
		 *  3,展示搜索后的结果
		 */
		if(isset($_POST['txt'])){
			$keyword=$_POST['txt'];
			$art_model=new \Model\ArticleModel();
			$art_list=$art_model->indexPaperSearch($keyword);    //获取可以公开访问的文章

			$cat_model=new \Model\CategoryModel();
			$cat_list=$cat_model->getCategoryTree();
			$link_model=new \Core\Model('link');
			$link_list=$link_model->select();      //获取所有的友情链接
			$this->smarty->assign('data',array(
				'art_list'  => $art_list,
				'cat_list'  => $cat_list,
				'link_list' => $link_list,
				'keyword'   => $keyword
			));
			$this->smarty->display('list.html');
		}
	}
}