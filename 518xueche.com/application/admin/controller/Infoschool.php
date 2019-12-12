<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use app\admin\logic\MyLogic;
use think\Paginator;

/**
 * Created by PhpStorm.
 * 教练后台
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class 	Infoschool extends Base
{

   

    /**
     * @return mixed
     * 主页
     */
    public function index()
    {
        $user_info=Session::get('user_info');
        $uid=$user_info['uid'];
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        $remind_stock=$user_info['remind_stock'];

        $date=date("Y年m月d号",time());
       
        $this->assign('date',$date);
      	 $this->assign('user_info',$user_info);
        return $this->fetch();
    }

   public function school_edit_url()
    {
        $user_info=Session::get('user_info');
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_school')->where('ss_uid ='.$result['id'])->find();
        $this->redirect('/index.php/Admin/infoschool/school_edit?id='.$result2['id']);
    }
  
    public function school_edit()
    {
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

        
        $user_info=Session::get('user_info');
            if($user_info['uid'] != 1)
        {
        
            
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
            	
        $result2=Db::table('che_school')->where('ss_uid ='.$result['id'])->find();
        if(!$result2){ $post['ss_uid']= $result['id']; unset($post['id']); $post['state']=1; 
                           if($file){
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
             $path=$info->getSavename();$txt='/public/uploads/'.$path;
        }
              if($file2){
             $info2 =$file2->move(ROOT_PATH.'public'.DS.'uploads');
             $path2=$info2->getSavename();$txt2='/public/uploads/'.$path2;
        }
                 if($file3){
             $info3 =$file3->move(ROOT_PATH.'public'.DS.'uploads');
             $path3=$info3->getSavename();$txt3='/public/uploads/'.$path3;
        }
                 if($file4){
             $info4 =$file4->move(ROOT_PATH.'public'.DS.'uploads');
             $path4=$info4->getSavename();$txt4='/public/uploads/'.$path4;
        }
                 if($file5){
             $info5 =$file5->move(ROOT_PATH.'public'.DS.'uploads');
             $path5=$info5->getSavename();$txt5='/public/uploads/'.$path5;
        }
                 if($file6){
             $info6 =$file6->move(ROOT_PATH.'public'.DS.'uploads');
             $path6=$info6->getSavename();$txt6='/public/uploads/'.$path6;
        }
                 if($file7){
             $info7 =$file7->move(ROOT_PATH.'public'.DS.'uploads');
             $path7=$info7->getSavename();$txt7='/public/uploads/'.$path7;
        }
                 if($file8){
             $info8 =$file8->move(ROOT_PATH.'public'.DS.'uploads');
             $path8=$info8->getSavename();$txt8='/public/uploads/'.$path8;
        }
            
            $post['img']=$txt2.','.$txt3.','.$txt4.','.$txt5.','.$txt6.','.$txt7.','.$txt8;
            $post['spic']=$txt;
            $post['address']='暂无';
            $post['price']='暂无';  
                      
             $info=Db::table('che_school')->insert($post);
            }else{
        
    
          $post['state']=1; 
          
          
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
        }
        if($info){
          $this->redirect('/index.php/Admin/infoschool/school_edit_url');
        }

      	}
      }

     
        //if($post){  $this->success('修改成功'); }

            $request = request();
       		$id=$request->param('id');
      	
            $school=Db::table('che_school')->where(array('id'=>$id))->find();
			if(!$school){$school['name']=''; $school['address']=''; $school['price']='';  $school['id']=$id; $school['summary']=''; }
			$this->assign('school',$school);
       		return $this->fetch();
       

    
    }
  
  
  
    public function sindex(){

        $user_info=Session::get('user_info');
        if($user_info['uid'] != 1)
        {
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_school')->where('ss_uid ='.$result['id'])->find();
        if($result2) {$where['school_id'] = $result2['id']; }
        }
      
      $result=Db::table('che_school_can')->order('cid desc')->where(@$where)->paginate(8)->each(function($item, $key){
      $jiao=Db::table('che_school')->where('id ='.$item['school_id'])->find();
      $item['jiaolian']=$jiao['name'];
      return $item;
    });
    $this->assign('result',$result);
    return $this->fetch();
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
      $data['state']='1';
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

       $user_info=Session::get('user_info');
                if($user_info['uid'] != 1)
        {
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_school')->where('ss_uid ='.$result['id'])->find();
        if($result2) {  $where['id'] = $result2['id']; }
        }
      $jx_list=Db::table('che_school')->where(@$where)->order('id desc')->select();
      $this->assign('jx_list',$jx_list);
      return $this->fetch();
    }   
  }

     //驾校修改
  public function exit_can2(){
   
       $post=input('post.');
        
 
     
        if($post){
          $user_info=Session::get('user_info');
			 if($user_info['uid'] != 1)
              { 
              $post['state']=1;
              }else{
              $post['state']=0;
              }		
          
          
              $info=Db::name('school_can')->where(array("cid"=>$post['cid']))->update(array("price"=>$post['price'],"state"=>$post['state']));
               if($info){
                $this->redirect('/index.php/admin/infoschool/sindex');
               }
        }
    
    
     $request = request();
     $cid=$request->param('cid');
     $coach_can=Db::name('school_can')->where("cid =$cid")->find();
     $this->assign('taocan',$coach_can);

      return $this->fetch();
  } 
  
   public function del_can2(){
    $cid=input('param.cid');
    Db::table('che_school_can')->where('cid',$cid)->delete();
    $this->error('套餐删除成功！');
  } 

  
  
  
}
