<?php
namespace Controller\Admin;
class LinkController extends \Controller\Admin\BaseController{
	// 友情链接
	public function listAction(){
		$mode=new \Core\Model('link');
		$list=$mode->select();
		$this->smarty->assign('list',$list);
		$this->smarty->display('link_list.html');
	}
	// 添加链接
	public function addAction(){
		// 执行添加的逻辑
		if(!empty($_POST)){
			$_POST['link_time']=time();
			$model=new \Core\Model('link');
			if($model->insert($_POST)){
				$this->success('index.php?p=Admin&c=Link&a=list','添加成功');
			}else{
				$this->error('index.php?p=Admin&c=Link&a=add','添加失败');
			}
		}
		// 显示添加的页面
		$this->smarty->display('link_add.html');
	}
	// 修改连接
	public function alterAction(){
		$link_id=$_GET['link_id'];
		$model=new \Core\Model('link');
		$link_content=$model->Find($link_id);
		if(!empty($_POST)){
			$_POST['link_id']=$link_id;
			$_POST['link_time']=time();
			if($model->update($_POST)){
				$this->success('index.php?p=Admin&c=Link&a=list','修改成功');
			}else{
				$this->error('index.php?p=Admin&c=Link&a=alter','修改失败');
			}
		}
		$this->smarty->assign('link_content',$link_content);
		$this->smarty->display('link_alter.html');
	}
	//删除连接
	public function delAction(){
		$link_id=$_GET['link_id'];
		$model=new \Core\Model('link');
		if($model->delete($link_id)){
				$this->success('index.php?p=Admin&c=Link&a=list','删除成功');
			}else{
				$this->error('index.php?p=Admin&c=Link&a=del','删除失败');
			}
	}
}



