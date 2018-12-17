<?php
namespace Model;
//products表的模型，用来操作products表
class ProductsModel extends \Core\Model {
	public function getList() {
		//获取products表的数据
		return $this->db->fetchAll('select * from Products');
	}
	/**
	*删除商品
	*@param $proid int 删除商品的变号
	*/
	public function del($proid) {
		return $this->db->exec("delete from products where proid=$proid");
	}
}

