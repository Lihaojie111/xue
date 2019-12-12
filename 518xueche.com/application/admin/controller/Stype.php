<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class Stype extends Base{

  //ThinkPHP的构造函数


//驾照类型
 public function stype()
 {
   $result = Db::table('che_class')->where(array("type"=>"type"))->order('id desc')->paginate(8);
   $this->assign('stype',$result);
   return $this->fetch();

 } 




//添加驾照类型
 public function add_stype(){
   $post=input('post.');
  if($post){

     $type=Db::table('che_class')->Insert($post);
   if($type){
       $this->redirect('/index.php/Admin/stype/stype');
     }

   }
 return $this->fetch();

}
 //修改
public function  edit(){
   $post=input('post.');
   if($post){

       //$state=Db::name('customer_tel')->where('id',$post['id'])->update(['describe'=>$describe,'state'=>2]);
     $info=Db::table('che_class')->where('id',$post['id'])->update($post);
     
     if($info){
       $this->redirect('/index.php/Admin/stype/stype');
     }
   }
   $request = request();
   $id=$request->param('id');
   $type=Db::table('che_class')->where(array('id'=>$id))->find();
   ;

   $this->assign('type',$type);
   return $this->fetch();
   

}

     //删除
public function  del(){
 $get=input('get.');
  if(isset($get['id'])) {
   $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
 }
 $this->redirect('/index.php/Admin/stype/stype');







}




  //班型
public function sclass()
{
  $result = Db::table('che_class')->where(array("type"=>"class"))->order('id desc')->paginate(8);
 $this->assign('stype',$result);
 return $this->fetch();

} 




//添加班型
public function add_sclass(){
  $post=input('post.');
  if($post){

    $type=Db::table('che_class')->Insert($post);
    if($type){
      $this->redirect('/index.php/Admin/stype/sclass');
    }

  }
  return $this->fetch();
}
 //修改班型
public function  cedit(){
  $post=input('post.');
  if($post){
    $info=Db::table('che_class')->update($post);
    if($info){
     $this->redirect('/index.php/Admin/stype/sclass');
   }
 }
 $request = request();
 $id=$request->param('id');
 $type=Db::table('che_class')->where(array('id'=>$id))->find();
 ;

 $this->assign('type',$type);
 return $this->fetch();

}

     //删除班型
public function  cdel(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/sclass');







}


  //驾校服务
public function  service()
{
   $result = Db::table('che_class')->where(array("type"=>"service"))->order('id desc')->paginate(8);
 $this->assign('service',$result);
 return $this->fetch();

} 

//添加驾校服务
public function add_service(){
  $post=input('post.');
  $request =request();
  $file=$request->file("spic");

  if($post){


   if($file){

                     //移动
     $info =$file->move(ROOT_PATH.'public'.DS.'uploads');

                //获取上传文件信息
     $path=$info->getSavename();

     $post['pic']='/public/uploads/'.$path;
   }


   $type=Db::table('che_class')->Insert($post);
   if($type){
    $this->redirect('/index.php/Admin/stype/service');
  }

}
return $this->fetch();
}  

   //修改服务
public function  fedit(){
  $post=input('post.');
   $request =request();
  $file=$request->file("spic");

  
  if($post){

       if($file){

                     //移动
     $info =$file->move(ROOT_PATH.'public'.DS.'uploads');

                //获取上传文件信息
     $path=$info->getSavename();

     $post['pic']='/public/uploads/'.$path;
   }
    
    $id=$post['id']; unset($post['id']);
    $info=Db::table('che_class')->where('id ='.$id)->update($post);
    if($info){
      $this->redirect('/index.php/Admin/stype/service');
    }
  }
  $request = request();
  $id=$request->param('id');
  $type=Db::table('che_class')->where(array('id'=>$id))->find();

  $this->assign('type',$type);
  return $this->fetch();

}








   //删除服务
public function  fdel(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/service');







}





  //驾校服务
public function  kemu()
{
$result = Db::table('che_class')->where(array("type"=>"kemu"))->order('id desc')->paginate(8);

 $this->assign('service',$result);
 return $this->fetch();
} 

//添加驾校服务
public function add_kemu(){
  $post=input('post.');
  $request =request();
  if($post){
   $type=Db::table('che_class')->Insert($post);
   if($type){
    $this->redirect('/index.php/Admin/stype/kemu');
  }
}
return $this->fetch();
}  

   //修改服务
public function  edit_kemu(){
  $post=input('post.');
  if($post){
  
    $info=Db::table('che_class')->where('id',$post['id'])->update($post);
    if($info){
      $this->redirect('/index.php/Admin/stype/kemu');
    }
  }
  $request = request();
  $id=$request->param('id');
  $type=Db::table('che_class')->where(array('id'=>$id))->find();
  $this->assign('type',$type);
  return $this->fetch();
}

public function  del_kemu(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/kemu');
}




public function  kaochang()
{
   $result = Db::table('che_class')->where(array("type"=>"kaochang"))->order('id desc')->paginate(8);
 $this->assign('service',$result);
 return $this->fetch();
} 

//添加驾校服务
public function add_kaochang(){
  $post=input('post.');
  $request =request();
  if($post){
   $type=Db::table('che_class')->Insert($post);
   if($type){
    $this->redirect('/index.php/Admin/stype/kaochang');
  }
}
return $this->fetch();
}  

   //修改服务
public function  edit_kaochang(){
  $post=input('post.');
  if($post){
    $info=Db::table('che_class')->where('id',$post['id'])->update($post);
    if($info){
      $this->redirect('/index.php/Admin/stype/kaochang');
    }
  }
  $request = request();
  $id=$request->param('id');
  $type=Db::table('che_class')->where(array('id'=>$id))->find();
  $this->assign('type',$type);
  return $this->fetch();
}

public function  del_kaochang(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/kaochang');
}


public function  chexing()
{
   $time = Db::table('che_class')->where(array("type"=>"chexing"))->order('id desc')->paginate(8);
 $this->assign('service',$time);
 return $this->fetch();
} 

//添加驾校服务
public function add_chexing(){
  $post=input('post.');
  $request =request();
  if($post){
   $type=Db::table('che_class')->Insert($post);
   if($type){
    $this->redirect('/index.php/Admin/stype/chexing');
  }
}
return $this->fetch();
}  

   //修改服务
public function  edit_chexing(){
  $post=input('post.');
  if($post){
    $info=Db::table('che_class')->where('id',$post['id'])->update($post);
    if($info){
      $this->redirect('/index.php/Admin/stype/chexing');
    }
  }
  $request = request();
  $id=$request->param('id');
  $type=Db::table('che_class')->where(array('id'=>$id))->find();
  $this->assign('type',$type);
  return $this->fetch();
}

public function  del_chexing(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/chexing');
}

  
 public function  time(){
 
    $time = Db::table('che_class')->where(array("type"=>"time"))->order('id desc')->paginate(8);
  $this->assign('time',$time);
   return $this->fetch();
} 
  
//添加班型时间
public function add_time(){
  $post=input('post.');
  $request =request();

  if($post){

   $type=Db::table('che_class')->Insert($post);
   if($type){
    $this->redirect('/index.php/Admin/stype/time');
  }
}
return $this->fetch();
}    
  
  //del_time

public function  del_time(){
  $get=input('get.');
  if(isset($get['id'])) {
    $goods_info = Db::table('che_class')->where(array("id" => $get['id']))->delete();
  }
  $this->redirect('/index.php/Admin/stype/time');
}

 
   //修改服务
public function  edit_time(){
  $post=input('post.');
  if($post){
    $info=Db::table('che_class')->where('id',$post['id'])->update($post);
    if($info){
      $this->redirect('/index.php/Admin/stype/time');
    }
  }
  $request = request();
  $id=$request->param('id');
  $type=Db::table('che_class')->where(array('id'=>$id))->find();
  $this->assign('type',$type);
  return $this->fetch();
}
 

 public function banxing_content(){

  $id=input('param.id');
  $post=input('post.');
  if($post){
    $bid=$post['bid']; unset($post['bid']);
  $result2=Db::table('che_banxing_content')->where('bid ='.$bid)->find();
  if($result2){  Db::table('che_banxing_content')->where('bid ='.$bid)->update($post); }else{ $post['bid']=$bid; Db::table('che_banxing_content')->insert($post);}
  $this->success('班型内容修改成功','/index.php/admin/stype/sclass');
  }
  $result=Db::table('che_banxing_content')->where('bid ='.$id)->find();
  if($result){ $content = $result['content']; $price=$result['price'];}else{ $content ='';$price='';}
  $this->assign('content',$content);
  $this->assign('price',$price);
  $this->assign('id',$id);
  return $this->fetch();

 }
  
  

}