<?php
namespace Here\Controller;
use Think\Controller;
class UserController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录
     * @param  string $username 用户名
     * @param  string $password 密码
     */
    public function login($username = '', $password = ''){
        if(!isset($_POST['username']) && !isset($_SESSION['username'])){
            $this->display('login');
            die();
        }

        if($_SESSION['username']){
            exit('您已登录，用户名：'.$_SESSION['username']);
        }

        $username = trim($username);
        $password = trim($password);

        if($username == '' || $password == ''){
            exit('用户名或者密码不能为空！');
        }
        if(!preg_match('/[\d_\w]{5,10}/', $username)){
            exit('用户名不符合规则');
        }

        $user = M("User")->where('username="'.$username.'"')->find();

        if($user == NULL){
            exit('不存在此用户');
        }

        $check = M("User")->where('username="'.$username.'" AND password="'.md5($password).'"')->find();
        if($check == NULL){
            exit('密码错误');
        }

        $_SESSION['userid'] = $check['id'];
        $_SESSION['username'] = $username;
        echo '登录成功，用户名：'.$_SESSION['username'];

    }

    public function logout(){
        echo '退出登录';
        unset($_SESSION['username']);
    }

    /**
     * 注册
     * @param  string $username 用户名
     * @param  string $password 密码
     */
    public function register($username = '', $password = '', $nickname = ''){

        if(!isset($_POST['username'])){
            $this->display('register');
            die();
        }

        $username = trim($username);
        $password = trim($password);
        $nickname = htmlentities(trim($nickname));

        if($username == '' || $password == ''){
            exit('用户名或者密码不能为空！');
        }
        if(!preg_match('/[\d_\w]{5,10}/', $username) || !preg_match('/[\d_\w]{5,10}/', $password)){
            exit('用户名或密码不符合规则');
        }

        $user = M("User")->where('username="'.$username.'"')->find();

        if($user !== NULL){
            exit('此用户已存在');
        }

        M('User')->data(array('username' => $username,
                              'password' => md5($password),
                              'nickname' => $nickname))->add();

        exit('注册成功');
    }

}