<?php
namespace app\index\controller;
use think\Db;
use think\Session;
use app\index\logic\MyLogic;

/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class Userinfo extends Base
{
 
    public function liuyan(){
      $hotline=Db::name('hotline')->order("line_id DESC")->find();

      $mobile=$hotline['mobile'];
        
 
   file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的留言信息，请注意登录后台查看&sendTime=&extno=');  
 
  
       //   //获取留言
   
       //  $content= $request->post('liu');
     
       //  $user_info=Session::get('user');
       //  $uid=$user_info['id'];
       
       //  $time=date("Y-m-d",time());
       //   $type=Db::table('che_liuyan')->insert(array('uid'=>$uid,'content'=>$content,'time'=>$time));
       //       if($type){

       //                  echo 1;

       //           }else{
       //                  echo  2;

       //           }
              

    


    }
 
        public function info(){
      
       $request=request();
  
         //获取信息
   
        $name= $request->post('name');
        $tel= $request->post('tel');
        $address=$request->post('address');
   
        $user_info=Session::get('user');
        $uid=$user_info['id'];
       
        $time=date("Y-m-d",time());
         $type=Db::table('che_user_address')->insert(array('uid'=>$uid,'tel'=>$tel,'time'=>$time,'address'=>$address,'name'=>$name));
             if($type){

                        echo 1;

                 }else{
                        echo  2;

                 }
              

    


    }
 




 
  
  
  
}
