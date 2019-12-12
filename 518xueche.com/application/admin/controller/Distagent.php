<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use app\index\logic\MyLogic;

/**
 * Created by PhpStorm.
 * 县级代理商
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class Distagent extends Base
{

    public function _initialize() {
        $user_info=Session::get('user_info');
        //验证登录
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        if($user_info){
            //判断是否拥有访问权限，无权限则退出让重新登录
            if($user_info['type']==1){
                $this->redirect('/index.php/Admin/index/index');
            }elseif($user_info['type']==2){
                $this->redirect('/index.php/Admin/cityagent/index');
            }
            //加载通知
            $notice=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->select();
            $notice_count=Db::name('notice')->where(array("uid"=>$user_info['uid'],"status"=>0))->count();
            //传输数据
            $this->assign('notice',$notice);
            $this->assign('notice_count',$notice_count);
            $this->assign('user_info',$user_info);
          
        }else{
            $this->redirect('/index.php/Admin/User/login');
        }
    }

    /**
     * @return mixed
     * 主页
     */
    public function index()
    {
        $date=date("Y年m月d号",time()) ;
         $user=Session::get('user_info');
        //获取上级id
       
        $this->assign('date',$date);
        $this->assign('user_info',$user);
        
        return $this->fetch();
    }



//添加教练
    public function add_coach()
    {

        $result=Db::table('che_school')->where("state=0")->order('id desc')->select();
        $this->assign('jx_list',$result);

 		$user=Session::get('user_info');

          	$this->assign('user_info',$user);
        return $this->fetch();

    }


  public function do_add_coach()
    {
	
      $user=Session::get('user_info');
	 	
    	$uid=$user['uid'];
        $file = request()->file('img'); 
        $data = input('post.');
       
        if($file){
          
          	$info = $file->validate(['ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');   
            $path=$info->getSaveName(); 
            $data['img']='/public/uploads/'.$path;
           
          
          	$data['bid']=$uid;
          	$data['state']=1;
            $result=Db::table('che_coach')->insert($data);
             $cname=$data['name'];
             $sid=$data['school'];
			
         
            if($result){
                   $cid = Db::table('che_coach')->getLastInsID();

                    Db::table('che_shoolcoach')->insert(array('sid'=>$sid,'cname'=>$cname,'cid'=>$cid));
                $this->redirect('/index.php/Admin/distagent/list_coach');
            }else{
                $this->error('教练添加失败，请联系管理员');
            }
        }else{
            $this->error('上传失败，只能上传图片文件');
        }

    
    }



    public function list_coach()
    {
      
      	$user=Session::get('user_info');
      	$uid=$user['uid'];
        $result=Db::table('che_coach')->where("bid=$uid")->order('cid desc')->paginate(10)->each(function($item, $key){
        $re=Db::table('che_school')->where('id ='.$item['school'])->find();
        $item['jx_name']=$re['name'];
        return $item;
        });
        $this->assign('list',$result);
      	$this->assign('user_info',$user);
        return $this->fetch();

    }
// 修改 教练 
     public function  coach_edit(){
       	$user_info=Session::get('user_info');
       $post=input('post.');
         $request =request();
         $file=$request->file("img");
     
        if($post){
   
          if($file){
          
                     //移动
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        
                //获取上传文件信息
              $path=$info->getSavename();
                 
              $post['spic']='/public/uploads/'.$path;

          }		$post['state']=2;
				$post['xiugai']=1;
              $info=Db::table('che_coach')->update($post);
               if($info){
                 
                   $myLogic=new MyLogic();
        			$resault=$myLogic->inStockApply($user_info,$post['cid']);
                 
                 
                 
                $this->redirect('/index.php/Admin/distagent/list_coach');
               }
        }
           
        
            
        
            $request = request();
            $cid=$request->param('id');
            
        $coach=Db::table('che_coach')->where(array('cid'=>$cid))->find();
        
     
       
       $school=Db::table('che_school')->order('id desc')->select();
      

        $this->assign('user_info',$user_info);
    
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
         $this->redirect('/index.php/Admin/distagent/list_coach');
        


    }
  	//教练详情展示 
    public function  coach_show(){
          
       	$user=Session::get('user_info');
      	$get=input('get.');
      	 $cid=$get['id'];
      	$pic=Db::table('che_coach_img')->where(array("coach_id" => $get['id'],'state'=>1))->select();
        $this->assign('pic',$pic);
       $this->assign('user_info',$user);
    	
      $this->assign('coach_id',$cid);
        return $this->fetch();
        
  

    }
  
  //删除教练详情图片
  
     public function c_del(){
          
      
      	$get=input('get.');
     
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach_img')->where(array("img_id" => $get['id']))->delete();
        }
       
        
  	  $this->redirect('/index.php/Admin/distagent/list_coach');

    }
  
    //删除驾校详情图片
  
     public function d_del(){
          
      
      	$get=input('get.');
     
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach_img')->where(array("img_id" => $get['id']))->delete();
        }
       
        
  	  $this->redirect('/index.php/Admin/distagent/index');

    }
  
   	//教练详情展示 添加
    public function  add_cpic(){
 		 
       	$user=Session::get('user_info');
      
      $post = input('post.');
        $request = request();
        $file = $request->file("pic");
	 	$id=input('param.id');
        if ($file) {

            //移动
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            //获取上传文件信息
            $path = $info->getSavename();

            $post['pic'] = '/public/uploads/'.$path;
         	//coach_id
          	$post['state']=1;
          	$post['coach_id']=$id;
            $type = Db::table('che_coach_img')->Insert($post);
 
          if ($type) {
                $this->redirect('/index.php/Admin/distagent/list_coach');
            }

        }
      
         $this->assign('user_info',$user);
     	$this->assign('coach_id',$id);
     	 return $this->fetch();
        
  

    }
  
  
   public function indexs()
{
 $user=Session::get('user_info');
	
     $uid=$user['uid'];
 $where="bid=$uid";
     if(isset($get['name'])){
            $name=$get['name'];
            //模糊匹配商品的名称、规格、型号、品牌
            $where=$where." and name like '%$name%' OR price like '%$name%'";
        }  
 $result=Db::table('che_school')->where($where)->order('id desc')->paginate(8);
 
 $this->assign('user_info',$user);   
 $this->assign('stype',$result);
 return $this->fetch();

} 
  
//添加驾照类型
  public function add_school(){
     $user=Session::get('user_info');
	$uid=$user['uid'];    
    $post=input('post.');
          $request =request();
         $file=$request->file("spic");   
        if($post){

               if($file){
          
                     //移动
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        
                //获取上传文件信息
                $path=$info->getSavename();
                 
                 $post['spic']='/public/uploads/'.$path;
               }
            	$post['state']=1;
          		 $post['bid']=$uid;

            $type=Db::table('che_school')->Insert($post);
            if($type){
                $this->redirect('/index.php/Admin/distagent/indexs');
            }
        
        }
    

   	 $this->assign('user_info',$user);
         $type=Db::table('che_shooltype')->select();
          $this->assign('type',$type);
       
    
    
        return $this->fetch();
    }
 


     public function  edit(){
         $user_info=Session::get('user_info');
       
       $post=input('post.');
         $request =request();
        $file=$request->file("spic");
     
        if($post){

    

          if($file){
          
                     //移动
             $info =$file->move(ROOT_PATH.'public'.DS.'uploads');
        
                //获取上传文件信息
              $path=$info->getSavename();
                 
              $post['spic']='/public/uploads/'.$path;

          }
          		$post['state']=2;
				$post['xiugai']=1;
              $info=Db::table('che_school')->update($post);
               if($info){
               
                      $myLogic=new MyLogic();
        			$resault=$myLogic->inStockApply($user_info,$post['id']);
                   $this->redirect('/index.php/Admin/distagent/indexs');
               }
        }
           
        
            
        
            $request = request();
       $id=$request->param('id');
            
     
     $school=Db::table('che_school')->where(array('id'=>$id))->find();

        	 $this->assign('user_info',$user_info);
        
          $this->assign('school',$school);
       
    
    
        return $this->fetch();
       
     

    }



  
  //删除
      public function  del(){
          $get=input('get.');
        if(isset($get['id'])) {
            $goods_info = Db::table('che_school')->where(array("id" => $get['id']))->delete();
        }
         $this->redirect('/index.php/Admin/distagent/indexs');
        


    }
    //驾校教练 scoach
  
   public function scoach()
{



   $scoach=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->paginate(8); 

     $this->assign('scoach',$scoach);
 return $this->fetch();

} 
   //学校详情展示 
    public function  school_show(){
            $user=Session::get('user_info');
      
        $get=input('get.');
           $cid=$get['id'];
        $pic=Db::table('che_coach_img')->where(array("coach_id" => $get['id'],'state'=>2))->select();
        $this->assign('pic',$pic);
        $this->assign('coach_id',$cid);
      	$this->assign('user_info',$user); 
      return $this->fetch();
        
  

    }
  
  //删除学校详情图片
  
     public function s_del(){
          
      
        $get=input('get.');
     
        if(isset($get['id'])) {
            $goods_info = Db::table('che_coach_img')->where(array("img_id" => $get['id']))->delete();
        }
       
        
      $this->redirect('/index.php/Admin/distagent/indexs');

    }
  
    //学校详情展示 添加
    public function  add_spic(){
        
          $user=Session::get('user_info');
      
      
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
                   $this->redirect('/index.php/Admin/distagent/indexs');

            }

        }
      
        	$this->assign('user_info',$user); 
        $this->assign('coach_id',$id);
         return $this->fetch();
        
  

    }
  

  
  
  

}
