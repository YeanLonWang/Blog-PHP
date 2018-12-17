<?php
namespace Core;
//基础模型类
class Model {
    private $table;     //操作的表名
    private $pk;        //主键
    protected $db;
    /*
     * @param $table string 操作的表名
     */
    public function __construct($table='') {
            $this->initDB();
            $this->getTable($table);
            $this->getPrimaryKey();
    }
    //初始化连接数据库
    private function initDB() {
            $this->db=MyPDO::getInstance($GLOBALS['config']['database']);
    }
    //获取表名
    private function getTable($table){
        if($table==''){
            $class_name=basename(get_class($this));	//获取对象属于的类
            $this->table=substr($class_name,0,-5);
        }else{
            $this->table=$table;
        }
    }
    //获取表的主键
    private function getPrimaryKey(){
        $rs= $this->db->fetchAll("desc `$this->table`");
        foreach($rs as $rows){
            if($rows['Key']=='PRI'){
                $this->pk=$rows['Field'];
                return;
            }
        }
    }
    /*
     * 封装万能的insert语句
     * @param $data array 插入的数据
     * @return int|false 成功：返回自动增长的编号，失败返回false。
     */
    public function insert($data){
        $keys=array_keys($data);		//获取数组的键
        $keys=array_map(function($key){	//每个key上添加反引号
                return "`{$key}`";
        },$keys);
        $fields=implode(',',$keys);			//将keys中的元素用逗号连接
        $values=array_values($data);	//获取元素的值
        $values=array_map(function($value){		//将values中所有的值用引号引起了
            // 放置XSS跨站攻击
            $value=str_replace("<script",'&lt;script',$value);
            $value=str_replace('</script>','&lt;/script&gt;',$value);
            return "'$value'";
        },$values);
        $values=implode(',',$values);	//将values中的元素用逗号连接起来
        $sql="insert into `$this->table` ($fields) values ($values)";	//拼接SQL语句
        if($this->db->exec($sql))
            return $this->db->getInsertId();
        return fasle;
    }
    //封装万能的update语句
    public function update($data){
        //获取所有键
        $keys=array_keys($data);
        $index=array_search($this->pk,$keys);	//获取主键在数组中的下标
        unset($keys[$index]);		//删除主键元素
        //拼接`键`='值'的格式
        $fields=array_map(function($key) use($data){
                return "`$key`='$data[$key]'";
        },$keys);
        $fields=implode(',',$fields);
        $sql="update `$this->table` set $fields where `$this->pk`='{$data[$this->pk]}'";
        return $this->db->exec($sql);
    }
    //封装万能的delete语句
    public function delete($id){
        $sql="delete from `{$this->table}` where `{$this->pk}`='{$id}'";
        return $this->db->exec($sql);
    }
    /*
     * 封装万能的select语句，返回二维数组
     * @param $order_field string 排序字段，默认主键排序
     * @param $order_type string asc|desc
     */
    public function select($condition=array(),$order_field='',$order_type='asc'){
        $sql="select * from `{$this->table}` where 1";
	foreach($condition as $k=>$v){
		$sql.=" and `{$k}`='$v'";
	}
        if($order_field=='')
            $order_field=$this->pk;
        $sql.=" order by `$order_field` $order_type";
	return $this->db->fetchAll($sql);
    }
    //封装万能的Find()方法，获取一条记录
    public function Find($id){
        $sql="select * from `{$this->table}` where `$this->pk`='$id'";
        return $this->db->fetchRow($sql);
    }
}