<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use app\admin\logic\MyLogic;
use think\Paginator;

/**
 * Created by PhpStorm.
 * 客服
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class Cityagent extends Base
{

    public function _initialize() {
        $user_info=Session::get('user_info');
        //验证登录
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        if($user_info){
            //判断是否拥有访问权限，无权限则退出让重新登录
            if($user_info['type']==1){
                $this->redirect('/index.php/Admin/index/index');
            }elseif($user_info['type']==3){
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
        $user_info=Session::get('user_info');
        $uid=$user_info['uid'];
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        $remind_stock=$user_info['remind_stock'];

        $date=date("Y年m月d号",time());
       
        $this->assign('date',$date);
      	 $this->assign('user_info',$user_info);
        return $this->fetch();
    }

    /**
     * @return mixed
     * 库存管理
     */
    public function stock(){
        $user_info=Session::get('user_info');
        $cid=$user_info['uid'];
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        $where=" 1 = 1 and cid = $cid";
        $remind_stock=$user_info['remind_stock'];
        $get=input('get.');
        $get['empty']= isset($get['empty'])?$get['empty']:"N";
        $tag="none";
        if($get['empty']=="Y"){
            $where=$where." and stock < $remind_stock";
            $tag="empty";
        }
        if(isset($get['name'])){
            $name=$get['name'];
            //模糊匹配商品的名称、规格、型号、品牌
            $where=$where." and tel_name like '%$name%' OR dianhua like '%$name%'";
        }
        $goods_lists=Db::name('customer_tel')->where($where)->order("id DESC")->paginate(8);
       
 
       
        $this->assign('user_info',$user_info);
        $this->assign('goods_list',$goods_lists);
        $this->assign('tag',$tag);
       
        return $this->fetch();
    }

   	//修改已拨打状态
  	    public function customer_state(){
       
    	$request=request();
    	$id=$request->param('id');
    		  $state=Db::name('customer_tel')->where('id',$id)->update(['state' =>1]);
       
        	if($state){
            		 $this->redirect('/index.php/Admin/cityagent/stock');
            
            }
     
     
       
 
       
      
       
        
    }

  
  
   	//电话内容描述
  	    public function describe(){

        $user_info=Session::get('user_info');
        $post=input('post.');
     	if($post){
        	$describe=$post['describe'];
        	 $state=Db::name('customer_tel')->where('id',$post['id'])->update(['describe'=>$describe,'state'=>2]);
          	 $this->redirect('/index.php/Admin/cityagent/stock');
          
        }
        
         

    }
  
  
  

  
      	//电话内容描述详情
  	    public function    customer_show(){
	  $user_info=Session::get('user_info');
      	$request=request();
    	$id=$request->param('id');
    	$show=Db::name('customer_tel')->where('id',$id)->find();
  		 $this->assign('show',$show);
           $this->assign('user_info',$user_info);
	 	return $this->fetch();
    }
  
  
    /**
     * 密码修改     县经销商
     */
    public function cmima(){
          $post=input('post.');
         if($post){
             $post['password']=md5($post['password']);
               $data=array(
                
                'password'=>$post['password'],
                'uid'=>$post['uid'],
               );   

              $info=Db::name('user')->update($data);
               if($info){
                $this->redirect('/index.php/Index/cityagent/index');
               }
        }
          $user=Session::get('user_info');
            //获取用户id
             $uid=$user['uid'];
                //用户名称
             $name=$user['name'];
             $this->assign('uid',$uid);
             $this->assign('name',$name);
        return $this->fetch();
    }

    //个人中心
    public function selfcenter(){
        return $this->fetch();
    }

    //编辑个人信息
    public function editinfo(){
        $post=input('post.');
        if($post){
            $info=Db::name('user_info')->where(array("info_id"=>$post['info_id']))->update($post);
            if($info){
                $this->redirect('/index.php/Index/cityagent/selfcenter');
            }
        }
        return $this->fetch();
    }
    //库存提醒值
    public function minstock(){
        header('Content-Type:application/json; charset=utf-8');
        $user=Session::get('user_info');
        //获取用户id
        $uid=$user['uid'];
        $post=input('post.');
        if($post){
          $remind_stock=$post['remind_stock'];
          Db::name('user_info')->where(array("uid"=>$uid))->update(array("remind_stock"=>$remind_stock));
          $user_info=Db::name('user_info')->where(array("uid"=>$uid))->find();
          Session::set('user_info',$user_info);
          $arr = array('code'=>1,'msg'=>2);
        }

        exit(json_encode($arr));

    }
  
  
  
  
}
