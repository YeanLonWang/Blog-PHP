<?php
namespace Model;
class UserModel extends \Core\Model{
    /*
     * 判断登录是否成功
     * @return array|false 如果登录成功，返回用户的信息，否则返回false
     */
    public function isLogin(){
        $data['user_name']= addslashes($_POST['username']); //给特殊字符添加转义
        $data['user_pwd']=md5($_POST['password']);
        if($info= $this->select($data)){
            $info=$info[0];
            unset($info['user_pwd']);
            return $info;
        }
        return false;
    }
    //更新用户登录信息
    public function updateLoginInfo(){
        $data['last_login_ip']= ip2long($_SERVER['REMOTE_ADDR']);
        $data['last_login_time']=time();
        $data['login_count']=++$_SESSION['user']['login_count'];
        $data['user_id']=$_SESSION['user']['user_id'];
        return $this->update($data);
    }
}


// namespace Model;
// class UserModel extends \Core\Model{
// 	/*
// 	 *判断登录是否成功
// 	 *$param array|false 如果登陆成功,返回用户的信息,否则返回false
// 	 */
// 	public  function isLogin(){
// 		$data['user_name']=addslashes($_POST['username']);
// 		$data['user_pwd']=md5($_POST['password']);
// 		if($info = $this->select($data)){
// 			$info=$info[0];
// 			unset($info['user_pwd']);
// 			return $info;
// 		}
// 		return false;
// 	}
// 	// 更新用户登录信息
// 	 public function updateLoginInfo(){
//         $data['last_login_ip']= ip2long($_SERVER['REMOTE_ADDR']);
//         $data['last_login_time']=time();
//         $data['login_count']=++$_SESSION['user']['login_count'];
//         $data['user_id']=$_SESSION['user']['user_id'];
//         return $this->update($data);
//     }
// }
