<?php
//基础控制器类
namespace Core;
abstract class Controller{
    protected $smarty;
    public function __construct() {
        $this->initSession();  //开启会话
        $this->initSmarty();
    }
    private function initSession(){
        new \Lib\Session();
    }
    private function initSmarty(){
        $this->smarty=new \Smarty();
        $this->smarty->setTemplateDir(__VIEW__);  //设置模板目录
        $this->smarty->setCompileDir(__VIEWC__);  //设置混编目录;
    }
    //封装成功的跳转
    public function success($url,$info='',$time=1){
        $this->jump($url, $info, $time, 'success');
    }
    //封装失败的跳转
    public function error($url,$info='',$time=3){
        $this->jump($url, $info, $time, 'error');
    }

    /*
     * 跳转的方法
     * @param $url string 跳转的地址
     * @param $info string 显示的信息
     * @param $time int 停留的时间
     * @param $flag string 成功|失败   success |error
     */
    private function jump($url,$info='',$time=3,$flag='success'){
        if($info=='')
            header("location:{$url}");
        else{
            echo <<<str
            <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="{$time};{$url}"/>
<title>无标题文档</title>
<style type="text/css">
body{
	text-align:center;
}
#msg{
	font-size:36px;
	margin:20px 0px;
}
.success{
	color:#090;
}
.error{
	color:#F00;
}
</style>
</head>

<body>
<img src="/09-25phpblogd2/products1/Public/images/{$flag}.fw.png" width="83" height="82">

<div id="msg" class="{$flag}">{$info}</div>

<div>{$time}秒以后跳转</div>

</body>
</html>
str;
     exit;
        }
    }
}
