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

class Collection extends Base
{
 public function collection()
{
    	$where="type=3";
        $get=input('get.');
	  if(isset($get['name'])){
            $name=$get['name'];
            //模糊匹配商品的名称、规格、型号、品牌
            $where=$where." and  name like '%$name%' ";
        }
   

        $user_list=Db::name('user_info')->where($where)->paginate(8);
        $this->assign('customer',$user_list);
        return $this->fetch();

} 
  
  
  
  //查看采集信息
    public function collection_list(){
      	$request=request(); 
        $uid=$request->param('uid');
        $iid=$request->param('iid');
      	$where="bid=$uid";
     	$school=Db::name('school')->where($where)->paginate(8);
      	$this->assign('uid',$uid);
      	$this->assign('iid',$uid);
	 	$this->assign('school',$school);
        return $this->fetch();

    }
  
  
  
  

    //注册认证
       public function uname(){
           header('Content-type: text/json;charset=utf-8'); 
        $request=request();
        $name=$request->get('name');
    
       //获取数据库用户名
        $arr=Db::name("user")->column('username');  
        //判断是否在数组里
        if(in_array($name,$arr)){
        echo 1;

        }else{
            echo 0;
        }
            
    }

//修改Db密码 
    public function collection_mima(){
          $post=input('post.');
         if($post){
             $post['password']=md5($post['password']);
               $data=array(
               
                'password'=>$post['password'],
                'uid'=>$post['uid'],
               );   

              $info=Db::name('user')->update($data);
               if($info){
                $this->redirect('/index.php/Admin/collection/collection');
               }
        }
            $request = request();
            $iid=$request->param('iid');
            $uid=$request->param('uid');
            $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
            $this->assign('user',$user);
            $this->assign('info',$info);
        return $this->fetch();
    }
  
  
  
     /**
     * 电话详情
     */
    public function tel_list(){
      	$request=request(); 
      	$get=input('get.');
        $uid=$request->param('uid');
        $user_info=Db::name('user_info')->where(array("uid"=>$uid))->find();
        $where="cid = $uid";
        $get['empty']= isset($get['empty'])?$get['empty']:"N";
        if(isset($get['name'])){
            $name=$get['name'];
            //模糊匹配商品的名称、规格、型号、品牌
            $where=$where." and tel  like '%$name%' OR cname like '%$name%'";
        }
        $tel=Db::name('customer_tel')->where($where)->order("id DESC")->paginate(8);
		
      	$this->assign('request',$request->param());
      	$this->assign('uid',$uid);
      	$this->assign('tel',$tel);
        $this->assign('name',$user_info);
        return $this->fetch();

    }
  
  
  //指定电话 
    public function appoint_tel(){
      	$request=request(); 
        $uid=$request->param('uid');
     
      	   $post=input('post.');
         if($post){
            
              $info=Db::name('customer_tel')->insert($post);
               if($info){
                $this->redirect('/index.php/Admin/customer/customer_service');
               }
        }

     
      	$this->assign('request',$request->param());
      	$this->assign('uid',$uid);


        return $this->fetch();

    }
  
  
  
  
  
  
  
  
  
 //修改
      public function  edit(){
        $post=input('post.');
         $request =request();
         $file=$request->file("spic");
     
        if($post){
             $t=$post['type'];
        
          $ty=implode(',',$t);
      
          $typ=','.$ty.',';     
          $post['type']=$typ;
             
                if($file){
          
                     //移动
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        
                //获取上传文件信息
                $path=$info->getSavename();
                 
                 $post['spic']='/public/uploads/'.$path;
            }
          
           
              $info=Db::table('che_school')->update($post);
               if($info){
                $this->redirect('/index.php/Admin/school/indexs');
               }
        }
           
        
            
        
            $request = request();
            $id=$request->param('id');
            
        $school=Db::table('che_school')->where(array('id'=>$id))->find();
         $class=Db::table('che_shoolclass')->select();
         $type=Db::table('che_shooltype')->select();
          foreach ($type as $key => $value) {
           $ishave = strpos($school['type'],$value['type']);
           if($ishave){
             $ishave="Y";
           }else{
             $ishave="N";
           }
           $type[$key]['ishave']=$ishave;
         }

          $this->assign('type',$type);
          $this->assign('class',$class);
         $this->assign('type',$type);

            $this->assign('school',$school);
            return $this->fetch();

    }

     //删除
      public function  del(){
          $get=input('get.');
        if(isset($get['id'])) {
            $goods_info = Db::table('che_school')->where(array("id" => $get['id']))->delete();
        }
         $this->redirect('/index.php/Admin/school/indexs');
        
        
        
   
        
 

    }

  //删除BD
  
   public function del_customer(){
        $get=input('get.');
       
        if(isset($get['uid'])) {
        
           
            $user = Db::name('user')->where(array("uid" => $get['uid']))->delete();
            $user_info = Db::name('user_info')->where(array("uid" => $get['uid']))->delete();

        
        }
		  if(isset($get['type'])) {
            if($get['type']==2){
                $this->redirect('/index.php/Admin/customer/customer_service');
            }else{
                $this->redirect('/index.php/Admin/collection/collection');
            }
        }
      
        
     }
  
  
  
  
  //审核
     public function  shenghe(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('school')->where('id',$id)->update(['state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/collection/collection');
            }
    

        

    }
   //教练审核
     public function  jiao_shenghe(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('coach')->where('cid',$id)->update(['state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/collection/collection');
            }     
  
  
  
     }
  
  
  
  
  //教练
     public function  jiaolian(){
    	$request=request(); 
        $uid=$request->param('uid');
      	$where="bid=$uid";
    
      	$this->assign('uid',$uid);
	 	
        
        $result=Db::table('che_coach')->where($where)->order('cid desc')->paginate(10)->each(function($item, $key){
        $re=Db::table('che_school')->where('id ='.$item['school'])->find();
        $item['jx_name']=$re['name'];
        return $item;
        });
        $this->assign('school',$result);
        $this->assign('uid',$uid);
       	return $this->fetch();

       
       return $this->fetch();
       
    
       
        

    }
  
  //BD修改教练审核 jiao_xiugai

     public function  jiao_xiugai(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('coach')->where('cid',$id)->update(['xiugai'=>0,'state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/collection/collection');
            }     
  
  
  
     }
  
  //删除驾校
    public function  s_del(){
        
      	$request=request();
       $id=$request->param('id');
      $uid=$request->param('uid');
      $iid=$request->param('iid');
     
		$goods_info = Db::table('che_school')->where(array("id" =>$id))->delete();
      
 		if( $goods_info ){
         	 $this->redirect("/index.php/Admin/collection/collection_list/iid/$iid/uid/$uid");

        }
        
	

    }
  //删除教练
    public function  c_del(){
        
      	$request=request();
       $cid=$request->param('id');
      $uid=$request->param('uid');
		$goods_info = Db::table('che_coach')->where(array("cid" =>$cid))->delete();

 		if( $goods_info ){
         	 $this->redirect("/index.php/Admin/collection/jiaolian/uid/$uid");

        }
        
	

    }
  
  
  
  
    //BD修改教驾校审核 jiao_xiugai

     public function  xue_xiugai(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('school')->where('id',$id)->update(['xiugai'=>0,'state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/collection/collection');
            }     
  
  
  
     }
  
  
  

}