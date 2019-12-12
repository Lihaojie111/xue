<?php
namespace app\index\controller;
//导入Controller
use think\Controller;
//导入Session
use think\Session;
class Allow extends Controller
{
    //Controller ；类初始化方法
    public function _initialize(){
    	//检测用户session信息
    	//var_dump(Session::get('user_info'));exit;
      if(!Session::get('user')){
    		//跳转到后台登录界面
    		//$this->error("请先登录","/index.php/Index/index/ulogin");
    		  $this->redirect('/index.php/Index/index/ulogin');
      }
    }

}
