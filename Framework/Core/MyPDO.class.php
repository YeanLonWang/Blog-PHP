<?php
namespace Core;
class MyPDO{
    //$dsn='mysql:host=127.0.0.1;port=3306;dbname=data;charset=utf8';
    private $type;          //数据库类别
    private $host;          //主机地址
    private $port;          //端口号
    private $dbname;        //数据库名称
    private $charset;       //字符编码
    private $user;          //用户名
    private $pwd;           //密码
    private $pdo;           //PDO对象
    private static $instance;
    private function __construct($param) {
        $this->initParam($param);
        $this->initPDO();
        $this->initException();
    }
    private function __clone() {
    }
    public static function getInstance($param=array()){
        if(!isset(self::$instance))
            self::$instance=new self($param);
        return self::$instance;
    }
    //初始化参数
    private function initParam($param){
        $this->type=$param['type']??'mysql';
        $this->host=$param['host']??'127.0.0.1';
        $this->port=$param['port']??'3306';
        $this->dbname=$param['dbname']??'data';
        $this->charset=$param['charset']??'utf8';
        $this->user=$param['user']??'root';
        $this->pwd=$param['pwd']??'root';
    }
    //初始化PDO
    private function initPDO(){
        try{
            $dsn="{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            $this->pdo=new \PDO($dsn, $this->user, $this->pwd);
        }catch(\Exception $e){
            $this->showException($e);
        }
    }
    //初始化异常模式
    private function initException(){
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }
    //封装显示异常的方法
    private function showException($e,$sql=''){
        if($sql!='')
            echo 'SQL语句执行失败。<br>错误的SQL语句是:',$sql,'<br>';
        echo '错误编号：',$e->getCode(),'<br>';
        echo '错误信息：',$e->getMessage(),'<br>';
        echo '错误文件：',$e->getFile(),'<br>';
        echo '错误行号：',$e->getLine(),'<br>';
        exit;
    }

    /*
     * 执行数据操作语句
     * @param $sql string 执行的SQL语句
     * @return int 受影响的记录数
     */
    public function exec($sql){
        try{
            return $this->pdo->exec($sql);
        }catch(\Exception $e){
             $this->showException($e,$sql);
        }
    }
    //获取自动增长的编号
    public function getInsertId(){
        return $this->pdo->lastInsertId();
    }
    //获取查询语句的PDOStatement对象
    private function getPDOStatement($sql){
        try{
            return $this->pdo->query($sql);
        } catch (\Exception $ex) {
            $this->showException($e, $sql);
        }
    }
    //封装获取匹配类型
    private function getFetchType($type){
        switch($type){
            case 'num':
                return \PDO::FETCH_NUM;
            case 'both':
                return \PDO::FETCH_BOTH;
              default:
               return \PDO::FETCH_ASSOC;
        }
    }
    /*
     * 匹配所有记录
     * @param $type string assoc|num|both
     * @return array 二维数组
     */
    public function fetchAll($sql,$type='assoc'){
        $stmt= $this->getPDOStatement($sql);
        $type= $this->getFetchType($type);
        return $stmt->fetchAll($type);
    }
    //匹配一行
    public function fetchRow($sql,$type='assoc'){
        $stmt= $this->getPDOStatement($sql);
        $type= $this->getFetchType($type);
        return $stmt->fetch($type);
    }
    /*
     * 匹配一行一列
     * @param $n int 列的索引
     * @return string 当前行的第n列的值
     */
    public function fetchColumn($sql,$n=0){
        $stmt= $this->getPDOStatement($sql);
        return $stmt->fetchColumn($n);
    }
}

