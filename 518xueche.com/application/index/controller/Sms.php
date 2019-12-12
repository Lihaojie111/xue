<?php
/**
 * Created by PhpStorm.
 * Base全局控制器，非User控制器继承该控制器，以完成相关验证操作
 * User: Biao
 * Date: 2018/8/22
 * Time: 15:22
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Sms extends Controller
{


  public function ajaxsms()
  {
    $data=input('post.');
    $data['code']=rand(1000,9999);
    $data['time']=time();
    if($data['mobile'] == '')
    {
      return '手机号不能为空'; exit;
    }
    $data['mobile'] = $data['mobile'];
    $result=Db::table('che_smslog')->where(array('mobile'=>$data['mobile'],'status'=>0))->find();
    $sj=time();
    $cha=$sj-$result['time'];
    if($cha <= 60)
    {
      return '60秒后才可以重新获取验证码'; exit;
    }
    $xr=Db::table('che_smslog')->insert($data);
    if($xr)
    {
      if($data['lx'] == 1){
        file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$data['mobile'].'&content=【518学车网】尊敬的用户您好，感谢您对518学车的支持，您的注册验证码是'.$data['code'].'。请勿告诉其他人&sendTime=&extno=');

      }else{
        file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$data['mobile'].'&content=【518学车网】尊敬的用户您好，您的登录验证码是'.$data['code'].'。请勿告诉其他人&sendTime=&extno=');
      }
      return '验证码获取成功'; 
    }else{

      return '验证码获取失败'; 

    }
  }


    public function school_detail(){

         $request =request();
         $id=$request->param('id');
         $school_list=Db::table('che_school')->find($id);
         $type=Db::name('shooltype')->select();
         foreach($type as $key => $value){
              $txt='_type'.$value['id'].'_';
              $map['content']=array('like','%'.$txt.'%');
              $map['school_id']=$id;
              $result=Db::table('che_school_can')->where($map)->find();
              if(!$result){ unset($type[$key]);}
         }



         $service=Db::table('che_shoolservice')->select();

         foreach($service as $key1 => $value1){
              $txt1='_service'.$value1['id'].'_';
              $map1['content']=array('like','%'.$txt1.'%');
              $map1['school_id']=$id;
              $result1=Db::table('che_school_can')->where($map1)->find();
              if(!$result1){ unset($service[$key1]);}
         }


         $class=Db::table('che_shoolclass')->select();

         foreach($class as $key2 => $value2){
              $txt2='_class'.$value2['id'].'_';
              $map2['content']=array('like','%'.$txt2.'%');
              $map2['school_id']=$id;
              $result2=Db::table('che_school_can')->where($map2)->find();
              if(!$result2){ unset($class[$key2]);}
         }



         $ss_jiaolian=Db::table('che_coach')->where('school ='.$id)->limit(4)->select();
         

         
         
         foreach($ss_jiaolian as $key4 => $value4)
         {
         $service2=Db::table('che_shoolservice')->select();
         foreach($service2 as $key3 => $value3){
              $txt3='_service'.$value3['id'].'_';
              $map3['content']=array('like','%'.$txt3.'%');
              $map3['coach_id']=$value4['cid'];
              $result3=Db::table('che_coach_can')->where($map3)->find();

              if(!$result3){ unset($service2[$key3]);}
              $ss_jiaolian[$key4]['child']=$result3;
         }

         }
         $this->assign('ss_jiaolian',$ss_jiaolian); 
         $this->assign('school',$school_list); 
         $this->assign('type',$type);
         $this->assign('class',$class);
         $this->assign('service',$service);
         return $this->fetch('index/school_detail');
   



    } 




    public function coach_detail(){
         
      
        $request =request();
         $id=$request->param('id');
        $denglu=1;      
      if(!Session::get('user')){
            //跳转到后台登录界面
          $denglu=0; 
             
      }else{
        $denglu=1;
      }


         $service=Db::table('che_shoolservice')->select();

         foreach($service as $key1 => $value1){
              $txt1='_service'.$value1['id'].'_';
              $map1['content']=array('like','%'.$txt1.'%');
              $map1['coach_id']=$id;
              $result1=Db::table('che_coach_can')->where($map1)->find();
              if(!$result1){ unset($service[$key1]);}
         }

          $this->assign('service',$service);



         $kemu=Db::table('che_kemu')->select();

         foreach($kemu as $key2 => $value2){
              $txt2='_kemu'.$value2['id'].'_';
              $map2['content']=array('like','%'.$txt2.'%');
              $map2['coach_id']=$id;
              $result2=Db::table('che_coach_can')->where($map2)->find();
              if(!$result2){ unset($kemu[$key2]);}
         }
          $this->assign('kemu',$kemu);

       
          $type=Db::table('che_shooltype')->select();

         foreach($type as $key3 => $value3){
              $txt3='_kemu'.$value3['id'].'_';
              $map3['content']=array('like','%'.$txt3.'%');
              $map3['coach_id']=$id;
              $result3=Db::table('che_coach_can')->where($map3)->find();
              if(!$result3){ unset($type[$key3]);}
         }
          $this->assign('type',$type);



          $kaochang=Db::table('che_kaochang')->select();

         foreach($kaochang as $key4 => $value4){
              $txt4='_kaochang'.$value4['id'].'_';
              $map4['content']=array('like','%'.$txt4.'%');
              $map4['coach_id']=$id;
              $result4=Db::table('che_coach_can')->where($map4)->find();
              if(!$result4){ unset($kaochang[$key4]);}
         }
          $this->assign('kaochang',$kaochang);


          $chexing=Db::table('che_chexing')->select();

         foreach($chexing as $key5 => $value5){
              $txt5='_chexing'.$value5['id'].'_';
              $map5['content']=array('like','%'.$txt5.'%');
              $map5['coach_id']=$id;
              $result5=Db::table('che_coach_can')->where($map5)->find();
              if(!$chexing){ unset($chexing[$key5]);}
         }
          $this->assign('chexing',$chexing);

        
        $pingjia=Db::name('pingjia')->where(array("cid"=>$id))->order("id desc")->limit("0","2")->select();
        $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where(array("c.cid"=>$id))->find();
        $conut=count($pingjia);

        
        echo Cookie::get('type');  exit;
        $this->assign('count',$conut);
        $this->assign('denglu',$denglu);
        $this->assign('coach',$coach_list);
        $this->assign('pingjia',$pingjia);
        return $this->fetch('index/coach_detail');
    }


}