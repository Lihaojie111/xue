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

class Insure extends Base
{

    /**
     * @return mixed
     * 投保
     */
    public function insure(){
      $id=input('param.id');
      $state=input('param.state');
      if($id)
      {
        $where['i.id']=$id;
      }else{
        $where='';
      }
      if($state){
         $where='o.pay_state=1 and i.state = 0';
      }
     $insure = Db::name('insure i')->where($where)
    ->field("i.*,o.pay_state,o.price,o.sf_price")
    ->join('__ORDER__ o', 'i.id = o.content', 'LEFT')
    ->order('i.id desc')
    ->paginate(8);
      $this->assign('bespeak',$insure);
      return $this->fetch();
    }
    /**
     * @return mixed
     * 审核操作
     */
    public function make(){
      $request=request();
      $state=$request->param('state');

      $id=$request->param("id");
      $insure=Db::name('insure')->where("id",$id)->find();
        $mobile=$insure['phone'];
		if($state=='1'){ 
           $state=Db::name('insure')->where('id',$id)->update(['state'=>1]);
        		if($state){
       			file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的用户您好，您的投保审核通过&sendTime=&extno=');
       		 }	
        }else{
        
          $state=Db::name('insure')->where('id',$id)->update(['state'=>2]);
          if($state){
        	 	file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的用户您好，您的投保审核未通过,请重新提交审核&sendTime=&extno=');
       		 }	
        }	
      
     
 	 $this->success('操作成功');

    }
  
 //删除 insure_del
 public function insure_del(){
   	 $request=request();
   	$id=$request->param("id");
     $state = Db::name('insure')->where(array("id" => $id))->delete();
 	if($state){
    	  $this->redirect('/index.php/Admin/Insure/insure');
    }   
 }
  
  //在线理赔
    public function lipei(){
        $insure=Db::name('lipei')->order('id desc')->paginate(8);
      
		$this->assign('bespeak',$insure);
     	 return $this->fetch();
    }
   public function make_lipei(){
      $request=request();
      $state=$request->param('state');
		$id=$request->param("id");
      	$insure=Db::name('lipei')->where("id",$id)->find();
        $mobile=$insure['phone'];
		if($state=='1'){ 
           $state=Db::name('lipei')->where('id',$id)->update(['state'=>1]);
        		if($state){
       			file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的用户您好，您的在线理赔审核通过&sendTime=&extno=');
       		 }	
        }else{
        
          $state=Db::name('lipei')->where('id',$id)->update(['state'=>2]);
          if($state){
        	 	file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的用户您好，您的在线理赔审核未通过,请重新提交审核&sendTime=&extno=');
       		 }	
        }	
      
     
 	 $this->redirect('/index.php/Admin/Insure/lipei');

    } 
   //删除 理赔
 public function lipei_del(){
   	 $request=request();
   	$id=$request->param("id");
     $state = Db::name('lipei')->where(array("id" => $id))->delete();
 	if($state){
    	  $this->redirect('/index.php/Admin/Insure/lipei');
    }   
 }
  
}