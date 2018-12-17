<?php
// 文章列表控制器
namespace Controller\Home;
class ArticleController extends BaseController{
	public function listAction(){
		$art_model=new \Model\ArticleModel();
		$cat_id=(int)$_GET['cat_id'];          //获取类别的id
		$art_list=$art_model->getHomeArticleByCatId($cat_id);  //通过cat_id获取文章
		$cat_model=new \Model\CategoryModel();
		$cat_list=$cat_model->getCategoryTree();
		$link_model=new \Core\Model('link');
		$link_list=$link_model->select();      //获取所有的友情链接
		$this->smarty->assign('data',array(
			'art_list'  => $art_list,
			'cat_list'  => $cat_list,
			'link_list' => $link_list,
			'keyword'   => ''
		));
		$this->smarty->display('list.html');
	}
	// 内容页面
	public function articleAction(){
		$art_id=(int)$_GET['art_id'];    //文章id
		//执行添加主评论
		if(!empty($_POST)){
			// echo 1,$_POST['reply_id'];die();
			// 如果没有登陆则先登录
			if(!isset($_SESSION['user']))
				$this->error('index.php?p=Admin&c=Login&a=login','添加回复前请先登录');
			// 如果登录了就添加回复数据
			$data['art_id']=$art_id;
			$data['user_id']=$_SESSION['user']['user_id'];
			$data['reply_content']=$_POST['txaArticle'];
			$data['reply_time']=time();
			// 添加子评论
			if(isset($_POST['reply_id']) && $_POST['reply_id'] != ""){
				$data['parent_id']=$_POST['reply_id'];
			}else{
				$data['parent_id']=0;
			}
			// 添加子评论结束
			$reply_model=new \Core\Model('reply');
			if($reply_model->insert($data))
				$this->success('index.php?p=Home&c=Article&a=article&art_id='.$art_id,'评论成功');
			else
				$this->error('index.php?p=Home&c=Article&a=article&art_id='.$art_id,'评论失败');

		}
		//添加主评论结束
		// 显示内容页面
		$art_model=new \Model\ArticleModel();
		$art_model->updateArticleReadCount($art_id);
		$info=$art_model->Find($art_id);    //获取点击的文章内容
		// 获取回复树
		$reply_model=new \Model\ReplyModel();
		$reply_list=$reply_model->getReplyTree($art_id);
		$this->smarty->assign('data',array(
			'info'  => $info,
			'reply_list'  => $reply_list
		));
		$this->smarty->display('article.html');
	}
	// 踩,赞
	public function UpDownAction(){
		$art_id=(int)$_GET['art_id'];   //文章编号
		$flag=(int)$_GET['flag'];     //1赞  0踩
		$art_model=new \Model\ArticleModel();  //实例化article模型
		$msg=$flag?'赞':'踩';
		if($art_model->setUpDown($art_id,$flag)){
			$this->success('index.php?p=Home&c=Article&a=article&art_id='.$art_id,"{$msg}操作成功");
		}else{
			$this->error('index.php?p=Home&c=Article&a=article&art_id='.$art_id,"{$msg}失败,您已经操作过了");
		}
	}
	// 上一篇或下一篇
	public function prevNextArticleAction(){
		$art_id_1=(int)$_GET['art_id'];   // 得到当前文章id
		$type=(int)$_GET['type'];         //0上一篇,1下一篇
		$art_model=new \Model\ArticleModel();   //实例化article表模型
		if($art_id_2=$art_model->getPrevOrNextArtId($art_id_1,$type)){
			header("location:index.php?p=Home&c=Article&a=article&art_id=$art_id_2");  //跳转到文章页面
		}else{
			$msg=$type?'已经是最后一篇了':'已经是第一篇了';
			$this->error('index.php?p=Home&c=Article&a=article&art_id='.$art_id_1,$msg);
		}

	}
}
