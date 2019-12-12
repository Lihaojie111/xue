<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class School extends Base{
//驾照类型
 public function indexs()
{
       $request=request();
       $state=$request->param('state');
        if($state){
            $where="state=1";
        }
 $result=Db::table('che_school')->where(@$where)->order('id desc')->paginate(8);
 
   
 $this->assign('stype',$result);
 return $this->fetch();

} 
  
//教练审核
     public function  shenghe(){
        $request=request();
       $id=$request->param('id');
            
            $state=Db::name('school')->where('id',$id)->update(['state'=>0]);
            if($state){
                  $this->redirect('/index.php/Admin/school/indexs');
            }     
  
  
  
     }
    
//添加驾照类型
  public function add_school(){
        $post=input('post.');
        $request =request();
        $file=$request->file("img");
        $file2=$request->file("img2");
        $file3=$request->file("img3");
        $file4=$request->file("img4");
        $file5=$request->file("img5");
        $file6=$request->file("img6");
        $file7=$request->file("img7");
        $file8=$request->file("img8");
        if($post){

         if($file){
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
             $path=$info->getSavename();$txt='/public/uploads/'.$path;
        }
                 if($file2){
             $info2 =$file2->move(ROOT_PATH.'public'.DS.'uploads');
             $path2=$info2->getSavename();$txt2='/public/uploads/'.$path2;
        }else{
                 	$txt2='';
                 }
                 if($file3){
             $info3 =$file3->move(ROOT_PATH.'public'.DS.'uploads');
             $path3=$info3->getSavename();$txt3='/public/uploads/'.$path3;
        }else{
                   	$txt3='';
                 }
                 if($file4){
             $info4 =$file4->move(ROOT_PATH.'public'.DS.'uploads');
             $path4=$info4->getSavename();$txt4='/public/uploads/'.$path4;
        }else{
                 	$txt4='';
                 }
                 if($file5){
             $info5 =$file5->move(ROOT_PATH.'public'.DS.'uploads');
             $path5=$info5->getSavename();$txt5='/public/uploads/'.$path5;
        }else{
                   	$txt5='';
                 }
                 if($file6){
             $info6 =$file6->move(ROOT_PATH.'public'.DS.'uploads');
             $path6=$info6->getSavename();$txt6='/public/uploads/'.$path6;
        }else{	
                   	$txt6='';
                 }
                 if($file7){
             $info7 =$file7->move(ROOT_PATH.'public'.DS.'uploads');
             $path7=$info7->getSavename();$txt7='/public/uploads/'.$path7;
        }else{
                   	$txt7='';
                 }
                 if($file8){
             $info8 =$file8->move(ROOT_PATH.'public'.DS.'uploads');
             $path8=$info8->getSavename();$txt8='/public/uploads/'.$path8;
        }else{
                 	$txt8='';
                 }
            
            $post['img']=$txt2.','.$txt3.','.$txt4.','.$txt5.','.$txt6.','.$txt7.','.$txt8;
            $post['spic']=$txt;
            $post['address']='暂无';
            $post['price']='暂无';
            $type=Db::table('che_school')->Insert($post);
            if($type){
                $this->redirect('/index.php/Admin/school/indexs');
            }
        
        }
    

   
         $type=Db::table('che_shooltype')->select();
          $this->assign('type',$type);
       
    
    
        return $this->fetch();
    }
 
 //修改
    //   public function  edit(){
    //     $post=input('post.');
    //     $request =request();
    //      $file=$request->file("spic");
     
    //     if($post){
           
    //         var_dump($post);exit;
             
    //             if($file){
          
    //                  //移动
    //          $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        
    //             //获取上传文件信息
    //             $path=$info->getSavename();
                 
    //              $post['spic']='/public/uploads/'.$path;
    //         }
          
           
    //           $info=Db::table('che_school')->update($post);
    //            if($info){
    //             $this->redirect('/index.php/Admin/school/indexs');
    //            }
    //     }
           
        
            
        
    //     $request = request();
    //     $id=$request->param('id');
            
    //     $school=Db::table('che_school')->where(array('id'=>$id))->find();

    //     $this->assign('school',$school);
    //      return $this->fetch();

    // }



    //  public function  edit(){
    //     $post=input('post.');
    //      $request =request();

    //     $file=$request->file("img");
    //     $file2=$request->file("img2");
    //     $file3=$request->file("img3");
    //     $file4=$request->file("img4");
    //     $file5=$request->file("img5");
    //     $file6=$request->file("img6");
    //     $file7=$request->file("img7");
    //     $file8=$request->file("img8");
     
    //     if($post){
    //     $gxid=$post['id'];
    //     unset($post['id']);

    //     if($file){
    //     $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path=$info->getSavename();
    //     $txt='/public/uploads/'.$path;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update(['spic' => $txt]);
    //     }
    //     if($file2){
    //     $info2 =$file2->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path2=$info2->getSavename();$txt2='/public/uploads/'.$path2;
    //     $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[0]=$txt2;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file3){
    //     $info3 =$file3->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path3=$info3->getSavename();$txt3='/public/uploads/'.$path3;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[1]=$txt3;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file4){
    //     $info4 =$file4->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path4=$info4->getSavename();$txt4='/public/uploads/'.$path4;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[2]=$txt4;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file5){
    //     $info5 =$file5->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path5=$info5->getSavename();$txt5='/public/uploads/'.$path5;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[3]=$txt5;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file6){
    //     $info6 =$file6->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path6=$info6->getSavename();$txt6='/public/uploads/'.$path6;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[4]=$txt6;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file7){
    //     $info7 =$file7->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path7=$info7->getSavename();$txt7='/public/uploads/'.$path7;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[5]=$txt7;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     if($file8){
    //     $info8 =$file8->move(ROOT_PATH.'public'.DS.'uploads');
    //     $path8=$info8->getSavename();$txt8='/public/uploads/'.$path8;
    //      $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
    //     $imgz=explode(',',$result['img']);
    //     $imgz[6]=$txt8;
    //     $imgz=implode(',',$imgz);
    //     $post['img']=$imgz;
    //     $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
    //     }
    //     $this->success('修改成功');

    //     }
           
        
            
        
    //         $request = request();
    //    $id=$request->param('id');
            
     
    //  $school=Db::table('che_school')->where(array('id'=>$id))->find();

        
        
    //       $this->assign('school',$school);
       
    
    
    //     return $this->fetch();
       
     

    // }

 public function  edit(){
        $post=input('post.');
         $request =request();

        $file=$request->file("img");
        $file2=$request->file("img2");
        $file3=$request->file("img3");
        $file4=$request->file("img4");
        $file5=$request->file("img5");
        $file6=$request->file("img6");
        $file7=$request->file("img7");
        $file8=$request->file("img8");
     
        if($post){
        $gxid=$post['id'];
        unset($post['id']);

        if($file){
        $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        $path=$info->getSavename();
        $txt='/public/uploads/'.$path;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update(['spic' => $txt]);
        }
        if($file2){
        $info2 =$file2->move(ROOT_PATH.'public'.DS.'uploads');
        $path2=$info2->getSavename();$txt2='/public/uploads/'.$path2;
        $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[0]=$txt2;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file3){
        $info3 =$file3->move(ROOT_PATH.'public'.DS.'uploads');
        $path3=$info3->getSavename();$txt3='/public/uploads/'.$path3;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[1]=$txt3;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file4){
        $info4 =$file4->move(ROOT_PATH.'public'.DS.'uploads');
        $path4=$info4->getSavename();$txt4='/public/uploads/'.$path4;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[2]=$txt4;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file5){
        $info5 =$file5->move(ROOT_PATH.'public'.DS.'uploads');
        $path5=$info5->getSavename();$txt5='/public/uploads/'.$path5;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[3]=$txt5;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file6){
        $info6 =$file6->move(ROOT_PATH.'public'.DS.'uploads');
        $path6=$info6->getSavename();$txt6='/public/uploads/'.$path6;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[4]=$txt6;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file7){
        $info7 =$file7->move(ROOT_PATH.'public'.DS.'uploads');
        $path7=$info7->getSavename();$txt7='/public/uploads/'.$path7;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[5]=$txt7;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        if($file8){
        $info8 =$file8->move(ROOT_PATH.'public'.DS.'uploads');
        $path8=$info8->getSavename();$txt8='/public/uploads/'.$path8;
         $result=Db::table('che_school')->where(array('id'=>$gxid))->find();
        $imgz=explode(',',$result['img']);
        $imgz[6]=$txt8;
        $imgz=implode(',',$imgz);
        $post['img']=$imgz;
        $gxgx=Db::table('che_school')->where(array('id'=>$gxid))->update($post);
        }
        $info=Db::table('che_school')->where('id ='.$gxid)->update($post);
        $this->success('修改成功');

        }
           
        
            
        
            $request = request();
       $id=$request->param('id');
            
     
     $school=Db::table('che_school')->where(array('id'=>$id))->find();

        
        
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
    //驾校教练 scoach
  
   public function scoach()
{


   //$scoach=Db::table('che_shoolcoach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id =c.sid")->order('c.id desc')->paginate(8); 
   $scoach=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->paginate(8); 

     $this->assign('scoach',$scoach);
 return $this->fetch();

} 
   //学校详情展示 
    public function  school_show(){
          
      
        $get=input('get.');
           $cid=$get['id'];
        $pic=Db::table('che_coach_img')->where(array("coach_id" => $get['id'],'state'=>2))->select();
        $this->assign('pic',$pic);
        $this->assign('coach_id',$cid);
        return $this->fetch();
        
  

    }
  
  //删除学校详情图片
  
     public function s_del(){
          
      
        $get=input('get.');
     
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach_img')->where(array("img_id" => $get['id']))->delete();
        }
       
        
      $this->redirect('/index.php/Admin/school/indexs');

    }
  
    //学校详情展示 添加
    public function  add_spic(){
          $post = input('post.');
        $request = request();
        $file = $request->file("pic");
        $id=input('param.id');
 

        if ($file) {

            //移动
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            //获取上传文件信息
            $path = $info->getSavename();

            $post['pic'] = '/public/uploads/' . $path;
            //coach_id
            $post['state']=2;
            $post['coach_id']=$id;
            $type = Db::table('che_coach_img')->Insert($post);
 
          if ($type) {
                   $this->redirect('/index.php/Admin/school/indexs');

            }

        }
      
        
        $this->assign('coach_id',$id);
         return $this->fetch();
        
  

    }
  
  

}