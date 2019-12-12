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
class Reset extends Allow
{
 public function  reset(){
     
      //用户注册
        $post=input('post.');

        $id=input('param.id');
        if($post){
         $result=Db::table('che_smslog')->order('id desc')->where(array('mobile'=>$post['phone'],'status'=>"0"))->find();

        if(!$result){ return '<script>alert("请先获取验证码"); window.history.go(-1); </script>'; exit;}
        if((time() -$result['time']) >= 120){  return '<script>alert("验证码已失效，请重新获取！"); window.history.go(-1); </script>';   exit;}
        if($result['code'] != $post['code']){ return '<script>alert("验证码输入错误！"); window.history.go(-1); </script>';   exit;}

		 $name=$post['name'];
          $phone=$post['phone'];
        $password=md5($post['password']);
          
        $user=Db::name('userlogin')->where(array("name"=>$name,"dianhua"=>$phone))->find();
         if($user){
         	$uid=$user['id'];
         
           $reset=Db::name('userlogin')->where('id',$uid)->update(['password'=>$password]);
         		if($reset){
               	 echo "<script>alert('密码修改成功');location.href='/index.php/index/index/ulogin';</script>";exit;
                
                }else{
                 echo "<script>alert('用户名或手机号有误');location.href='/index.php/Index/Reset/reset';</script>";exit;
                }
         
         }else{
         
         echo "<script>alert('用户名或手机号有误');location.href='/index.php/Index/Reset/reset';</script>";exit;
         
         }

    
   

  }
      
      
      return $this->fetch();
    } 
   


}
