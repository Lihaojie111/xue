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

class Customer extends Base
{
 public function customer_service()
{
			 $where="type=2";
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
  
  
  
  
//添加客服
  public function add_customer(){
       $post=input('post.');
        $get=input('get.');

        $get_type=$get['type'];
        if($post){
            if($post['type']==2){
                $post['super_id']="1";
            }
            $post['password']=md5($post['password']);
            $data=array(
                'username'=>$post['username'],
                'password'=>$post['password']
            );
            $uid=Db::name('user')->strict(true)->insertGetId($data);
            $data2=array(
                'uid'=>$uid,
                'name'=>$post['name'],
      			'time'=>date('Y-m-d H:i:s',time()),
                'type'=>$post['type'],
          
            
            );
            $info_id=Db::name('user_info')->strict(true)->insertGetId($data2);
            $user_info=Db::name('user_info')->where(array('info_id'=>$info_id))->find();
            if($user_info['type']==2){
                $this->redirect('/index.php/Admin/customer/customer_service');
            }else{
                $this->redirect('/index.php/Admin/collection/collection');
                }
        }
        $users=Db::name('user_info')->where(array("type"=>2))->select();
        $this->assign('city_users',$users);
        $this->assign('get_type',$get_type);
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

//修改客服密码 
    public function customer_mima(){
          $post=input('post.');
         if($post){
             $post['password']=md5($post['password']);
               $data=array(
               
                'password'=>$post['password'],
                'uid'=>$post['uid'],
               );   

              $info=Db::name('user')->update($data);
               if($info){
                $this->redirect('/index.php/Admin/customer/customer_service');
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
      
        $tel=Db::name('customer_tel')->where($where)->order("id DESC")->paginate(8);
		
      
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
  //查看电话详情
   public function tel_show(){
      	$request=request(); 
        $id=$request->param('id');
     
      	
              $describe=Db::name('customer_tel')->where(array("id"=>$id))->find();
    
      	$this->assign('describe',$describe);

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
  
  
  
  
  
  
  
  
  
  
  
  
  
  

}