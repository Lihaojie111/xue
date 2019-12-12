<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Wei extends Controller
{
       private  $appid;//私有的自己用
       private  $appsecret;
       private  $token;

      //实例化触发构造函数
       public function __construct(){
           $this->appid = 'wxd9525f8caeb1a0eb';
           $this->appsecret = '2bb474a04ca0257b9d5fac74d576cc8f';
           $this->token ='chen';
       }
    
    //封装request curl方法
    public function request($url,$https=true,$method='get',$data=null){
        //1.初始化url
        $ch = curl_init($url);
        //2.设置请求参数
        //把数据以文件流形式保存，而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //支持http和https协议
        //https协议  ssl证书
        //绕过证书验证
        if($https === true){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //支持post请求
        if($method === 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            //发送的post数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }
        //3.发送请求
        $content = curl_exec($ch);
        //4.关闭请求
        curl_close($ch);
        return $content;
    }
    
    //封装请求方法
    public function newrequest($url,$https=true,$method='get',$data=null){
        //协议区分 http https(ssl加密)
        //请求方式区分  get(url传值)  post(对应传输数据)
        //使用curl封装
        //1.curl初识化
        $ch = curl_init($url);
        //2.设置相关选项参数
        //接收到的数据以文件流的形式保存,不进行输出
        //设置基本上都是开关状态  true和false
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //支持https请求
        if($https === true){
            //绕过ssl验证
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        //支持post请求
        if($method === 'post'){
            //开启post请求
            curl_setopt($ch,CURLOPT_POST,true);
            //发送的post数据
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        //3.发送请求
        $content = curl_exec($ch);
        //4.关闭请求链接
        curl_close($ch);
        //返回给调用者
        return $content;
    }
    

    
//获取access_token
  public function getAccessToken(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
    //2.请求方式
    //3.发送请求
    $content = $this->request($url);
    //4.处理返回值
      //dump($content);exit;
    //返回值为json
    $content = json_decode($content);
    //dump($content);
      //输出access_token
    return  "$content->access_token";
  }

 //模板消息/一键报修,创建订单成功提醒
 public  function  baoxiu(){
  $openid="oBsNOwq7s6logzAvIerNLQCqiiek";
  $id    ="1";
  $url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->getAccessToken();

        $data = array(
        "touser"=>$openid, //推送给谁,openid
        "template_id"=>"eq6lRAGRvg3dT2ac-GozcAg4FkxDzrFm8me86DcQjQI", //微信后台的模板信息id
        "url"=>"http://ad.yz360.net/", //下面为预约看房模板示例
        "data"=> array(
            "first" => array(
                "value"=>"恭喜您已经报修成功!正在为您派单，请耐心等待!",
                "color"=>"#173177"
            ),
            "orderno"=>array(
                "value"=>$id, //传的变量
                "color"=>"#173177"
            ),
            "remark"=> array(
                "value"=>date('Y-m-d H:i:s'),
                "color"=>"#173177"
            ),
        )
    );
    $data = json_encode($data);
     //3.发送请求
      $content = $this->request($url,true,'post',$data);
      //4.处理返回值
      $content = json_decode($content);
      
      //var_dump($content);
  
 }
  




         //创建自定义菜单
  public function createMenu(){
    //1.url
    //注意粘贴链接去掉空格
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken();
    //2.请求
    $data = '{
    "button": [
        {
            "name": "华哥定制", 
            "sub_button": [
                {
                      "type":"view",
                   "name":"如花官网",
                   "url":"http://www.ruhuage.com"
                }, 
                {
                      "type":"view",
                   "name":"定制商城",
                   "url":"http://rhg.yz360.net/index.php/weixin/wei/getcode"
                }
            ]
        }, 
        {
            "name": "用户中心", 
            "sub_button": [
                       
                 {
                    "type": "view", 
                    "name": "个人中心", 
                    "url": "http://rhg.yz360.net/index.php/weixin/wei/getcode" 
                   
                }
            ]
        }, 
        {
            "name": "我的论坛", 
            "sub_button": [
                {
                       "type":"view",
                   "name":"论坛",
                   "url":"http://rhg.yz360.net/index.php/Home/Forum/index.html"
                 },               
                 {
                        "type":"view",
                   "name":"最新优惠",
                   "url":"http://rhg.yz360.net/index.php/Home/Index/youhui.html"
                }
            ]
        } 
    ]
}';
    //3.发送请求
    $content = $this->request($url,true,'post',$data);
    //4.处理返回值
    $content = json_decode($content);
    //判断是否创建成功
    if($content->errmsg == 'ok'){
      echo '创建菜单成功!';
    }else{
      echo '创建菜单失败！'.'<br />';
      echo '错误代码'.$content->errcode.'<br />';
      echo '错误信息'.$content->errmsg;
    }
  }

   //查询菜单程序
  public function showMenu(){
      //1.url
      $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->getAccessToken();
       //2.请求方式
       //3.发送请求
      $content = $this->request($url);
      //4.处理返回值
      var_dump($content);
   } 

    //用户微信登录
    public function getcode(){
            //1获取code
        $redirect_uri =urlencode("https://ad.yz360.net/index.php/Home/wei/getuseropenid");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header('location:'.$url);
      
    }




   public function getcode3(){
            //1获取code
        $redirect_uri =urlencode("https://ad.yz360.net/index.php/Home/wei/getuseropenid3");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header('location:'.$url);
      
    }

    public function getuseropenid(){
         //2获取网页授权自己的access_token
            $code = $_GET['code'];
            $url ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code ';
                 $access_token1=$this->request($url);
                 //dump($code );
                 $access_token1 = json_decode($access_token1);//转化为对象
                 //dump( $access_token1);exit;
                
                 $access_token11=  $access_token1->access_token;
                 $openid = $access_token1->openid;
                 session('openid',$openid);
         //3拉取用户openid详细信息
                 $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token11.'&openid='.$openid.'&lang=zh_CN';

                $xinxi=$this->request($url);
                 //转数据为对象格式
                $xinxi = json_decode($xinxi);

                //dump($xinxi);exit;
                    $openid1 = $xinxi->openid;     //用户的唯一标识
                    $nickname = $xinxi->nickname;   //用户昵称
                    $headimgurl= $xinxi->headimgurl;  //用户头像，
                    $sex= $xinxi->sex;//用户性别
                      //var_dump($xinxi);exit;
                      
                      
                //查询数据此用户是否存下
                  $open = M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                           
                   
                    if($open){
                            //存在的,ID
                          $uss=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                          $is_teacher=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->getField("is_teacher");
                           
                         if($is_teacher==0){
                           
                          session('user',$uss);

                         
                          $url = urldecode($_GET['url']);
                          if($url)
                          {
                          $user  = session('user');
                          header('location:'.$url.$user['user_id']);
                          }else{
                          header('location:https://ad.yz360.net/index.php/Home/User/index');
                          }
                         
                         }
                         
                    }else{
                        $data['openid']=$openid1;
                      $data['nickname']=$nickname;
                      $data['name']=$nickname;
                      $data['image']=$headimgurl;
                      $data['sex']=$sex;
                        $data['quan_money']='50';
                      $data['is_teacher']=0;
                      $data['reg_time']=time();

                      if($openid1)
                      {
                      $info=M('users')->add($data);
                      if($info){
                        $info1=M('users')->where(array('user_id'=>$info))->find();
                        session('user',$info1);


                          $url = urldecode($_GET['url']);
                          if($url)
                          {
                          $user  = session('user');
                          header('location:'.$url.$user['user_id']);
                          }else{
                          header('location:https://ad.yz360.net/index.php/Home/User/index');
                          }
                             
                      }
                    }
                       //header("Content-type: text/html; charset=utf-8");
                       
                         //echo "<script>alert('请先用手机号登录,然后在个人中心进行微信绑定')</script>
                         
                             //<script> window.location.href='https://ad.yz360.net/Home/Login/user_login'</script>";
                       

                    }

                //dump($xinxi);exit;
         
    }
    




        public function getuseropenid3(){
         //2获取网页授权自己的access_token
            $code = $_GET['code'];
            $url ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code ';
                 $access_token1=$this->request($url);
                 //dump($code );
                 $access_token1 = json_decode($access_token1);//转化为对象
                 //dump( $access_token1);exit;
                
                 $access_token11=  $access_token1->access_token;
                 $openid = $access_token1->openid;
                 session('openid',$openid);
         //3拉取用户openid详细信息
           $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token11.'&openid='.$openid.'&lang=zh_CN';

                $xinxi=$this->request($url);
                 //转数据为对象格式
                $xinxi = json_decode($xinxi);

                //dump($xinxi);exit;
                    $openid1 = $xinxi->openid;     //用户的唯一标识
                    $nickname = $xinxi->nickname;   //用户昵称
                    $headimgurl= $xinxi->headimgurl;  //用户头像，
                    $sex= $xinxi->sex;//用户性别
                      //var_dump($xinxi);exit;
                      
                      
                //查询数据此用户是否存下
                  $open = M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                           
                     
                    if($open){
                            //存在的,ID
                          $uss=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->find();
                          $is_teacher=M('users')->where(array('openid'=>$openid1,'is_teacher'=>0))->getField("is_teacher");
                           
                         if($is_teacher==0){
                           
                          session('user',$uss);
                          header('location:https://ad.yz360.net/index.php/Home/User/index');
                         
                         }
                         
                    }else{


                        $data['openid']=$openid1;
                      $data['nickname']=$nickname;
                      $data['name']=$nickname;
                      $data['image']=$headimgurl;
                      $data['sex']=$sex;
                        $data['quan_money']='50';
                      $data['is_teacher']=0;
                      $data['is_shangjia']=1;
                      $data['reg_time']=time();

                         if($openid1)
                      {
                      $info=M('users')->add($data);
                      if($info){
                        $info1=M('users')->where(array('user_id'=>$info))->find();
                        session('user',$info1);
                            header('location:https://ad.yz360.net/index.php/Home/User/index');
                      }
                       //header("Content-type: text/html; charset=utf-8");
                       
                         //echo "<script>alert('请先用手机号登录,然后在个人中心进行微信绑定')</script>
                         
                             //<script> window.location.href='https://ad.yz360.net/Home/Login/user_login'</script>";
                       }

                    }

                //dump($xinxi);exit;
         
    }
    
    //师傅登录
    public function getcode2(){
            //1获取code
        $redirect_uri =urlencode("https://ad.yz360.net/index.php/Home/wei/getuseropenid2");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header('location:'.$url);
      
    }

    public function getuseropenid2(){
         //2获取网页授权自己的access_token
            $code = $_GET['code'];
            $url ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code ';
            $access_token1=$this->request($url);

            $access_token1 = json_decode($access_token1);//转化为对象
                //dump( $access_token1);exit;
            $access_token11=  $access_token1->access_token;
            $openid = $access_token1->openid;
            //3拉取用户openid详细信息
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token11.'&openid='.$openid.'&lang=zh_CN';

            $xinxi=$this->request($url);
             //转数据为对象格式
            $xinxi = json_decode($xinxi);


            $openid1 = $xinxi->openid;     //用户的唯一标识
            $nickname = $xinxi->nickname;   //用户昵称
            $headimgurl= $xinxi->headimgurl;  //用户头像，
              //var_dump($xinxi);exit;
                  
            //查询数据此用户是否存下
            $open = M('users')->where(array('openid'=>$openid1,'is_teacher'=>1))->find();
            if(!$open){
                $data['openid']=$openid1;
                $data['name']=$nickname;
                $data['image']=$headimgurl;
                $data['is_pass']=0;
                $data['sex']=$sex;
                $data['reg_time']=time();
                $data['is_teacher']=1;
              if($openid1)
               {
                $info=M('users')->add($data);
                //dump($info);exit;
                $info1=M('users')->where(array('user_id'=>$info))->find();
                session('user',$info1);
                if($info){
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('请前往认证');</script>
                      <script> window.location.href='https://ad.yz360.net/Home/Teacher/identity?user_id=".$info1['user_id']."';</script>";
                }
                exit();

              }
            }
            else{
                $is_pass=M('users')->where(array('openid'=>$openid1,'is_teacher'=>1))->getFieldByOpenid($openid1,'is_pass');
                // dump($is_pass);die;
                if($is_pass==0&&$open['carte_dos_img']==""){
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('还没有进行身份认证,请先填写认证信息');</script>
                      <script> window.location.href='https://ad.yz360.net/Home/Teacher/identity?user_id=".$open['user_id']."';</script>";
                      exit();
                }
                if($is_pass==0&&$open['carte_dos_img']!=""){
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('微信账号还没有审核,请耐心等待');</script>
                      <script> window.location.href='https://ad.yz360.net/Home/Index/index';</script>";
                      exit();
                }
                if($is_pass==1){
                      //存在的,ID
                      // $uss=M('users')->where(array('openid'=>$openid1,'is_teacher'=>1,'is_pass'=>1))->find();
                      // $is_teacher=M('users')->where(array('openid'=>$openid1,'is_teacher'=>1))->getField("is_teacher");    
                      session('user',$open);


                          $url = urldecode($_GET['url']);
                          if($url)
                          {
                          $user  = session('user');
                          header('location:'.$url.$user['user_id']);
                          }else{
                         header('location:https://ad.yz360.net/index.php/Home/Teacher/index');  
                          }


                     
                }
                if($is_pass==2){
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('微信账号没有审核通过,详细情况请联系我们的客服');</script>
                      <script> window.location.href='https://ad.yz360.net/Home/Index/index';</script>";
                       exit();
                }
                if($is_pass==3){
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('改微信账号因违反操作已被拉黑或停用,如需继续使用请联系我们的客服');</script>
                      <script> window.location.href='https://ad.yz360.net/Home/Index/index';</script>";
                       exit();
                }
            } 
            
    }




    
    
     //微信绑定代码///////////////
    public function getcode1(){
            //1获取code
        $redirect_uri =urlencode("https://ad.yz360.net/index.php/Home/wei/getuseropenid1");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header('location:'.$url);
      
    }

    public function getuseropenid1(){
         //2获取网页授权自己的access_token
            $code = $_GET['code'];
            $url ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code ';
                 $access_token1=$this->request($url);

                 $access_token1 = json_decode($access_token1);//转化为对象snsapi_userinfo
                    //dump( $access_token1);exit;
                 $access_token11=  $access_token1->access_token;
                 $openid = $access_token1->openid;
         //3拉取用户openid详细信息
           $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token11.'&openid='.$openid.'&lang=zh_CN';

                $xinxi=$this->request($url);
                 //转数据为对象格式
                $xinxi = json_decode($xinxi);


                    $openid1 = $xinxi->openid;     //用户的唯一标识
                    $nickname = $xinxi->nickname;   //用户昵称
                    $headimgurl= $xinxi->headimgurl;  //用户头像，
                     // var_dump($xinxi);exit;
                //查询数据此用户是否存下
                    $user=session('user');
                  $user_id=$user['user_id'];
                  
                   
                  $chen=M('users')->where(array('user_id'=>$user_id))->save(array('openid'=>$openid1));
                  $is_teacher=M('users')->where(array('user_id'=>$user_id))->getField("is_teacher");
                  //var_dump($cid);exit;
                 if($is_teacher==0){
                  
                   header("Content-type: text/html; charset=utf-8");
                       
                         echo "<script>alert('亲,你微信已绑定成功')</script>
                         
                             <script> window.location.href='https://ad.yz360.net/index.php/Home/User/index'</script>";
                   //header('location:http://ad.yz360.net/index.php/Home/User/index');
                   
                   
                 }else if($is_teacher==1){
                  
                      header("Content-type: text/html; charset=utf-8");
                       
                         echo "<script>alert('亲,你微信已绑定成功')</script>
                         
                             <script> window.location.href='https://ad.yz360.net/index.php/Home/Teacher/index'</script>";
                    
                 }
                 

         
    }

//////////////////////////////////////

  
     //获取所有关注公众号用户openID列表
  public function getUserList(){
    //1.url
    $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->getAccessToken();
    //2.请求方式
    //3.发送请求
    $content = $this->request($url);
    //4.处理返回值
    //{"total":2,"count":2,"data":{"openid":["","OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
    $content = json_decode($content);   //对象格式
    // var_dump($content);
    //把openID逐行输出
    $openidList = $content->data->openid;
    echo '关注数量为:'.$content->total.'<br />';
    echo '本次拉取数量:'.$content->count.'<br />';
    //遍历数组，输出每一条用户的openid信息
    foreach ($openidList as $key => $value) {
      echo ($key+1).'###<a href="http://localhost/wechat54/do.php?openid='.$value.'">'.$value.'</a><br />';
    }
  }


          //通过openID列表获取用户基本信息
          public function getUserInfo($openid){
            //1.url
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
            //2.请求方式
            //3.发送请求
            $content = $this->request($url);
            //转数据为对象格式
            $content = json_decode($content);

            //dump($content);exit;
              //返回查询到的信息
              return  $content;


            // //4.处理返回值
            // switch ($content->sex) {
            //   case '1':
            //     $sex = '男';
            //     break;
            //   case '2':
            //     $sex = '女';
            //     break;
            //   default:
            //     $sex = '未知';
            //     break;
            // }
            // echo '昵称:'.$content->nickname.'<br />';
            // echo '性别:'.$sex.'<br />';
            // echo '省份:'.$content->province.'<br />';
            // echo '<img src="'.$content->headimgurl.'" />';
          } 



     


     //生成临时7天二维码接口
     
      function getticket(){
              //取出生成二维码人的id
             $cid = session('cid');
           // $cid = '1';
           //1获取ticket票据
          $url ="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->getAccessToken();
          $data ='{"expire_seconds": 604800,"action_name": "QR_SCENE", "action_info": {"scene": {"scene_id":'.$cid.'}}}';
          $content = $this->request($url,true,'post',$data);
          //json转化为对象
          $content = json_decode($content); 
          $ticket= urlencode($content->ticket);
          return  $ticket;
      }

      function getqrcode(){
            header('content-type:text/html;charset=utf-8');
           //2通过ticket获取二维码gQGT8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyLWV6TTVvQVBlZDAxMDAwMGcwM1QAAgR21F1ZAwQAAAAA
            $url ="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$this->getticket();
            //$content = $this->request($url);
          
            echo "<div   style='text-align: center;padding-top:300px' ><img  alt='我的二维码'  style='width:800px;height:800px' src='".$url."'></div>";
           
      }

////////////////////开始/////////////////验证服务器
///
///url地址
   public  function index(){
              
             
              //判断是验证还是消息处理的
              if($_GET["echostr"]){
                //调用验证方法
                $this->valid();
              }else{
                //调用消息管理方法
                $this->responseMsg();
              }


      }
        //验证方法
      public function valid()
        {
            $echoStr = $_GET["echostr"];

            //valid signature , option
            if($this->checkSignature()){
              echo $echoStr;
              exit;
            }
        }
        //消息管理方法
    public function responseMsg()
         { 
            //get post data, May be due to the different environments
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

                //extract post data
            if (!empty($postStr)){
                        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                           the best way is to check the validity of xml by yourself */
                        libxml_disable_entity_loader(true);
                            //把xml数据转化为对象
                        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                         //判断接收到的消息类型,进行对应的方法处理
                         switch ($postObj->MsgType) {
                              case 'text':
                                $this->doText($postObj);   //文本消息处理
                                break;
                              case 'image':
                                $this->doImg($postObj);   //图片消息处理
                                break;
                              case 'location':
                                $this->doLocation($postObj);    //地理位置消息处理
                                break;
                              case 'event':
                                $this->doEvent($postObj);  //事件消息的处理
                                break;
                              default:
                                # code...
                                break;
                          };

                //         $fromUsername = $postObj->FromUserName;
                //         $toUsername = $postObj->ToUserName;
                //         $keyword = trim($postObj->Content);
                //         $time = time();
                //         $textTpl = "<xml>
                //       <ToUserName><![CDATA[%s]]></ToUserName>
                //       <FromUserName><![CDATA[%s]]></FromUserName>
                //       <CreateTime>%s</CreateTime>
                //       <MsgType><![CDATA[%s]]></MsgType>
                //       <Content><![CDATA[%s]]></Content>
                //       <FuncFlag>0</FuncFlag>
                //       </xml>";
                // if(!empty( $keyword ))
                //         {
                //           $msgType = "text";
                //           $contentStr = "如花哥欢迎你到来!正在忙碌中,不能回复你,请亲自己看";
                //           $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                //           echo $resultStr;
                //         }
                    }
               
    }
      //校验签名的方法
  public function checkSignature(){
    // you must define TOKEN by yourself
    if (!defined("TOKEN")) {
        //throw new Exception('TOKEN is not defined!');
      }

            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
            // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
          return true;
        }else{
          return false;
        }
  }









      
    }