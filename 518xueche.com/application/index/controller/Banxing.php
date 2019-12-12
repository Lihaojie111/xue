<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;
class Banxing extends Controller
{

  public function banxing()
  {
    $request=request();
    $hotline=Db::name('hotline')->order("line_id DESC")->find();
    $mobile=$hotline['mobile'];
    $type= $request->post('type');
    $banxing= $request->post('content');
    $price= $request->post('price');
    $user_info=Session::get('user');
    if(!Session::get('user')){
       $arr=array('code'=>2,'msg' => '/index.php/index/index/ulogin.html'); return json_encode($arr); exit;
    }

    $uid=$user_info['id'];
    $mobile=$user_info['dianhua'];
    $uname=$user_info['name'];
    $time=date("Y-m-d",time());
    $type=Db::table('che_banxing_mack')->insert(array('uid'=>$uid,'banxing'=>$banxing,'time'=>$time,'type'=>$type,'price'=>$price,'mobile'=>$mobile,'uname'=>$uname));
    if($type){
       $hotline=Db::name('hotline')->order("line_id DESC")->find();
      $mobile=$hotline['mobile'];
        file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的班型预约信息，请注意登录后台查看&sendTime=&extno=');  
      $arr=array('code'=>1,'msg' => '班型预约成功，请耐心等待工作人员的联系。'); return json_encode($arr); exit;
    }else{
      $arr=array('code'=>1,'msg' => '班型预约失败，请联系工作人员解决。'); return json_encode($arr); exit;

    }


  }


}
