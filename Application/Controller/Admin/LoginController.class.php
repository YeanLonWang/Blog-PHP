<?php
// 登陆控制器
namespace Controller\Admin;
class LoginController extends BaseController{
	// 登录
	public function loginAction(){
		// 登录业务逻辑
		if(!empty($_POST)){
			// 验证输入的验证码是否正确
			// echo 1;die();
			$captcha=new \Lib\Captcha();
			// var_dump($_POST['passcode']);die;
			if(!$captcha->checkCode($_POST['passcode'])){
				$this->error('index.php?p=Admin&c=Login&a=login','验证码输入错误');
			}
			$model=new \Model\UserModel();
			if($info=$model->isLogin()){
				// var_dump($_SESSION);die();
				$_SESSION['user']=$info;  //将用户信息保存到会话中
				$model->updateLoginInfo();  //更新登录信息
				// $this->smarty->display('02demo.html');
				// die();
				//记住用户名和密码开始
				if(isset($_POST['remember'])){
					$time=time()+3600*24*7;
					setcookie('username',$_POST['username'],$time);
					setcookie('pwd',$_POST['password'],$time);
				}
				//记住用户名和密码结束
				$this->success('index.php?p=Admin&c=Admin&a=admin','登陆成功');
				die();
			}else{
				$this->error('index.php?p=Admin&c=Login&a=login','登录失败,请重新登录');
			}
		}
		$username=$_COOKIE['username']??'';
		$pwd=$_COOKIE['pwd']??'';

		$this->smarty->assign('username',$username);
		$this->smarty->assign('pwd',$pwd);
		$this->smarty->display('login.html');
	}
	// 注册
	public function registerAction(){
		// 实现用户注册,
		// 	获取提交的post用户名,用户密码,
		// 	首先判断用户名是否已存在
		// 		存在则返回注册页,提醒用户名已存在
		// 		不存在则写入数据库
		// 		$captcha=new \Lib\Captcha();
			// var_dump($_POST['passcode']);die;
		$captcha=new \Lib\Captcha();
		// if(!$captcha->checkCode($_POST['passcode'])){
		// 	$this->error('index.php?p=Admin&c=Login&a=login','验证码输入错误');
		// }
		if(!empty($_POST)){
			$data['user_name']=$_POST['username'];
			$data['user_pwd']=md5($_POST['password']);
			// 判断用户名是否已存在
			$model=new \Core\Model('user');
			if($model->select(array('user_name'=>$data['user_name'])))
				$this->error('index.php?p=Admin&c=Login&a=register','此用户名已存在,请重新选择');
			// 上传图片
			$path=$GLOBALS['config']['app']['upload_path'];
	        $size=$GLOBALS['config']['app']['upload_size'];
	        $type=$GLOBALS['config']['app']['upload_type'];
	        $upload=new \Lib\Upload($path,$size,$type);
	        if($path=$upload->uploadOne($_FILES['face'])){
	        	$data['user_face']=$path;
	        }else{
	        	$this->error('index.php?p=Admin&c=Login&a=register',$upload->getError());
	        }
			// 将用户信息写进数据库
			if($model->insert($data)){
				$this->success('index.php?p=Admin&c=Login&a=login','注册成功,你可以去登录了');
			}else{
				$this->error('index.php?p=Admin&c=Login&a=register','注册失败,请重新注册');
			}
		}
		$this->smarty->display('register.html');
	}
	// 调用验证码
	public function createCaptchaAction(){
        $captcha=new \Lib\Captcha();
        $captcha->generalCaptcha();
    }
    // 安全退出
    public function logoutAction(){
    	session_destroy();
    	header("location:index.php?p=Admin&c=Login&a=login");
    }

}
