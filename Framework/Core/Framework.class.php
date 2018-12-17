<?php
namespace Core;
class Framework{
    //封装run()方法
    public static function run(){
        self::initConst();
        self::initConfig();
        self::initError();
        self::initRoutes();
        self::initLoadClass();
        self::initDispatch();
    }
    //初始化路径常量
    private static function initConst(){
        define('DS', DIRECTORY_SEPARATOR);              //定义目录分隔符
        define('ROOT_PATH', getcwd().DS);              //入口文件所在的目录
        define('APP_PATH', ROOT_PATH.'Application'.DS);
        define('FRAMEWORK_PATH', ROOT_PATH.'Framework'.DS);
        define('CONFIG_PATH', APP_PATH.'Config'.DS);
        define('CONTROLLER_PATH', APP_PATH.'Controller'.DS);
        define('MODEL_PATH', APP_PATH.'Model'.DS);
        define('VIEW_PATH', APP_PATH.'View'.DS);
        define('CORE_PATH', FRAMEWORK_PATH.'Core'.DS);
        define('LIB_PATH', FRAMEWORK_PATH.'Lib'.DS);
    }
    //导入配置
    private static function initConfig(){
        $GLOBALS['config']=require CONFIG_PATH.'config.php';
    }


    /*
     * 确定路由
     * p:平台   c：控制器   a:方法
     */
    private static function initRoutes(){
        $p=$_GET['p']??$GLOBALS['config']['app']['default_platform'];                 //获取平台（分组）
        $c=$_GET['c']??$GLOBALS['config']['app']['default_controller'];		//获取控制器
        $a=$_GET['a']??$GLOBALS['config']['app']['default_action'];			//获取方法
        $p=ucfirst(strtolower($p));
        $c=ucfirst(strtolower($c));		//首字母大写
        $a=strtolower($a);
        define('PLATFORM_NAME', $p);
        define('CONTROLLER_NAME', $c);
        define('ACTION_NAME', $a);
        define('__URL__', CONTROLLER_PATH.$p.DS);      //当前请求的 控制器的路径
        define('__VIEW__', VIEW_PATH.$p.DS);        //当前视图的路径
        define('__VIEWC__', APP_PATH.'Viewc'.DS.$p.DS);  //定义混编目录地址
    }

    //自动加载类
    private static function initLoadClass(){
        spl_autoload_register(function($class_name){
           $namespace= dirname($class_name);        //获取命名空间
           $class_name=basename($class_name);       //获取类名
            //由于Smarty类保存地址不规则，将Smarty类名和地址做一个映射
            $map=array(
                'Smarty'    =>  CORE_PATH.'Smarty'.DS.'Smarty.class.php'
            );
           if(isset($map[$class_name]))
                $path=$map[$class_name];
           elseif(in_array($namespace, array('Core','Lib'))){
               $path=FRAMEWORK_PATH.$namespace.DS.$class_name.'.class.php';
           }elseif($namespace=='Model'){    //加载模型
               $path=MODEL_PATH.$class_name.'.class.php';
           }else{       //加载控制器
               $path=__URL__.$class_name.'.class.php';
           }
           if(file_exists($path))   //加载缺少的类名
                require $path;
        });
    }
    //请求分发
    private static function initDispatch(){
        $controller_name='Controller\\'.PLATFORM_NAME.'\\'.CONTROLLER_NAME.'Controller';	//拼接类名
        $action_name=ACTION_NAME.'Action';	//拼接方法名

        $obj=new $controller_name();
        $obj->$action_name();
    }
    // 错误处理
    private static function initError(){
        ini_set('error_reporting',E_ALL);
        if($GLOBALS['config']['app']['debug']){  //开发模式
            ini_set('display_errors','On');  //错误显示在浏览器上
            ini_set('log_errors','Off');     //关闭日志
        }else{
            ini_set('display_errors','Off');
            ini_set('log_errors','On');
            $logname=date('Y-m-d').'.log';
            ini_set('error_log','F:\wamp\log\\'.$logname);
        }
    }

}
