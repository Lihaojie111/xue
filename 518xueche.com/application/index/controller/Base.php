<?php
/**
 * Created by PhpStorm.
 * Base全局控制器，非User控制器继承该控制器，以完成相关验证操作
 * User: Biao
 * Date: 2018/8/22
 * Time: 15:22
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Base extends Controller
{
    public function _initialize() {


      $user_info=Session::get('user');
      if($user_info){ 
      	$result=Db::table('che_userlogin')->where('id ='.$user_info['id'])->find();
      	if($result['is_keyong'] != 1){  
      		session::clear();
            Cookie::clear();
        }
      }
//        //验证登录
//        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
//        if($user_info){
//            //加载通知
//            $notice=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->select();
//            $notice_count=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->count();
//            //传输数据
//            $this->assign('notice',$notice);
//            $this->assign('notice_count',$notice_count);
//            $this->assign('user_info',$user_info);
//
//        }else{
//            $this->redirect('/index.php/Index/User/login');
//        }

    }

}