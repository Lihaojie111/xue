<?php
namespace app\index\controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\index\logic\MyLogic;
use think\Controller;
use think\File;

/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class Xxhf extends Controller
{
	public function two(){
		if(!Session::get('user')){
		//	$this->redirect('/index.php/index/index/ulogin.html');  exit;	
        $usxx='';
        }
		$usxx=Session::get('user');
		$post=input('post.');
		if($post)
		{
    
			
			$post['uid']=$usxx['id'];
			$post['time']=time();
			$post['jjlxr']=$post['txt1'].'，'.$post['txt2'].'，'.$post['txt3'].'/'.$post['txta1'].'，'.$post['txta2'].'，'.$post['txta3'];
			
          	
         
          	$h=Db::table('che_xxhf')->strict(false)->where('id ='.$post['yid'])->update($post);
			$hotline=Db::name('hotline')->order("line_id DESC")->find();
           // file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，有用户提交【先学后付】申请，请注意登录后台查看！&sendTime=&extno='); 
          	$this->redirect('/index.php/index/xxhf/three/yid/'.$post['yid']);  exit;
			
		}
		$yid=input('param.yid');
		$this->assign('yid',$yid);
		return $this->fetch();
	}



	public function one(){
		if(!Session::get('user')){
			//$this->redirect('/index.php/index/index/ulogin.html');  exit;
			$usxx='';
        }
		$usxx=Session::get('user');
	
      	$post=input('post.');
		if($post)
		{
		
 			if($usxx){
             	$post['uid']=$usxx['id'];
            }           
         
          
			$post['time']=time();
			Db::table('che_xxhf')->strict(false)->insert($post);
			$yid = Db::table('che_xxhf')->getLastInsID();
			$this->redirect('/index.php/index/xxhf/two/yid/'.$yid);  exit;
		}

		return $this->fetch();
	}

	public function three(){

		//if(!Session::get('user')){
		//	$this->redirect('/index.php/index/index/ulogin.html');  exit;
		//}
 
		$yid=input('param.yid');
     	$tijiao=Db::table('che_xxhf')->where("id =$yid")->find();
      	$jia=$tijiao['price'];
      	$zong=($jia -500) /2;
      	$this->assign('zong',$zong); 
         $this->assign('tijiao',$tijiao); 
		$this->assign('yid',$yid);
		return $this->fetch();

	}


	public function do_three(){

		   
			if(!Session::get('user')){
				$this->redirect('/index.php/index/index/ulogin.html');  exit;
			}

			$file = request()->file('filea'); 
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if ($info) { 
			$sz['img1']=$info->getSaveName();
			Db::table('che_xxhf')->strict(false)->where('id ='.$post['yid'])->update($sz);
			} 

			$file2 = request()->file('fileb'); 
            $info2 = $file2->move(ROOT_PATH . 'public' . DS . 'uploads');
			if ($info2) { 
			$sz['img2']=$info2->getSaveName();
			Db::table('che_xxhf')->strict(false)->where('id ='.$post['yid'])->update($sz);
			} 
   
			echo '<script>alert("申请先学后付名额成功，请等待审核！");loaction.href="/"</script>';
			

	}



// 	public function xieup(){

//     $img = isset($_POST['img'])? $_POST['img'] : '';

//     list($type, $data) = explode(',', $img);

//     if(strstr($type,'image/jpeg')!=''){
//         $ext = '.jpg';
//     }elseif(strstr($type,'image/gif')!=''){
//         $ext = '.gif';
//     }elseif(strstr($type,'image/png')!=''){
//         $ext = '.png';
//     }else{

//     	exit;
//     }

//     $photo = 'public/img/'.time().$ext;

//     file_put_contents($photo, base64_decode($data), true);
//     if($_POST['lx'] == 1)
//     {
//     Db::table('che_xxhf')->strict(false)->where('id ='.$_POST['yid'])->update(['img1'=>$photo]);
//     $ret = array('imgurl'=>'/'.$photo);
//     return json_encode($ret); exit;
//     }

//     if($_POST['lx'] == 2)
//     {
//     Db::table('che_xxhf')->strict(false)->where('id ='.$_POST['yid'])->update(['img2'=>$photo]);
//     $ret = array('imgurl'=>'/'.$photo);
//     return json_encode($ret); exit;
//     }




// }

public function xieup(){
    $img = isset($_POST['img'])? $_POST['img'] : '';

// 获取图片
    list($type, $data) = explode(',', $img);

// 判断类型
    if(strstr($type,'image/jpeg')!=''){
        $ext = '.jpg';
    }elseif(strstr($type,'image/gif')!=''){
        $ext = '.gif';
    }elseif(strstr($type,'image/png')!=''){
        $ext = '.png';
    }

// 生成的文件名
    $photo = 'public/img/'.time().$ext;

// 生成文件
    file_put_contents($photo, base64_decode($data), true);

// 返回
//    header('content-type:application/json;charset=utf-8');
    $ret = array('img'=>$photo);

    echo json_encode($ret);

}

	public function del_img(){

	$post=input('post.');
	var_dump(unlink('./'.$post['imgurl']['imgurl']));

	}

	public function debit_process(){
		return $this->fetch();
	}

	public function refund518(){
      	$yid=input('param.yid');
 
     	$tijiao=Db::table('che_xxhf')->where("id =$yid")->find();

         $this->assign('tijiao',$tijiao); 
		$this->assign('yid',$yid);
		return $this->fetch();
	}


  	//   先学 后付 二维码版
	public function saoma(){
      $request=request();	
      $id=$request->param("id");
      	//获取驾校
      
      //查询驾校名称
       $school=Db::name('school')->find($id);
      	$name=$school['name'];
       $school_can=Db::name('school_can')->where("school_id=$id")->select();
    
  		$this->assign('school_can',$school_can);
      	$post=input('post.');
		if($post)
		{
			
			$post['time']=time();
      
              
          	$id=$post['banxing'];
        
          	//查询套餐价格
          	$can = Db::name('school_can')->where("cid=$id")->find();
          	$post['price']=$can['price'];
          	$post['taocan']=$can['miaoshu'];
          	$post['s_id']=$id;
			Db::table('che_xxhf')->strict(false)->insert($post);
			$yid = Db::table('che_xxhf')->getLastInsID();
			$this->redirect('/index.php/index/xxhf/two/yid/'.$yid);  exit;
		}
      	
        $this->assign('school',$school);
      	$this->assign('school_can',$school_can);
		$this->assign('name',$name);
      	$this->assign('id',$id);
	return $this->fetch();
	}
  
  
}