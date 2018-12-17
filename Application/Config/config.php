<?php
return array(
    //数据库配置
    'database'    =>  array(
        'user'  =>  'root',
        'pwd'	=>	'admin',
        'dbname'=>	'php17'
    ),
    //应用程序配置
    'app'         =>  array(
        'debug'             =>  true,   //true表示开发模式
        'upload_path'       =>  './public/Uploads/',
        'upload_size'       =>  223344,
        'upload_type'       =>  array('image/png','image/gif','image/jpeg','image/jpg'),
        'default_platform'  =>  'Home',
        'default_controller'=>  'Index',
        'default_action'    =>  'index',
        'pagesize'          =>   2
    )
);

