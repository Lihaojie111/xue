<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\File;

class Coach extends Base{

//添加教练
    public function add_coach()
    {
        $result=Db::table('che_school')->where("state=0")->order('id desc')->select();
        $this->assign('jx_list',$result);

        $result2=Db::table('che_shoolservice')->order('id desc')->select();
        $this->assign('service_list',$result2);


        $result3=Db::table('che_shooltype')->order('id desc')->select();
        $this->assign('result3',$result3);
        
        return $this->fetch();

    }


  public function do_add_coach()
    {

        $file = request()->file('img'); 
        $data = input('post.');
        
        if($file){
            $info = $file->validate(['ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            $path=$info->getSaveName(); 
            $data['img']='/public/uploads/'.$path;

//            $g=$data['guarantee'];
//            $gu=implode(',',$g);
//            $data['guarantee']=','.$gu.',';
            
            $result=Db::table('che_coach')->insert($data);
             $cname=$data['name'];
             $sid=$data['school'];

         
            if($result){
                   $cid = Db::table('che_coach')->getLastInsID();

                    Db::table('che_shoolcoach')->insert(array('sid'=>$sid,'cname'=>$cname,'cid'=>$cid));
                $this->success('教练添加成功');
            }else{
                $this->error('教练添加失败，请联系管理员');
            }
        }else{
            $this->error('上传失败，只能上传图片文件');
        }

    }



    public function list_coach()
    {
          $request=request();
       $state=$request->param('state');
        if($state){
            $where="state=1";
        }
        $result=Db::table('che_coach')->where(@$where)->order('cid desc')->paginate(10)->each(function($item, $key){
        $re=Db::table('che_school')->where('id ='.$item['school'])->find();
        $item['jx_name']=$re['name'];
        return $item;
        });
        $this->assign('list',$result);
        return $this->fetch();

    }
// 修改 教练 
    public function  coach_edit(){
      $post=input('post.');
      $request =request();
      $file=$request->file("img");
      if($post){
        if($file){
          $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
          $path=$info->getSavename();
          $post['img']='/public/uploads/'.$path;
        }

        $user_info=Session::get('user_info');
            if($user_info['uid'] != 1)
        {
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_coach')->where('ss_uid ='.$result['id'])->find();
        if(!$result2){ $post['ss_uid']= $result['id']; unset($post['cid']); $post['state']=1; $info=Db::table('che_coach')->insert($post);}else{ $ccid=$post['cid'];  unset($post['cid']); $post['state']=1;  $info=Db::table('che_coach')->where('cid ='.$ccid)->update($post); }
        if($info){
          $this->redirect('/index.php/Admin/index/coach_edit_url');
        }

      }else{

         $ccid=$post['cid'];  unset($post['cid']); $info=Db::table('che_coach')->where('cid ='.$ccid)->update($post);
         $this->success('修改成功！','/index.php/admin/coach/list_coach');

      }
      }
      $request = request();
      $cid=$request->param('id');
      $coach=Db::table('che_coach')->where(array('cid'=>$cid))->find();
      $school=Db::table('che_school')->order('id desc')->select();
      if(!$coach){$coach['name']=''; $coach['price']=''; $coach['content']=''; $coach['cid']=$cid;}
      $this->assign('coach',$coach);
      $this->assign('school',$school);
      return $this->fetch();
    }

  
  
  //删除 coach_del
    
      public function  coach_del(){
          $get=input('get.');
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach')->where(array("cid" => $get['id']))->delete();
        }
         $this->redirect('/index.php/Admin/coach/list_coach');
        
        
        
        
        
 

    }
  	//教练详情展示 
    public function  coach_show(){
          
      
      	$get=input('get.');
      	   $cid=$get['id'];
      	$pic=Db::table('che_coach_img')->where(array("coach_id" => $get['id'],'state'=>1))->select();
        $this->assign('pic',$pic);
     	$this->assign('coach_id',$cid);
        return $this->fetch();
        
  

    }
  
  //删除教练详情图片
  
     public function c_del(){
          
      
      	$get=input('get.');
     
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach_img')->where(array("img_id" => $get['id']))->delete();
        }
       
        
  	  $this->redirect('/index.php/Admin/coach/list_coach');

    }
  
   	//教练详情展示 添加
    public function  add_cpic(){
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
          	$post['state']=1;
          	$post['coach_id']=$id;
            $type = Db::table('che_coach_img')->Insert($post);
 
          if ($type) {
                $this->redirect('/index.php/Admin/coach/list_coach');
            }

        }
      
        
     	$this->assign('coach_id',$id);
     	 return $this->fetch();
        
  

    }
  
  
  
   //审核
     public function  shenghe(){
    	$request=request();
       $id=$request->param('id');
      		
	 		$state=Db::name('coach')->where('cid',$id)->update(['state'=>0]);
          	if($state){
              	  $this->redirect('/index.php/Admin/coach/list_coach');
            }     
  
  
  
     }
  
  
  
  
  
  
  
}