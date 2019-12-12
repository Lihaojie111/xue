<?php
/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 15:44
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class User extends Controller
{

    /**
     * @return mixed
     * 登录页
     */
    public function login(){
        return $this->fetch();
    }
    /**
     * @return mixed
     * 登录操作
     */
    public function dologin(){
        header("Content-Type: text/html;charset=utf-8");
        $post=input('post.');
        $username=$post['username'];
        $password=md5($post['password']);
        $user=Db::name('user')->where(array("username"=>$username,"password"=>$password))->find();
        if($user){
            $user_info=Db::name('user_info')->where(array("uid"=>$user['uid']))->find();
            Session::set('user_info',$user_info);
            if($user_info['type']==1){
                $this->redirect('/index.php/Admin/index/index');
            }elseif($user_info['type']==2){
                $this->redirect('/index.php/Admin/cityagent/index');
            }elseif($user_info['type']==3){
                $this->redirect('/index.php/Admin/distagent/index');
            }elseif($user_info['type']==4){
                $this->redirect('/index.php/Admin/index/index');
            }elseif($user_info['type']==5){
                $this->redirect('/index.php/Admin/infoschool/index');
            }
      

             $this->redirect('/index.php/Admin/index/index');
        }else{
            echo "<script>alert('用户名密码不匹配，登录失败');location.href='/index.php/Admin/User/login';</script>";exit;
        }

    }
    /**
     * @return mixed
     * 退出登录
     */
    public function logout(){
        Session::clear();
        return $this->fetch("/user/login");
    }

}