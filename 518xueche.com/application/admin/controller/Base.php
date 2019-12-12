<?php
/**
 * Created by PhpStorm.
 * Base全局控制器，非User控制器继承该控制器，以完成相关验证操作
 * User: Biao
 * Date: 2018/8/22
 * Time: 15:22
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;

class Base extends Controller
{
    public function _initialize() {
        $user_info=Session::get('user_info');
        //验证登录
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
    
      if($user_info){



            if($user_info['uid'] == 1)
            { 
            $this->assign('jiaose',1);
            }else{
              $this->assign('jiaose',2);
            }
            
          
          //加载通知
            //加载通知
            $notice=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->select();
            $notice_count=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->count();
            //传输数据
            $this->assign('notice',$notice);
            $this->assign('notice_count',$notice_count);
            //传输数据
            $this->assign('user_info',$user_info);

        }else{
            $this->redirect('/index.php/Admin/User/login');
        }

    }

}