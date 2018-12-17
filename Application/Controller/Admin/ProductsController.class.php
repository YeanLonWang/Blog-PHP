<?php
namespace Controller\Admin;
//products控制器
class ProductsController extends \Core\Controller {
	//显示商品
	public function listAction() {
            // $products=new \Model\ProductsModel;		//调用模型
            // $list=$products->getList();
            $products=new \Core\Model('products');  //调用模型
            $list=$products->select();  //直接调用基础模型类中的万能的方法
            $this->smarty->assign('list',$list);
            $this->smarty->display('products_list.html');
	}
	//删除商品
	public function delAction() {
		$proid=(int)$_GET['proid'];		//获取删除的商品id
		$model=new \Model\ProductsModel;	//实例化商品模型
		// if($model->del($proid)){	//调用模型的del()方法
		if($model->delete($proid)){	//直接调用基础模型类中的delete()方法
                    $this->success('index.php?p=Admin&c=Products&a=list', '删除成功');
		}else {
                    $this->error('index.php?p=Admin&c=Products&a=list', '删除失败');
		}
	}
}
