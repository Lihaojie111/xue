<?php
namespace app\index\controller;
use think\Db;
use think\Session;
use think\Cookie;
use app\index\logic\MyLogic;
/**
* Created by PhpStorm.
* User: Biao
* Date: 2018/8/22
* Time: 11:22
*/
class Index extends Base
{
/**
* @return mixed
* 主页
*/
public function index()
{
  $this->redirect('/index.php/index/index/home');
}
/**
* @return mixed
* 跳转至真正的主页
*/
public function home(){
  if(!Session::get('user')){
//跳转到后台登录界面
    $denglu=0;
  }else{
    $denglu=1;
  }
//轮播图
  $map_list=Db::table('che_map')->select();
  $school_list=Db::table('che_school')->where("state=0")->order('id desc')->limit("0","4")->select();
  $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where("c.state=0")->order('c.cid desc')->limit("0","4")->select();
  foreach($school_list as $key6 => $value6)
  {
    $service7=Db::table('che_class')->where("type = 'service'")->select();
    $school_list[$key6]['child2'][]=array('pic' =>'');
    foreach($service7 as $key8 => $value8){
      $txt4='_service'.$value8['id'].'_';
      $map4['content']=array('like','%'.$txt4.'%');
      $map4['school_id']=$value6['id'];
      $result4=Db::table('che_school_can')->where($map4)->find();
      if($result4)
      {
        $school_list[$key6]['child2'][]=$value8;
      }
    }
  }
//var_dump($school_list);exit;
  foreach($coach_list as $key => $value)
  {
    $service2=Db::table('che_class')->where("type = 'service'")->select();
    $coach_list[$key]['child'][]=array('pic' =>'');
    foreach($service2 as $key3 => $value3){
      $txt3='_service'.$value3['id'].'_';
      $map3['content']=array('like','%'.$txt3.'%');
      $map3['coach_id']=$value['cid'];
      $result3=Db::table('che_coach_can')->where($map3)->find();
      if($result3)
      {
        $coach_list[$key]['child'][]=$value3;
      }
    }
  }
//新闻
  $news=Db::table('che_news')->select();
//var_dump($coach_list[2]['child']);exit;
//   var_dump($coach_list);exit;
//客服电话
  $hotline=Db::name('hotline')->find();
  $this->assign('hotline',$hotline);
  $this->assign('news',$news);
  $this->assign('school_list',$school_list);
  $this->assign('coach_list',$coach_list);
  $this->assign('map_list',$map_list);
  $this->assign('denglu',$denglu);
  return $this->fetch();
}
/**
* @return mixed
* 教练列表
*/
public function indexcoach(){
  Session::get('user');
  $post=input("post.");
//var_dump(Cookie::get('type'));exit;
  if($post){
    Cookie::set('search','Y');
    $type=Cookie::get('type');
    $kemu=Cookie::get('kemu');
    $kaochang=Cookie::get('kaochang');
    $chexing=Cookie::get('chexing');
    $service=Cookie::get('service');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($kemu){
      $where=$where." and content like '%$kemu%'";
    }
    if($kaochang){
      $where=$where." and content like '%$kaochang%'";
    }
    if($chexing){
      $where=$where." and content like '$chexing%'";
    }
    if($service){
      $where=$where." and content like '%$service%'";
    }
    $coach_can=Db::name('coach_can')->where($where)->select();
    $where1="1=1";
    if($coach_can){
      $in="(";
      foreach ($coach_can as $key=>$value){
        if($key==0){
          $in=$in.$value['coach_id'];
        }else{
          $in=$in.",".$value['coach_id'];
        }
      }
      $in=$in.")";
      $where1="c.cid in $in";
    }else{
      $where1="1=2";
    }
    $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where($where1)->order('cid desc')->paginate(4)->each(function($item, $key){
      $service2=Db::table('che_class')->where("type = 'service'")->select();
      foreach($service2 as $key3 => $value3){
        $txt3='_service'.$value3['id'].'_';
        $map3['content']=array('like','%'.$txt3.'%');
        $map3['coach_id']=$item['cid'];
        $result3=Db::table('che_coach_can')->where($map3)->find();
        if($result3)
        {
          $item['child'][]=$value3;
        }else{
          $item['child'][]=array('pic' => '');
        }
      }
      return $item;
    });
  }else{
//非通过查询进来的删除cookie
    setcookie("type");
    setcookie("kemu");
    setcookie("kaochang");
    setcookie("chexing");
    setcookie("service");
    setcookie("search");
    Cookie::clear('think_');
    $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where("c.state=0")->order('cid desc')->paginate(4)->each(function($item, $key){
      $service2=Db::table('che_class')->where("type = 'service'")->select();
      foreach($service2 as $key3 => $value3){
        $txt3='_service'.$value3['id'].'_';
        $map3['content']=array('like','%'.$txt3.'%');
        $map3['coach_id']=$item['cid'];
        $result3=Db::table('che_coach_can')->where($map3)->find();
        if($result3)
        {
          $item['child'][]=$value3;
        }else{
          $item['child'][]=array('pic' => '');
        }
      }
      return $item;
    });
  }
  $shooltype=Db::name('class')->where("type = 'type'")->select();
  $kemu=Db::name('class')->where("type = 'kemu'")->select();
  $kaochang=Db::name('class')->where("type = 'kaochang'")->select();
  $chexing=Db::name('class')->where("type = 'chexing'")->select();
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $this->assign('coach_list',$coach_list);
  $this->assign('shooltype',$shooltype);
  $this->assign('kemu',$kemu);
  $this->assign('kaochang',$kaochang);
  $this->assign('chexing',$chexing);
  $this->assign('shoolservice',$shoolservice);
  return $this->fetch();
}
public function banxing(){
  if(!Session::get('user')){
//跳转到后台登录界面
    $denglu=0;
  }else{
    $denglu=1;
  }
  $this->assign('denglu',$denglu);
  return $this->fetch();
}
public function banxing_info(){
  header('Content-type: text/json');
  $request=request();
  $s=$request->post('s');
  if($s=='shi'){
    $result = Db::name('banxing_content')->where(array("bid"=>40))->find();
    if($result){
      $result['pic']="/public/home/images/bandetailshi.png";
      $result['ban']="十天班";
      $result['pic2']="/public/home/images/shitian.png";
      $arr['name']=$result;
      exit(json_encode($arr));
    }
  }else if($s=='bao'){
    $result = Db::name('banxing_content')->where(array("bid"=>45))->find();
    if($result){
      $result['pic']="/public/home/images/bandetailbao.png";
      $result['ban']="保过班";
      $result['pic2']="";
      $arr['name']=$result;
      exit(json_encode($arr));
    }
  }else if($s=='wu'){
    $result = Db::name('banxing_content')->where(array("bid"=>38))->find();
    if($result){
      $result['pic']="/public/home/images/bandetailwu.png";
      $result['ban']="五天班";
      $result['pic2']="/public/home/images/wutian.png";
      $arr['name']=$result;
      exit(json_encode($arr));
    }
  }else if($s=='qi'){
    $result = Db::name('banxing_content')->where(array("bid"=>39))->find();
    if($result){
      $result['pic']="/public/home/images/bandetailqi.png";
      $result['ban']="七天班";
      $result['pic2']="/public/home/images/qitian.png";
      $arr['name']=$result;
      exit(json_encode($arr));
    }
  }
}
public function coach(){
  Session::get('user');
  $post=input("post.");
//var_dump(Cookie::get('type'));exit;
  if($post){
    Cookie::set('search','Y');
    $type=Cookie::get('type');
    $kemu=Cookie::get('kemu');
    $kaochang=Cookie::get('kaochang');
    $chexing=Cookie::get('chexing');
    $service=Cookie::get('service');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($kemu){
      $where=$where." and content like '%$kemu%'";
    }
    if($kaochang){
      $where=$where." and content like '%$kaochang%'";
    }
    if($chexing){
      $where=$where." and content like '$chexing%'";
    }
    if($service){
      $where=$where." and content like '%$service%'";
    }
    $coach_can=Db::name('coach_can')->where($where)->select();
    $where1="1=1";
    if($coach_can){
      $in="(";
      foreach ($coach_can as $key=>$value){
        if($key==0){
          $in=$in.$value['coach_id'];
        }else{
          $in=$in.",".$value['coach_id'];
        }
      }
      $in=$in.")";
      $where1="c.cid in $in";
    }else{
      $where1="1=2";
    }
    $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where($where1)->order('cid desc')->paginate(4)->each(function($item, $key){
      $service2=Db::table('che_class')->where("type = 'service'")->select();
      foreach($service2 as $key3 => $value3){
        $txt3='_service'.$value3['id'].'_';
        $map3['content']=array('like','%'.$txt3.'%');
        $map3['coach_id']=$item['cid'];
        $result3=Db::table('che_coach_can')->where($map3)->find();
        if($result3)
        {
          $item['child'][]=$value3;
        }else{
          $item['child'][]=array('pic' => '');
        }
      }
      return $item;
    });
  }else{
//非通过查询进来的删除cookie
    setcookie("type", "", time() - 3600,'/');
    setcookie("kemu", "", time() - 3600,'/');
    setcookie("kaochang", "", time() - 3600,'/');
    setcookie("chexing", "", time() - 3600,'/');
    setcookie("service", "", time() - 3600,'/');
    setcookie("search", "", time() - 3600,'/');
    $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where("c.state=0")->order('cid desc')->paginate(4)->each(function($item, $key){
      $service2=Db::table('che_class')->where("type = 'service'")->select();
      foreach($service2 as $key3 => $value3){
        $txt3='_service'.$value3['id'].'_';
        $map3['content']=array('like','%'.$txt3.'%');
        $map3['coach_id']=$item['cid'];
        $result3=Db::table('che_coach_can')->where($map3)->find();
        if($result3)
        {
          $item['child'][]=$value3;
        }else{
          $item['child'][]=array('pic' => '');
        }
      }
      return $item;
    });
  }
  $shooltype=Db::name('class')->where("type = 'type'")->select();
  $kemu=Db::name('class')->where("type = 'kemu'")->select();
  $kaochang=Db::name('class')->where("type = 'kaochang'")->select();
  $chexing=Db::name('class')->where("type = 'chexing'")->select();
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $this->assign('coach_list',$coach_list);
  $this->assign('shooltype',$shooltype);
  $this->assign('kemu',$kemu);
  $this->assign('kaochang',$kaochang);
  $this->assign('chexing',$chexing);
  $this->assign('shoolservice',$shoolservice);
  return $this->fetch();
}
public function ajax_coach(){
  header('Content-Type:application/json');
  $post=input('post.');
  $page=$post['page'];
  $search=Cookie::get('search');
  $type=Cookie::get('type');
  $kemu=Cookie::get('kemu');
  $kaochang=Cookie::get('kaochang');
  $chexing=Cookie::get('chexing');
  $service=Cookie::get('service');
  if($search and ($type or $kemu or $kaochang or $chexing or $service)){
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($kemu){
      $where=$where." and content like '%$kemu%'";
    }
    if($kaochang){
      $where=$where." and content like '%$kaochang%'";
    }
    if($chexing){
      $where=$where." and content like '$chexing%'";
    }
    if($service){
      $where=$where." and content like '%$service%'";
    }
    $coach_can=Db::name('coach_can')->where($where)->select();
    $where1="1=1";
    if($coach_can){
      $in="(";
      foreach ($coach_can as $key=>$value){
        if($key==0){
          $in=$in.$value['coach_id'];
        }else{
          $in=$in.",".$value['coach_id'];
        }
      }
      $in=$in.")";
      $where1="c.cid in $in";
    }else{
      $where1="1=2";
    }
    $school_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where($where1)->order('cid desc')->limit($page,1)->select();
  }else{
    $school_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->order('cid desc')->limit($page,1)->select();
  }
//$raw_success = array('code' => 2, 'msg' => '加载失败');
  if($school_list){
    foreach ($school_list as $key=>$value){
      $raw_success = array('code' => 1,'cid'=>$value['cid'], 'name' => $value['name'],'school_name'=>$value['school_name'],'pic'=>$value['img']);
    }
  }else{
    $raw_success = array('code' => 2, 'msg' => '已经显示完啦');
  }
  echo json_encode((object)$raw_success);
}
/**
* @return mixed
* 驾校详情
*/
public function driveschool_entrance(){
  return $this->fetch();
}
/**
* @return mixed
* 驾校列表
*/
public function coach_total_price(){
  $map=input('post.total');
  $map=$map.'_';
  $price=Db::table('che_coach_can')->where("content = '{$map}'")->find();
  return $price['price'];
}
public function school_total_tongji(){
  $map=input('post.total');
  $map=$map.'_';
  $price=Db::table('che_school_can')->where("content = '{$map}'")->find();
  return $price['price'];
}
public function gold_school(){
  $post=input("post.");
  if($post){
    setcookie('search','Y');
    $type=Cookie::get('type');
    $schoolclsass=Cookie::get('class');
    $schoolservice=Cookie::get('service');
    $time=Cookie::get('time');
    $price=Cookie::get('price');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($schoolclsass){
      $where=$where." and content like '%$schoolclsass%'";
    }
    if($schoolservice){
      $where=$where." and content like '%$schoolservice%'";
    }
    $school_can=Db::name('school_can')->where($where)->select();
    $where1="1=1";
    if($school_can){
      $in="(";
      foreach ($school_can as $key=>$value){
        if($key==0){
          $in=$in.$value['school_id'];
        }else{
          $in=$in.",".$value['school_id'];
        }
      }
      $in=$in.")";
      $where1="id in $in";
    }else{
      $where1="1=2";
    }
    $where3="1=1";
    if($time){
      if($time=="light") {
        $where3 = $where3 . " and baitian = 0";
      }elseif ($time=="dark"){
        $where3 = $where3 . " and baitian = 1";
      }
    }
    if($price){
      if($price=="<2000"){
        $where3=$where3." and  price <2000 ";
      }elseif($price=="2000-3000"){
        $where3=$where3." and  price >2000 and price < 3000";
      }elseif($price=="3000-4000"){
        $where3=$where3." and  price >3000 and price < 4000";
      }elseif($price==">4000"){
        $where3=$where3." and  price >4000";
      }
    }
    $school_list=Db::table('che_school')->where($where1)->where("state=0")->where($where3)->order('id desc')->limit("0","4")->select();
  }else{
    setcookie("type", "", time() - 3600,'/');
    setcookie("class", "", time() - 3600,'/');
    setcookie("service", "", time() - 3600,'/');
    setcookie("time", "", time() - 3600,'/');
    setcookie("price", "", time() - 3600,'/');
    setcookie("search", "", time() - 3600,'/');
//var_dump(Cookie::get('search'));exit;
    $school_list=Db::table('che_school')->where("state=0")->order('id desc')->limit("0","4")->select();
  }
  $this->assign('school_list',$school_list);
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $shoolclass=Db::name('class')->where("type = 'class'")->select();
  $schooltime=Db::name('class')->where("type = 'time'")->select();
  $this->assign('schooltime',$schooltime);
  $this->assign('shoolservice',$shoolservice);
  $this->assign('schoolclass',$shoolclass);
  return $this->fetch();
}
public function indexgold_school(){
  $post=input("post.");
  if($post){
    setcookie('search','Y');
    $type=Cookie::get('type');
    $schoolclsass=Cookie::get('class');
    $schoolservice=Cookie::get('service');
    $time=Cookie::get('time');
    $price=Cookie::get('price');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($schoolclsass){
      $where=$where." and content like '%$schoolclsass%'";
    }
    if($schoolservice){
      $where=$where." and content like '%$schoolservice%'";
    }
    $school_can=Db::name('school_can')->where($where)->select();
    $where1="1=1";
    if($school_can){
      $in="(";
      foreach ($school_can as $key=>$value){
        if($key==0){
          $in=$in.$value['school_id'];
        }else{
          $in=$in.",".$value['school_id'];
        }
      }
      $in=$in.")";
      $where1="id in $in";
    }else{
      $where1="1=2";
    }
    $where3="1=1";
    if($time){
      if($time=="light") {
        $where3 = $where3 . " and baitian = 0";
      }elseif ($time=="dark"){
        $where3 = $where3 . " and baitian = 1";
      }
    }
    if($price){
      if($price=="<2000"){
        $where3=$where3." and  price <2000 ";
      }elseif($price=="2000-3000"){
        $where3=$where3." and  price >2000 and price < 3000";
      }elseif($price=="3000-4000"){
        $where3=$where3." and  price >3000 and price < 4000";
      }elseif($price==">4000"){
        $where3=$where3." and  price >4000";
      }
    }
    $school_list=Db::table('che_school')->where($where1)->where($where3)->order('id desc')->limit("0","4")->select();
  }else{
    setcookie("type", "", time() - 3600,'/');
    setcookie("class", "", time() - 3600,'/');
    setcookie("service", "", time() - 3600,'/');
    setcookie("time", "", time() - 3600,'/');
    setcookie("price", "", time() - 3600,'/');
    setcookie("search", "", time() - 3600,'/');
//var_dump(Cookie::get('search'));exit;
    $school_list=Db::table('che_school')->where("state=0")->order('id desc')->limit("0","4")->select();
  }
  $this->assign('school_list',$school_list);
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $shoolclass=Db::name('class')->where("type = 'class'")->select();
  $schooltime=Db::name('class')->where("type = 'time'")->select();
  $this->assign('schooltime',$schooltime);
  $this->assign('shoolservice',$shoolservice);
  $this->assign('shoolclass',$shoolclass);
  return $this->fetch();
}
public function gold_desc(){
  $post=input("post.");
  if($post){
    setcookie('search','Y');
    $type=Cookie::get('type');
    $schoolclsass=Cookie::get('class');
    $schoolservice=Cookie::get('schoolservice');
    $time=Cookie::get('time');
    $price=Cookie::get('price');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($schoolclsass){
      $where=$where." and content like '%$schoolclsass%'";
    }
    if($schoolservice){
      $where=$where." and content like '%$schoolservice%'";
    }
    $school_can=Db::name('school_can')->where($where)->select();
    $where1="1=1";
    if($school_can){
      $in="(";
      foreach ($school_can as $key=>$value){
        if($key==0){
          $in=$in.$value['school_id'];
        }else{
          $in=$in.",".$value['school_id'];
        }
      }
      $in=$in.")";
      $where1="id in $in";
    }else{
      $where1="1=2";
    }
    $where3="1=1";
    if($time){
      if($time=="light") {
        $where3 = $where3 . " and baitian = 0";
      }elseif ($time=="dark"){
        $where3 = $where3 . " and baitian = 1";
      }
    }
    if($price){
      if($price=="<2000"){
        $where3=$where3." and  price <2000 ";
      }elseif($price=="2000-3000"){
        $where3=$where3." and  price >2000 and price < 3000";
      }elseif($price=="3000-4000"){
        $where3=$where3." and  price >3000 and price < 4000";
      }elseif($price==">4000"){
        $where3=$where3." and  price >4000";
      }
    }
    $school_list=Db::table('che_school')->where($where1)->where($where3)->limit("0","4")->select();
  }else{
    setcookie("type");
    setcookie("schoolclass");
    setcookie("schoolservice");
    setcookie("search");
    Cookie::clear('think_');
//var_dump(Cookie::get('search'));exit;
// $school_list=Db::table('che_school')->where("state=0")->limit("0","4")->select();
    $school_list=Db::table('che_school')->where("state=0")->order('price desc')->limit("0","4")->select();
  }
  $this->assign('school_list',$school_list);
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $shoolclass=Db::name('class')->where("type = 'class'")->select();
  $this->assign('shoolservice',$shoolservice);
  $this->assign('schoolclass',$shoolclass);
  return $this->fetch();
}
public function gold_asc(){
  $post=input("post.");
  if($post){
    setcookie('search','Y');
    $type=Cookie::get('type');
    $schoolclsass=Cookie::get('class');
    $schoolservice=Cookie::get('schoolservice');
    $time=Cookie::get('time');
    $price=Cookie::get('price');
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($schoolclsass){
      $where=$where." and content like '%$schoolclsass%'";
    }
    if($schoolservice){
      $where=$where." and content like '%$schoolservice%'";
    }
    $school_can=Db::name('school_can')->where($where)->select();
    $where1="1=1";
    if($school_can){
      $in="(";
      foreach ($school_can as $key=>$value){
        if($key==0){
          $in=$in.$value['school_id'];
        }else{
          $in=$in.",".$value['school_id'];
        }
      }
      $in=$in.")";
      $where1="id in $in";
    }else{
      $where1="1=2";
    }
    $where3="1=1";
    if($time){
      if($time=="light") {
        $where3 = $where3 . " and baitian = 0";
      }elseif ($time=="dark"){
        $where3 = $where3 . " and baitian = 1";
      }
    }
    if($price){
      if($price=="<2000"){
        $where3=$where3." and  price <2000 ";
      }elseif($price=="2000-3000"){
        $where3=$where3." and  price >2000 and price < 3000";
      }elseif($price=="3000-4000"){
        $where3=$where3." and  price >3000 and price < 4000";
      }elseif($price==">4000"){
        $where3=$where3." and  price >4000";
      }
    }
    $school_list=Db::table('che_school')->where($where1)->where($where3)->limit("0","4")->select();
  }else{
    setcookie("type");
    setcookie("schoolclass");
    setcookie("schoolservice");
    setcookie("search");
    Cookie::clear('think_');
//var_dump(Cookie::get('search'));exit;
// $school_list=Db::table('che_school')->where("state=0")->limit("0","4")->select();
    $school_list=Db::table('che_school')->where("state=0")->order('price asc')->limit("0","4")->select();
  }
  $this->assign('school_list',$school_list);
  $shoolservice=Db::name('class')->where("type = 'service'")->select();
  $shoolclass=Db::name('class')->where("type = 'class'")->select();
  $this->assign('shoolservice',$shoolservice);
  $this->assign('schoolclass',$shoolclass);
  return $this->fetch();
}
public function ajax_gold_school(){
  header('Content-Type:application/json');
  $post=input('post.');
  $page=$post['page'];
  $search=Cookie::get('search');
  $type=Cookie::get('type');
  $schoolclsass=Cookie::get('class');
  $schoolservice=Cookie::get('service');
  $time=Cookie::get('time');
  $price=Cookie::get('price');
  if($search and ($type or $schoolclsass or $schoolservice or $time or $price)){
    $type=Cookie::get('type');
    $schoolclsass=Cookie::get('class');
    $schoolservice=Cookie::get('service');
    $time=Cookie::get('time');
    $price=Cookie::get('price');
    $where="1 = 1 ";
    $where="1 = 1 ";
    if($type){
      $where=$where." and content like '%$type%'";
    }
    if($schoolclsass){
      $where=$where." and content like '%$schoolclsass%'";
    }
    if($schoolservice){
      $where=$where." and content like '%$schoolservice%'";
    }
    $school_can=Db::name('school_can')->where($where)->select();
    $where1="1=1";
    if($school_can){
      $in="(";
      foreach ($school_can as $key=>$value){
        if($key==0){
          $in=$in.$value['school_id'];
        }else{
          $in=$in.",".$value['school_id'];
        }
      }
      $in=$in.")";
      $where1="id in $in";
    }else{
      $where1="1=2";
    }
    $where3="1=1";
    if($time){
      if($time=="light") {
        $where3 = $where3 . " and baitian = 0";
      }elseif ($time=="dark"){
        $where3 = $where3 . " and baitian = 1";
      }
    }
    if($price){
      if($price=="<2000"){
        $where3=$where3." and  price <2000 ";
      }elseif($price=="2000-3000"){
        $where3=$where3." and  price >2000 and price < 3000";
      }elseif($price=="3000-4000"){
        $where3=$where3." and  price >3000 and price < 4000";
      }elseif($price==">4000"){
        $where3=$where3." and  price >4000";
      }
    }
    $school_list=Db::table('che_school')->where($where1)->where($where3)->where('state=0')->order('id desc')->limit($page-1,1)->select();
  }else{
    $school_list=Db::table('che_school')->where('state=0')->order('id desc')->limit($page-1,1)->select();
  }
  $txt='';
  if($school_list){
    foreach ($school_list as $key=>$value){
      $txt=$txt.'
      <div class="list-group-item">
        <a href="/index.php/index/index/school_detail/id/'.$value['id'].'">
          <img src="'.$value['spic'].'"/ style="width: 2.8rem;height: 2.8rem;">
          <p>
            '.$value['name'].'
          </p>
          <p>
            '.$value['address'].'
          </p>
        </a>
      </div>';
// $raw_success = array('code' => 1,"id"=>$value['id'], 'name' => $value['name'],'address'=>$value['address'],'pic'=>$value['spic']);
    }
  }else{
//$raw_success = array('code' => 2, 'msg' => '已经显示完啦');
    $txt='';
  }
  return $txt;
}
/**
* @return mixed
* 教练入口
*/
public function is_coach(){
  if(!Session::get('user')){
    $this->redirect('/index.php/index/index/ulogin/state/1');
    exit;
  }
  $usxx=Session::get('user');
  if(@$usxx['state'] == 0)
  {
    $this->redirect('/index.php/index/index/coach/state/1');
  }
  if(@$usxx['state'] != 1 and @$usxx['state'] != 4)
  {
    return '<script>alert("教练才可以访问本页面呦！"); window.location="/" </script>';
  }
  $this->assign('id',$usxx['id']);
  $result2=Db::table('che_userlogin')->where('id ='.$usxx['id'])->find();
  $this->assign('user',$result2);
  $result3=Db::table('che_coach')->where('ss_uid ='.$usxx['id'])->find();
  if(!$result3){
    return '<script>alert("请先进入后台添加自己的身份信息呦！"); window.location="/" </script>';
  }
  $this->assign('result3',$result3);
// echo $result3['cid'];
  $result=Db::table('che_pingjia')->where('cid ='.$result3['cid'])->select();
  $this->assign('pingjia',$result);
//var_dump($result);exit;
  $result4=Db::table('che_school')->where('id ='.$result3['school'])->find();
  $this->assign('result4',$result4);
  $paihang=Db::table('che_coach')->order('resources desc')->limit(10)->select();
  foreach($paihang as $key => $value)
  {
    $cxjx=Db::table('che_school')->where('id ='.$value['school'])->find();
    $paihang[$key]['jx_name']=$cxjx['name'];
  }
  $this->assign('paihang',$paihang);
  return $this->fetch();
}
/**
* @return mixed
* 学员入口
*/
public function is_student(){
  if(!Session::get('user')){
    $this->redirect('/index.php/index/index/ulogin');
    exit;
  }
  return $this->fetch();
}
/**
* @return mixed
* 学员入口
*/
public function is_school(){
  if(!Session::get('user')){
    $this->redirect('/index.php/index/index/ulogin/state/2');
    exit;
  }
  $usxx=Session::get('user');
  $usxx=Session::get('user');
  if(@$usxx['state'] == 0)
  {
    $this->redirect('/index.php/index/index/gold_school');
  }
  if(@$usxx['state'] != 2 and @$usxx['state'] != 4)
  {
    return '<script>alert("驾校才可以访问本页面呦！"); window.location="/"  </script>';
  }
  return $this->fetch();
}
/**
* @return mixed
* 教练详情
*/
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
  $service=Db::table('che_class')->where("type ='service'")->select();
  $img=Db::name('coach_img')->where("coach_id= $id and state=1")->select();
  foreach($service as $key1 => $value1){
    $txt1=']_service'.$value1['id'].']_';
    $result1=Db::query("select * from che_coach_can where coach_id = '$id' and  content like '%$txt1%' ESCAPE ']'");
    if(!$result1){ unset($service[$key1]);}
  }
  $this->assign('service',$service);
  $kemu=Db::table('che_class')->where("type ='kemu'")->select();
  foreach($kemu as $key2 => $value2){
    $txt2=']_kemu'.$value2['id'].']_';
    $result2=Db::query("select * from che_coach_can where coach_id = '$id' and  content like '%$txt2%' ESCAPE ']'");
    if(!$result2){ unset($kemu[$key2]);}
  }
  $this->assign('kemu',$kemu);
  $type=Db::table('che_class')->where("type ='type'")->select();
  foreach($type as $key3 => $value3){
    $txt3=']_type'.$value3['id'].']_';
    $result3=Db::query("select * from che_coach_can where coach_id = '$id' and  content like '%$txt3%' ESCAPE ']'");
    if(!$result3){ unset($type[$key3]);}
  }
  $this->assign('type',$type);
  $kaochang=Db::table('che_class')->where("type ='kaochang'")->select();
  foreach($kaochang as $key4 => $value4){
    $txt4=']_kaochang'.$value4['id'].']_';
    $result4=Db::query("select * from che_coach_can where coach_id = '$id' and  content like '%$txt4%' ESCAPE ']'");
//$result4=Db::table('che_coach_can')->where($map4)->find();
    if(!$result4){ unset($kaochang[$key4]);}
  }
  $this->assign('kaochang',$kaochang);
  $chexing=Db::table('che_class')->where("type ='chexing'")->select();
  foreach($chexing as $key5 => $value5){
    $txt5=']_chexing'.$value5['id'].']_';
    $result5=Db::query("select * from che_coach_can where coach_id = '$id' and  content like '%$txt5%' ESCAPE ']'");
    if(!$result5){ unset($chexing[$key5]);}
  }
  $this->assign('chexing',$chexing);
  $pingjia=Db::name('pingjia')->where(array("cid"=>$id))->order("id desc")->limit("0","2")->select();
  $coach_list=Db::table('che_coach')->alias("c")->field("c.*,s.name as school_name")->join("school s","s.id=c.school")->where(array("c.cid"=>$id))->find();
  $conut=count($pingjia);
// $aaatype=Cookie::get('type');
// $aaakemu=Cookie::get('kemu');
// $aaakaochang=Cookie::get('kaochang');
// $aaachexing=Cookie::get('chexing');
// $aaaservice=Cookie::get('service');
// $aaatype_id=explode('_type',$aaatype);
// $aaatype_id=str_replace('_', '', $aaatype_id);
// $type_re=Db::table('che_shooltype')->where('id',$aaatype_id)->find();
// $this->assign('type_re',$type_re);
// $aaaservice_id=explode('_service',$aaaservice);
// $aaaservice_id=str_replace('_', '', $aaaservice_id);
// $service_re=Db::table('che_shoolservice')->where('id',$aaaservice_id)->find();
// $this->assign('service',$service);
// $aaakemu_id=explode('_kemu',$aaakemu);
// $aaakemu_id=str_replace('_', '', $aaakemu_id);
// $kemu_re=Db::table('che_kemu')->where('id',$aaakemu_id)->find();
// $this->assign('type_re',$type_re);
// $aaatype_id=explode('_type',$aaatype);
// $aaatype_id=str_replace('_', '', $aaatype_id);
// $type_re=Db::table('che_shooltype')->where('id',$aaatype_id)->find();
// $this->assign('type_re',$type_re);
// $aaatype_id=explode('_type',$aaatype);
// $aaatype_id=str_replace('_', '', $aaatype_id);
// $type_re=Db::table('che_shooltype')->where('id',$aaatype_id)->find();
// $this->assign('type_re',$type_re);
  $this->assign('img',$img);
  $this->assign('count',$conut);
  $this->assign('denglu',$denglu);
  $this->assign('coach',$coach_list);
  $this->assign('pingjia',$pingjia);
  return $this->fetch('index/coach_detail');
}
/**
* @return mixed
* 驾校详情
*/
public function school_detail(){
  $request =request();
  $denglu=1;
  if(!Session::get('user')){
//跳转到后台登录界面
    $denglu=0;
  }else{
    $denglu=1;
  }
  $id=$request->param('id');
  $school_list=Db::table('che_school')->find($id);
//教练
  $imgz=explode(',',$school_list['img']);
  $this->assign('imgz',$imgz);
  $type=Db::table('che_class')->where("type ='type'")->select();
  foreach($type as $key => $value){
    $txt=']_type'.$value['id'].']_';
// $map['content']=array('like','%'.$txt.'%');
//$map['school_id']=$id;
    $result=Db::query("select * from che_school_can where school_id = '$id' and  content like '%$txt%' ESCAPE ']'");
//$result=Db::table('che_school_can')->where($map)->find();
    if(!$result){ unset($type[$key]);}
  }
  $img=Db::name('coach_img')->where("coach_id= $id and state=2")->select();
  $service=Db::table('che_class')->where("type ='service'")->select();
  foreach($service as $key1 => $value1){
    $txt1='_service'.$value1['id'].'_';
    $map1['content']=array('like','%'.$txt1.'%');
    $map1['school_id']=$id;
    $result1=Db::table('che_school_can')->where($map1)->find();
    if(!$result1){ unset($service[$key1]);}
  }
  $class=Db::table('che_class')->where("type ='class'")->select();
  foreach($class as $key2 => $value2){
    $txt2='_class'.$value2['id'].'_';
    $map2['content']=array('like','%'.$txt2.'%');
    $map2['school_id']=$id;
    $result2=Db::table('che_school_can')->where($map2)->find();
    if(!$result2){ unset($class[$key2]);}
  }
  $time=Db::table('che_class')->where("type ='time'")->select();
  foreach($time as $key5 => $value5){
    $txt5='_time'.$value5['id'].'_';
    $map5['content']=array('like','%'.$txt5.'%');
    $map5['school_id']=$id;
    $result5=Db::table('che_school_can')->where($map5)->find();
    if(!$result5){ unset($time[$key5]);}
  }
  $ss_jiaolian=Db::table('che_coach')->where('school ='.$id)->limit(4)->select();
  foreach($ss_jiaolian as $key4 => $value4)
  {
    $service2=Db::table('che_class')->where("type = 'service'")->select();
    foreach($service2 as $key3 => $value3){
      $txt3='_service'.$value3['id'].'_';
      $map3['content']=array('like','%'.$txt3.'%');
      $map3['coach_id']=$value4['cid'];
      $result3=Db::table('che_coach_can')->where($map3)->find();
      $ss_jiaolian[$key4]['child'][]=$value3;
    }
  }
//var_dump($ss_jiaolian);exit;
  $types=Cookie::get('type');
  $schoolclsass=Cookie::get('class');
//var_dump($schoolclsass);exit;
  $this->assign('ss_jiaolian',$ss_jiaolian);
  $this->assign('school',$school_list);
  $this->assign('type',$type);
  $this->assign('time',$time);
  $this->assign('class',$class);
  $this->assign('service',$service);
  $this->assign('types',$types);
  $this->assign('schoolclsass',$schoolclsass);
  $this->assign('denglu',$denglu);
  $this->assign('img',$img);
  return $this->fetch('index/school_detail');
}
//合作商
public function cooperate(){
  return $this->fetch();
}
//用户登录
public function  ulogin2(){//因为逻辑修改 暂时废弃 重写
  $post=input('post.');
  if($post){
    $state=$post['state'];
    if($state ==0){
      $phone=$post['phone'];
      $password=md5($post['password']);
//检测用户名和密码
      $row=Db::table("che_userlogin")->where("dianhua='{$phone}' and password = '{$password}'  and (state=0 or state=4)")->find();
      if($row){
        if($row['is_keyong'] == 0){
          echo "<script>alert('您的账号正在审核中，暂时无法登陆！');location.href='/index.php/Index/index/ulogin';</script>";exit;
        }
        $user_info=Db::table("che_userlogin")->where(array("id"=>$row['id']))->find();
        Session::set('user',$user_info);
        if($user_info['state']==0){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==1){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==2){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==4){
          $this->redirect('/index.php/Index/index/home');
        }
      }else{
        echo "<script>alert('用户名或手机号不匹配，登录失败');location.href='/index.php/Index/index/ulogin';</script>";exit;
      }
    }else if($state ==1){
      $phone=$post['phone'];
      $password=md5($post['password']);
//检测用户名和密码
      $row=Db::table("che_userlogin")->where("dianhua='{$phone}' and password = '{$password}'  and (state=1 or state=4)")->find();
      if($row){
        if($row['is_keyong'] == 0){
          echo "<script>alert('您的账号正在审核中，暂时无法登陆！');location.href='/index.php/Index/index/ulogin';</script>";exit;
        }
//把用户登录信息存储在session
        $user_info=Db::table("che_userlogin")->where(array("id"=>$row['id']))->find();
        Session::set('user',$user_info);
        if($user_info['state']==0){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==1){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==2){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==4){
          $this->redirect('/index.php/Index/index/home');
        }
      }else{
        echo "<script>alert('用户名或手机号不匹配，登录失败');location.href='/index.php/Index/index/ulogin';</script>";exit;
      }
    }else if($state ==2){
      $phone=$post['phone'];
      $password=md5($post['password']);
//检测用户名和密码
      $row=Db::table("che_userlogin")->where("dianhua='{$phone}' and password = '{$password}' and (state=2 or state=4)")->find();
      if($row){
        if($row['is_keyong'] == 0){
          echo "<script>alert('您的账号正在审核中，暂时无法登陆！');location.href='/index.php/Index/index/ulogin';</script>";exit;
        }
//把用户登录信息存储在session
        $user_info=Db::table("che_userlogin")->where(array("id"=>$row['id']))->find();
        Session::set('user',$user_info);
        if($user_info['state']==0){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==1){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==2){
          $this->redirect('/index.php/Index/index/home');
        }elseif($user_info['state']==4){
          $this->redirect('/index.php/Index/index/home');
        }
      }else{
        echo "<script>alert('用户名或手机号不匹配，登录失败');location.href='/index.php/Index/index/ulogin';</script>";exit;
      }
    }else{
      echo "<script>alert('用户名或手机号不匹配，登录失败');location.href='/index.php/Index/index/ulogin';</script>";exit;
    }
  }
  return $this->fetch();
}
public function  ulogin(){
  $post=input('post.');
  if($post){
    $phone=$post['phone'];
    $password=md5($post['password']);
    $row=Db::table("che_userlogin")->where("dianhua='{$phone}' and password = '{$password}'")->find();
    if($row){
      if($row['is_keyong'] == 0){
        echo "<script>alert('您的账号正在审核中，暂时无法登陆！');location.href='/index.php/Index/index/ulogin';</script>";exit;
      }
      $user_info=Db::table("che_userlogin")->where(array("id"=>$row['id']))->find();
      Session::set('user',$user_info);
      $this->redirect('/index.php/Index/index/home');
    }else{
      echo "<script>alert('用户名或手机号不匹配，登录失败');location.href='/index.php/Index/index/ulogin';</script>";exit;
    }
  }
  $state=input('param.state');
  if(!$state){ $state=0; }
  $this->assign('state',$state);
  return $this->fetch();
}
public  function  loginout(){
  session::clear();
  Cookie::clear();
  echo '<script>alert("恭喜您，退出成功！"); location.href="/index.php/index/index/home";</script>';
}
//注册
public function  register(){
//用户登录
  $post=input('post.');
  $id=input('param.id');
  if($post){
    $result=Db::table('che_smslog')->order('id desc')->where(array('mobile'=>$post['phone'],'status'=>"0"))->find();
    if(!$result){ return '<script>alert("请先获取验证码"); window.history.go(-1); </script>'; exit;}
    if((time() -$result['time']) >= 120){  return '<script>alert("验证码已失效，请重新获取！"); window.history.go(-1); </script>';   exit;}
    if($result['code'] != $post['code']){ return '<script>alert("验证码输入错误！"); window.history.go(-1); </script>';   exit;}
    $state=$post['state'];
    $dianhua=$post['phone'];
    $yz_user=Db::table('che_userlogin')->where("dianhua ='{$dianhua}'")->find();
    if($yz_user){  echo '<script>alert("您的手机已经注册过518账号，无法再次注册");window.location.href="/index.php/Index/index/ulogin"</script>';exit; }
    if($state ==0){
      if($id){
        $data['superior']=$id;
        $data['name']=$post['name'];
        $data['password']=md5($post['password']);
        $data['dianhua']=$post['phone'];
        $data['is_keyong']=1;
        $data['state']=0;
        $dianhua=$post['phone'];
        $yz_user=Db::table('che_userlogin')->where("dianhua ='{$dianhua}' and state=0")->find();
        if($yz_user){  echo '<script>alert("您的账号已经注册过普通会员，请更换手机或角色！");window.location.href="/index.php/Index/index/ulogin"</script>';exit; }
        $type=Db::table('che_userlogin')->Insert($data);
        if($type){
          $jiaolian=Db::table('che_coach')->where("ss_uid=$id")->setInc("resources",1);
          Db::table('che_smslog')->where(array('id'=>$result['id']))->update(array("status"=>1));
          echo '<script>alert("注册成功,赶快登陆吧！");window.location.href="/index.php/Index/index/ulogin"</script>';exit;
        }
      }else{
        $data['name']=$post['name'];
        $data['dianhua']=$post['phone'];
        $data['password']=md5($post['password']);
        $data['is_keyong']=1;
        $data['state']=0;
        $dianhua=$post['phone'];
        $yz_user=Db::table('che_userlogin')->where("dianhua ='{$dianhua}' and state=0")->find();
        if($yz_user){  echo '<script>alert("您的账号已经注册过普通会员，请更换手机或角色！");window.location.href="/index.php/Index/index/ulogin"</script>';exit; }
        $type=Db::table('che_userlogin')->Insert($data);if($type){
          Db::table('che_smslog')->where(array('id'=>$result['id']))->update(array("status"=>1));
          echo '<script>alert("注册成功,赶快登陆吧！");window.location.href="/index.php/Index/index/ulogin"</script>';exit;
        }
      }
    }else if($state ==1){
      $data['name']=$post['name'];
      $data['dianhua']=$post['phone'];
      $data['password']=md5($post['password']);
      $data['is_keyong']=0;
      $data['state']=1;
      $dianhua=$post['phone'];
      $yz_user=Db::table('che_userlogin')->where("dianhua ='{$dianhua}' and state=1")->find();
      if($yz_user){  echo '<script>alert("您的账号已经注册过教练会员，请更换手机或角色！");window.location.href="/index.php/Index/index/ulogin"</script>';exit; }
      $type=Db::table('che_userlogin')->Insert($data);
      echo '<script>alert("客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/Index/index/ulogin"</script>';exit;
    }else{
      $data['name']=$post['name'];
      $data['dianhua']=$post['phone'];
      $data['password']=md5($post['password']);
      $data['is_keyong']=0;
      $data['state']=2;
      $dianhua=$post['phone'];
      $yz_user=Db::table('che_userlogin')->where("dianhua ='{$dianhua}' and state=2")->find();
      if($yz_user){  echo '<script>alert("您的账号已经注册过驾校会员，请更换手机或角色！");window.location.href="/index.php/Index/index/ulogin"</script>';exit; }
      $type=Db::table('che_userlogin')->Insert($data);
      echo '<script>alert("客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/Index/index/ulogin"</script>';exit;
    }
  }
  $state=input('param.state');
  $this->assign('state',$state);
  return $this->fetch();
}
//注册用户判断 register_name
public function  register_name(){
  $request=request();
  $name=$request->post('name');
//获取数据库用户名
  $arr=Db::table("che_userlogin")->column('name');
//判断是否在数组里
  if(in_array($name,$arr)){
    echo 1;
  }else{
    echo 0;
  }
}
//检测手机
public function register_phone(){
  $request=request();
//获取手机号码
  $phone= $request->post('p');
//获取数据库手机号
  $arr=Db::table('che_userlogin')->column('dianhua');
  if(in_array($phone,$arr)){
    echo 1;
  }else{
    echo 0;
  }
}
// student_service
//学生服务
public function student_service(){
  return $this->fetch();
}
//group_service
public function group_service(){
  $post=input('post.');
  if($post){
//var_dump($post); exit;
    $post['add_time']=time();
    $post['time']=$post['start_time'].'到'.$post['end_time'];
    $teamservice=Db::name('teamservice')->Insert($post);
    if($teamservice){
      $hotline=Db::name('hotline')->order("line_id DESC")->find();
      $mobile=$hotline['mobile'];
      file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的团队预约信息，请注意登录后台查看&sendTime=&extno=');
      echo '<script>alert("预约客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/index/index/group_service"</script>';exit;
    }
  }
  return $this->fetch();
}
//服务保障
public function security_service(){
  return $this->fetch();
}
//教练预约
public function make_school(){
  header('Content-type:text/json;charset=utf-8');
  $user=Session::get('user');
  $uid=$user['id'];
//获取手机号码
  $post=input('post.');
  $sid=$post['sid'];
  if(!Session::get('user')){
    $arr=array('code'=>2,'msg' => '/index.php/index/index/ulogin.html'); return json_encode($arr); exit;
  }
  $post['uid']=$user['id'];
  $post['uname']=$user['name'];
  $post['phone']=$user['dianhua'];
  $post['state']=0;
//$result=Db::name('bespeak')->Insert($post);
  $result=Db::name('bespeak')->insertGetId($post);
  if($result){
    $arr=array('code'=>1,'msg' => '预约成功，我们将在两个工作日内联系您，请保持手机畅通'); return json_encode($arr); exit;
  }else{
    $arr=array('code'=>1,'msg' => '预约失败，请联系管理员进行处理'); return json_encode($arr); exit;
  }
}
//教练预约
public function make_coach(){
  header('Content-type:text/json;charset=utf-8');
  $user=Session::get('user');
  $uid=$user['id'];
  $post=input('post.');
  $cid=$post['cid'];
  if(!Session::get('user')){
    $arr=array('code'=>2,'msg' => '/index.php/index/index/ulogin.html'); return json_encode($arr); exit;
  }
  $post['uid']=$user['id'];
  $post['uname']=$user['name'];
  $post['phone']=$user['dianhua'];
  $post['state']=1;
  $result=Db::name('bespeak')->insertGetId($post);
  if($result){
    $arr=array('code'=>1,'msg' => '预约成功，我们将在两个工作日内联系您，请保持手机畅通'); return json_encode($arr); exit;
  }else{
    $arr=array('code'=>1,'msg' => '预约失败，请联系管理员进行处理'); return json_encode($arr); exit;
  }
}
//预约教练
public function docoach(){
//$user_info=Session::get('user_info');
// $uid=$user_info['id'];
  $post=input('post.');
//cid=$post['cid'];
  $state=$post['state'];
//判断支付发方式
  if($state=='0'){
//面对面
    echo '<script>alert("您选择了面对面,客服人员会在两个工作日内处理，请您保持手机畅通");window.location.href="/index.php/Index/index/home"</script>';exit;
  }elseif($state=='1'){
    echo '<script>alert("您选择选学后付，客服人员会在两个工作日内处理，请您保持手机畅通");window.location.href="/index.php/Index/index/home"</script>';exit;
  }elseif($state=='2'){
    echo '<script>alert("在线支付跳转中....");window.location.href="/index.php/Index/index/home"</script>';exit;
  }else{
    echo '<script>alert("您选择了面对面,客服人员会在两个工作日内处理，请您保持手机畅通");window.location.href="/index.php/Index/index/home"</script>';exit;
  }
}
//新闻列表
public function newslist(){
  $news=Db::table('che_news')->select();
  $this->assign('news',$news);
  return $this->fetch();
}
//新闻详情
public function newsdetail(){
  $request=request();
  $id=$request->param('id');
  $news=Db::table('che_news')->where("id=$id")->find();
  $this->assign('news',$news);
  return $this->fetch();
}
//留言
public function liuyan(){
//获取留言
  $request=request();
  $post=input('post.');
  $user_info=Session::get('user');
  $post['uid']=$user_info['id'];
  $post['uname']=$user_info['name'];
  $post['mobile']=$user_info['dianhua'];
  $post['time']=date("Y-m-d",time());
//$type=Db::table('che_liuyan')->insert(array('uid'=>$uid,'content'=>$content,'time'=>$time));
  $type=Db::table('che_liuyan')->insert($post);
  if($type){
    $hotline=Db::name('hotline')->order("line_id DESC")->find();
    $mobile=$hotline['mobile'];
    file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的留言信息，请注意登录后台查看&sendTime=&extno=');
    echo '<script>alert("留言成功,客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/index/index/home"</script>';exit;
  }else{
    echo '<script>alert("留言失败");window.location.href="/index.php/index/index/home"</script>';exit;
  }
}
//用户详情信息
public function userinfo(){
  $request=request();
  $post=input('post.');
  $user_info=Session::get('user');
  $post['uid']=$user_info['id'];
  $post['uname']=$user_info['name'];
  $post['time']=date("Y-m-d",time());
  $time=date("Y-m-d",time());
  $type=Db::table('che_user_address')->insert($post);
  if($type){
    $hotline=Db::name('hotline')->order("line_id DESC")->find();
    $mobile=$hotline['mobile'];
    file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的详情信息，请注意登录后台查看&sendTime=&extno=');
    echo '<script>alert("留言成功,客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/index/index/home"</script>';exit;
  }else{
    echo '<script>alert("提交失败");window.location.href="/index.php/index/index/home"</script>';exit;
  }
}
//移动
public function applicate01(){
  return $this->fetch();
}
public function online_lipei(){
  if(!Session::get('user')){
//跳转到后台登录界面
  }
  $denglu=1;
  $request=request();
  $post=input('post.');
  $user_info=Session::get('user');
  if(!$user_info){
    $this->redirect(url('ulogin'));
  }
  
  if($user_info['state'] == 2)
  {
    $jia=array('149','298');
  }else{
     $jia=array('200','400');
  }
  $this->assign('jia',$jia);

  if($post){


    $type=$request->param('type');
    $post['uid']=$user_info['id'];
    $post['uname']=$user_info['name'];
    $post['time']=date("Y-m-d",time());
    $info=Db::name('insure')->insertGetId($post);

   if(strpos($type,'C') !== false)
   {
    $price=$jia['0'];
   }else{
    $price=$jia['1'];
   }
    $data['time']=time();
    $data['pay_state']=0;
    $data['type']=4;  
    $data['price']=$price;  
    $data['order_sn']=date('YmdHis').rand(1000,9999);
    $data['uid']=$user_info['id'];
    $data['content']=$info;
    $result=Db::table('che_order')->insertGetId($data);

    $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf4152152ada16af9&redirect_uri=http://518xueche.com/index.php/index/Pay/wxlogin?zifu='.$price.'|'.$data['order_sn'].'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect');

    // if($info){
    //   $hotline=Db::name('hotline')->order("line_id DESC")->find();
    //   $mobile=$hotline['mobile'];
    //   file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的预约投保信息，请注意登录后台查看&sendTime=&extno=');
    //   echo '<script>alert("预约成功,客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/index/index/online_lipei"</script>';exit;
    // }else{
    //   echo '<script>alert("提交失败");window.location.href="/index.php/index/index/online_lipei"</script>';exit;
    // }
  }
  $this->assign('denglu',$denglu);
  return $this->fetch();
}
public function yuyue_safe(){
  if(!Session::get('user')){
//跳转到后台登录界面
    $denglu=0;
  }else{
    $denglu=1;
  }
  $request=request();
  $post=input('post.');
  $user_info=Session::get('user');
  if($post){
    $post['uid']=$user_info['id'];
    $post['uname']=$user_info['name'];
    $post['phone']=$user_info['dianhua'];
    $post['time']=date("Y-m-d",time());
    $info=Db::name('lipei')->Insert($post);
    if($info){
      $hotline=Db::name('hotline')->order("line_id DESC")->find();
      $mobile=$hotline['mobile'];
      file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$mobile.'&content=【518学车网】尊敬的客服您好，网站有新的预约理赔信息，请注意登录后台查看&sendTime=&extno=');
      echo '<script>alert("理赔申请成功,客服人员会在两个工作日内处理，联系您请保持手机畅通");window.location.href="/index.php/index/index/yuyue_safe"</script>';exit;
    }else{
      echo '<script>alert("提交失败");window.location.href="/index.php/index/index/yuyue_safe"</script>';exit;
    }
  }
  $this->assign('denglu',$denglu);
  return $this->fetch();
}
public function xianxue_houfu(){
  return $this->fetch();
}
public function kaoshi_wuyou(){
  return $this->fetch();
}
}