<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\File;

class Setmeal extends Base{

  public function cindex(){
		$request=request();
       $state=$request->param('state');
        if($state){
            $where="state=1";
        }

    
        $user_info=Session::get('user_info');
        if($user_info['uid'] != 1)
        {
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_coach')->where('ss_uid ='.$result['id'])->find();
        if($result2) {  $where['coach_id'] = $result2['cid']; }
        }
    
      $result=Db::table('che_coach_can')->order('cid desc')->where(@$where)->paginate(8)->each(function($item, $key){
      $jiao=Db::table('che_coach')->where('cid ='.$item['coach_id'])->find();
      $item['jiaolian']=$jiao['name'];
      return $item;
    });
    $this->assign('result',$result);
    return $this->fetch();
  } 
	//教练
    public function  c_shenghe(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('coach_can')->where('cid',$id)->update(['state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/setmeal/cindex');
            }     
  
  
  
     }
  	//驾校
     public function  s_shenghe(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('school_can')->where('cid',$id)->update(['state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/setmeal/index2');
            }     
  
  
  
     }

  public function index2(){
    $request=request();
       $state=$request->param('state');
        if($state){
            $where="state=1";
        }
    $result=Db::table('che_school_can')->where(@$where)->order('cid desc')->paginate(8)->each(function($item, $key){
      $jiao=Db::table('che_school')->where('id ='.$item['school_id'])->find();
      $item['jiaolian']=$jiao['name'];
      return $item;
    });
    $this->assign('result',$result);
    return $this->fetch();
  } 


  public function add_taocan(){
   $request=request();
  

    $post=input('post.');
    if($post){
      var_dump($post); exit;
      $re1=Db::table('che_shooltype')->where('id',$post['type'])->find();
      $re2=Db::table('che_kemu')->where('id',$post['kemu'])->find();
      $re3=Db::table('che_kaochang')->where('id',$post['kaochang'])->find();
      $re4=Db::table('che_chexing')->where('id',$post['chexing'])->find();
     // $re5=Db::table('che_shoolservice')->where('id',$post['service'])->find();
    //服务

      $service=$post['service']; 
      $s=implode('+',$service); 
  
   
     
      //$data['content']="_type{$post['type']}_kemu{$post['kemu']}_kaochang{$post['kaochang']}_chexing{$post['chexing']}_service{$post['service']}_";
       $data['content']="_type{$post['type']}_kemu{$post['kemu']}_kaochang{$post['kaochang']}_chexing{$post['chexing']}_service{$s}_";
      // $data['miaoshu']="{$re1['type']} + {$re2['kemu']} + {$re3['kaochang']} + {$re4['chexing']} + {$re5['name']}";
        $data['miaoshu']="{$re1['type']} + {$re2['kemu']} + {$re3['kaochang']} + {$re4['chexing']} + {$s}";
      $data['price']=$post['price'];
      $data['coach_id']=$post['jl_list'];
        $user_info=Session::get('user_info');
          if($user_info['uid'] != 1)
                {
            	  $data['state']=1;
                }else{
          	 $data['state']=0;
          }
      $xieru=Db::table('che_coach_can')->insert($data);
      if($xieru){$this->error('套餐添加成功');}else{$this->error('套餐添加失败');}
    }else{
      $jiazhao=Db::table('che_class')->where("type ='type'")->order('id desc')->select();
      $this->assign('jiazhao',$jiazhao);

      $kemu=Db::table('che_class')->where("type ='kemu'")->order('id desc')->select();
      $this->assign('kemu',$kemu);

      $kaochang=Db::table('che_class')->where("type ='kaochang'")->order('id desc')->select();
      $this->assign('kaochang',$kaochang);
      $chexing=Db::table('che_class')->where("type ='chexing'")->order('id desc')->select();
      $this->assign('chexing',$chexing);

      $service=Db::table('che_class')->where("type ='service'")->order('id desc')->select();
      $this->assign('service',$service);
      

        $user_info=Session::get('user_info');
                if($user_info['uid'] != 1)
        {
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_coach')->where('ss_uid ='.$result['id'])->find();
        if($result2) {  $where['cid'] = $result2['cid']; }
        }
      $jl_list=Db::table('che_coach')->where(@$where)->order('cid desc')->select();
      $this->assign('jl_list',$jl_list);
      return $this->fetch();
    }   
  }


  
   //添加套餐
   public function taocan(){
   header('Content-type:text/json;charset=utf-8'); 
    ;
     $request=request();
 
    $a=$request->post('taocans/a');
  foreach ($a as $key => $value) {
      
    $data[$key]=$value;
	foreach($data[$key]  as $k => $v){
    
    	$d[$k]=$v;
    	
    }

  }
  	  print_r($d);exit; 



  } 

     //添加套餐
   public function taocan2(){
   header('Content-type:text/json;charset=utf-8'); 
    ;
     $request=request();
 
    $a=$request->post('taocans/a');
  foreach ($a as $key => $value) {
      
    $data[$key]=$value;
	foreach($data[$key]  as $k => $v){
    
    	$d[$k]=$v;
    	
    }

  }
  	  print_r($d);exit; 



  } 


  
  
  public function ceshi_taocan(){


    $post=input('post.');
    $sz=$post['taocans'];
    foreach($sz as $key => $value) {
     $txt='_';
     $miaoshu='';
      foreach ($value as $key2 => $value2) {
     $result=Db::table('che_class')->where("name ='{$value2}'")->find();
     if($result)
     {
     $txt=$txt.$result['type'].$result['id'].'_';
     $miaoshu=$miaoshu.$result['name'].'+';
     }
      }
    $data['coach_id']=$post['coach_id'];;
    $data['content']=$txt;
    $data['miaoshu']=$miaoshu;
    $data['price']=end($value);
    $user_info=Session::get('user_info');
    if($user_info['uid'] != 1)
    { 
    $data['state']=1;
    }
    Db::table('che_coach_can')->insert($data);

    }
    return '套餐添加成功！';

  }
  

    public function school_taocan(){


    $post=input('post.');
    $sz=$post['taocans'];
    foreach($sz as $key => $value) {
     $txt='_';
     $miaoshu='';
      foreach ($value as $key2 => $value2) {
     $result=Db::table('che_class')->where("name ='{$value2}'")->find();
     if($result)
     {
     $txt=$txt.$result['type'].$result['id'].'_';
     $miaoshu=$miaoshu.$result['name'].'+';
     }
      }
    $data['school_id']=$post['school_id'];;
    $data['content']=$txt;
    $data['miaoshu']=$miaoshu;
    $data['price']=end($value);
    Db::table('che_school_can')->insert($data);

    }
    return '套餐添加成功！';

  }
  
  
  
  
  

  public function add_taocan2(){
    $post=input('post.');
    if($post){

      $re1=Db::table('che_shooltype')->where('id',$post['type'])->find();
      $re2=Db::table('che_shoolclass')->where('id',$post['class'])->find();
      //$re3=Db::table('che_shoolservice')->where('id',$post['service'])->find();
      $service=$post['service']; 
      $s=implode('+',$service); 
  
     // $data['content']="_type{$post['type']}_class{$post['class']}_service{$post['service']}_";
       $data['content']="_type{$post['type']}_class{$post['class']}_service{$s}_";
      

      //$data['miaoshu']="{$re1['type']} + {$re2['class']} + {$re3['name']}";
        $data['miaoshu']="{$re1['type']} + {$re2['class']} + {$s}"; 

      $data['price']=$post['price'];
      $data['school_id']=$post['jx_list'];
      $xieru=Db::table('che_school_can')->insert($data);
      if($xieru){$this->error('套餐添加成功');}else{$this->error('套餐添加失败');}
    }else{
      $jiazhao=Db::table('che_class')->where("type ='type'")->order('id desc')->select();
      $this->assign('jiazhao',$jiazhao);

      $class=Db::table('che_class')->where("type ='class'")->order('id desc')->select();
      $this->assign('class',$class);

      $service=Db::table('che_class')->where("type ='service'")->order('id desc')->select();
      $this->assign('service',$service);

      $time=Db::table('che_class')->where("type ='time'")->order('id desc')->select();
      $this->assign('time',$time);

      $jx_list=Db::table('che_school')->order('id desc')->select();
      $this->assign('jx_list',$jx_list);
      return $this->fetch();
    }   
  }

  //教练修改
  public function exit_can(){
   
       $post=input('post.');
        
 
     
        if($post){
              $user_info=Session::get('user_info');
              if($user_info['uid'] != 1)
              { 
              $post['state']=1;
              }else{
              $post['state']=0;
              }
              $info=Db::name('coach_can')->where(array("cid"=>$post['cid']))->update(array("price"=>$post['price'],"state"=>$post['state']));
               if($info){
                $this->redirect('/index.php/Admin/setmeal/cindex');
               }
        }
    
    
     $request = request();
     $cid=$request->param('cid');
     $coach_can=Db::name('coach_can')->where("cid =$cid")->find();
     $this->assign('taocan',$coach_can);

      return $this->fetch();
  } 

  
    //驾校修改
  public function exit_can2(){
   
       $post=input('post.');
        
 
     
        if($post){
				
              $info=Db::name('school_can')->where(array("cid"=>$post['cid']))->update(array("price"=>$post['price']));
               if($info){
                $this->redirect('/index.php/Admin/setmeal/index2');
               }
        }
    
    
     $request = request();
     $cid=$request->param('cid');
     $coach_can=Db::name('school_can')->where("cid =$cid")->find();
     $this->assign('taocan',$coach_can);

      return $this->fetch();
  } 
  
  
  
  
  
  
  public function del_can(){
    $cid=input('param.cid');
    Db::table('che_coach_can')->where('cid',$cid)->delete();
    $this->error('套餐删除成功！');
  } 


  public function del_can2(){
    $cid=input('param.cid');
    Db::table('che_school_can')->where('cid',$cid)->delete();
    $this->error('套餐删除成功！');
  } 



  
}