<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use app\admin\logic\MyLogic;

/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */

class Index extends Base
{

  public function _initialize() {
        $user_info=Session::get('user_info');
        //验证登录
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        if($user_info){
            //判断是否拥有访问权限，无权限则退出让重新登录
            if($user_info['type']==2){
                $this->redirect('/index.php/Admin/cityagent/index');
            }elseif($user_info['type']==3){
                $this->redirect('/index.php/Admin/distagent/index');
            }
            //加载通知
            $notice=Db::name('notice')->where(array("status"=>0))->group('content')->select();
            $notice_count=Db::name('notice')->where(array("status"=>0))->group('content')->count();
            $insure = Db::name('insure i')
            ->join('__ORDER__ o', 'i.id = o.content', 'LEFT')
            ->where('o.pay_state=1 and i.state = 0')
            ->count();
            $this->assign('insure',$insure);
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
    

    public function user_info(){
      $user_list=Db::table('che_userlogin')->order('id desc')->paginate(8);
      $this->assign('user_list',$user_list);
      return $this->fetch();
    }
    

    public function order(){
      $result=Db::table('che_order a')->join('che_userlogin u','u.id = a.uid','LEFT')->order('a.id desc')->paginate(10);
      $this->assign('result',$result);
      return $this->fetch();
    }

    public function change_user()
    {
     $get=input('param.');
     $data['is_keyong'] = $get['is_keyong'];
     Db::table('che_userlogin')->where('id',$get['id'])->update($data);
     $result=Db::table('che_userlogin')->where('id',$get['id'])->find();

     if(($result['state'] == 1 or $result['state'] == 2) and $get['is_keyong']==1)
     {
             
         file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$result['dianhua'].'&content=【518学车网】尊敬的用户您好，您的账号已经申请通过，请登陆518学车网使用相关服务！&sendTime=&extno=');

         $cxad=Db::table('che_user')->where("username ='{$result['dianhua']}'")->find();

         if(!$cxad and $result['state'] == 1){
         $data1['username']=$result['dianhua'];
         $data1['password']=$result['password'];
         $uuid=Db::table('che_user')->strict(true)->insertGetId($data1);
         $data2['uid']=$uuid;
         $data2['name']=$result['name'];
         $data2['type']=4;
         $data2['mobile']=$result['dianhua'];
         Db::table('che_user_info')->insert($data2);
          }


        if(!$cxad and $result['state'] == 2){
         $data1['username']=$result['dianhua'];
         $data1['password']=$result['password'];
         $uuid=Db::table('che_user')->strict(true)->insertGetId($data1);
         $data2['uid']=$uuid;
         $data2['name']=$result['name'];
         $data2['type']=5;
         $data2['mobile']=$result['dianhua'];
         Db::table('che_user_info')->insert($data2);
          }

     }
     $this->success("操作成功");

   }

    public function index()
    {
        $user_info=Session::get('user_info');
        $uid=$user_info['info_id'];
      	$remind_stock=$user_info['remind_stock'];
        $date=date("Y年m月d号",time()) ;
        $this->assign('date',$date);
      	// 驾校审核
         $s=Db::table('che_school')->where("state=1")->select();
      	$school=count( $s);
      	// 教练审核
         $c=Db::table('che_coach')->where("state=1")->select();
      		$coach=count( $c);
      //驾校套餐审核
       $jiao=Db::table('che_school_can')->where("state=1")->select();
     	$jiaxiao=count($jiao);
    
      //教练套餐审核
      	$jia=Db::table('che_coach_can')->where("state=1")->select();
     	$jiaolian=count($jia);
        $this->assign('school',$school);
        $this->assign('coach',$coach);
        $this->assign('uid',$uid);
      
       $this->assign('jiaxiao',$jiaxiao);
       $this->assign('jiaolian',$jiaolian);
      return $this->fetch();
    }

    public function coach_edit_url()
    {
        $user_info=Session::get('user_info');
        $result=Db::table('che_userlogin')->where('dianhua ='.$user_info['mobile'])->find();
        $result2=Db::table('che_coach')->where('ss_uid ='.$result['id'])->find();
        $this->redirect('/index.php/Admin/coach/coach_edit?id='.$result2['cid']);
    }
    /**
     * @return mixed
     * 库存管理
     */
    public function stock(){
        $user_info=Session::get('user_info');
        $remind_stock=$user_info['remind_stock'];
        $where=" 1 = 1 ";
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
            $where=$where." and goods_name like '%$name%' OR spec_name like '%$name%' OR brand like '%$name%' OR pattern like '%$name%'";
        }
        $goods_list=Db::name('goods')->where($where)->order("goods_id DESC")->paginate(8);

        $this->assign('goods_list',$goods_list);
        $this->assign('tag',$tag);
        return $this->fetch();
    }
    /**
     * @return mixed
     * 市级经销商
     */
    public function city_dealer(){
        $user_list=Db::name('user_info')->where(array("type"=>2))->paginate(8);
        $this->assign('user_list',$user_list);
        return $this->fetch();
    }


    /**
     * @return mixed
     * 县/区级经销商
     */
    public function dist_dealer(){
        $user_list=Db::name('user_info')->field("i.*,su.name as su_name")->alias("i")->join(" user_info su","i.super_uid = su.uid")->where(array("i.type"=>3))->paginate(8);
        $this->assign('user_list',$user_list);
        return $this->fetch();
    }
    /**
     * @return mixed
     * 发货管理
     */
    public function send_cargo(){

        $where=" 1 = 1 ";
        $get=input('get.');
        $get['unsend']= isset($get['unsend'])?$get['unsend']:"N";
        $get['issend']= isset($get['issend'])?$get['issend']:"N";
        $tag="none";
        if($get['unsend']=="Y"){
            $where=$where." and status = 0";
            $tag="unsend";
        }
        if($get['issend']=="Y"){
            $where=$where." and status = 1";
            $tag="issend";
        }
        if(isset($get['name'])){
            $name=$get['name'];
            $where=$where." and goods_name like '%$name%' OR user_name like '%$name%'";
        }
        $cargo_list=Db::name('send_cargo')->where($where)->order("id DESC")->paginate(8);
        $this->assign('cargo_list',$cargo_list);
        $this->assign('tag',$tag);
        return $this->fetch();
    }

    /**
     * @return mixed
     * 添加用户
     */
    public function add_users(){
        $post=input('post.');
        $get=input('get.');

        $get_type=$get['type'];
        if($post){
            if($post['type']==2){
                $post['super_id']="1";
            }
            $post['password']=md5($post['password']);
            $data=array(
                'username'=>$post['username'],
                'password'=>$post['password']
            );
            $uid=Db::name('user')->strict(true)->insertGetId($data);
            $data2=array(
                'uid'=>$uid,
                'name'=>$post['name'],
                'super_uid'=>$post['super_id'],
                'type'=>$post['type'],
                'area'=>$post['area'],
            	'mobile'=>$post['mobile'],
              	'e_mail'=>$post['e_mail'],
            
            );
            $info_id=Db::name('user_info')->strict(true)->insertGetId($data2);
            $user_info=Db::name('user_info')->where(array('info_id'=>$info_id))->find();
            if($user_info['type']==2){
                $this->redirect('/index.php/Index/index/city_dealer');
            }else{
                $this->redirect('/index.php/Index/index/dist_dealer');
                }
        }
        $users=Db::name('user_info')->where(array("type"=>2))->select();
        $this->assign('city_users',$users);
        $this->assign('get_type',$get_type);
        return $this->fetch();
    }

    //修改市级经销商
      public function  cedit(){
        $post=input('post.');
         if($post){
              $info=Db::name('user_info')->update($post);
               if($info){
                $this->redirect('/index.php/Index/index/city_dealer');
               }
        }
            $request = request();
            $iid=$request->param('iid');
            $uid=$request->param('uid');
            $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
            $users=Db::name('user_info')->where(array("type"=>2))->where("uid","<>","$uid")->select();
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
            $this->assign('city_users',$users);
            $this->assign('info',$info);
            $this->assign('user',$user);
            return $this->fetch();

    }

    //通知点击已读事件
    public function notice_click(){
        $post=input('post.');
        $notic=Db::name('notice')->where(array("id"=>$post['id']))->find();
        Db::name('notice')->where(array("content"=>$notic['content']))->update(array("status"=>1));
        $arr=array("code"=>"1","msg"=>"更新成功");
        exit(json_encode($arr));
    }

    /**
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 发货确认
     */
    public function send(){
        $post=input('post.');
        $user_info=Session::get('user_info');
        $get=input('get.');
        $date=date("Y年m月d号",time()) ;
        if(isset($get['type'])){
            if($get['type']=='n'){
                Db::name('send_cargo')->where(array("id"=>$get['id']))->update(array("status"=>2,"handel_time"=>$date));
                echo "<script> alert('操作成功'); location.href='/index.php/Index/index/send_cargo';</script>";exit;
            }
        }

        if(!isset($post['id'])||!isset($post['type'])){
            echo "<script> alert('系统异常，请联系网站管理员') ;location.href='/index.php/Index/index/send_cargo';</script>";exit;
        }
        if($post['type']=="y"){

             $send_info=Db::name('send_cargo')->where(array("id"=>$post['id']))->find();
             if(!$send_info){
                  echo "<script> alert('无此申请，请联系网站管理员');location.href='/index.php/Index/index/send_cargo'; </script>";exit;
             }
            $goods_info=Db::name('goods')->where(array("goods_id"=>$send_info['goods_id']))->find();
            if(!$goods_info){
                echo "<script> alert('请求商品不存在，请联系网站管理员');location.href='/index.php/Index/index/send_cargo'; </script>";exit;
            }
            $Account_log=new MyLogic();
            if($post['send_type']==1){
                //大厂发货需要查看库存
                if(!$goods_info||$goods_info['stock']<$send_info['apply_num']){
                    echo "<script> alert('库存不足，发货失败'); location.href='/index.php/Index/index/send_cargo';</script>";exit;
                }
                if($goods_info['stock_trial']<$send_info['apply_trial_num']){
                    echo "<script> alert('试用机库存不足，发货失败'); location.href='/index.php/Index/index/send_cargo';</script>";exit;
                }
                //大厂发货商家减库存
                Db::name('goods')->where(array("goods_id"=>$goods_info['goods_id']))->setDec('stock',$send_info['apply_num']);
                Db::name('goods')->where(array("goods_id"=>$goods_info['goods_id']))->setDec('stock_trial',$send_info['apply_trial_num']);
                //记录变更日志
                $Account_log->accountLog($user_info['uid'],$goods_info['goods_id'],"-".$send_info['apply_num'],"-".$send_info['apply_trial_num'],"大库发货，库存减少");
            }else{
                //厂家发货，记录发货情况
                $date=date("Y-m-d H:i:s",time()) ;
                $factory_data=array(
                    "goods_id"=>$goods_info['goods_id'],
                    "uid"=>'1',
                    'to_user'=>$send_info['uid'],
                    'create_time'=>$date,
                    'car_go_id'=>$send_info['id'],
                );
                Db::name('factory_send')->insertGetId($factory_data);
            }
                //用户加库存
                $user_goods=Db::name('user_goods')->where(array("uid"=>$send_info['uid'],"goods_id"=>$send_info['goods_id']))->find();
                if($user_goods){
                    Db::name('user_goods')->where(array("uid"=>$send_info['uid']))->setInc('stock',$send_info['apply_num']);
                    Db::name('user_goods')->where(array("uid"=>$send_info['uid']))->setInc('stock_trial',$send_info['apply_trial_num']);
                }else{
                    $goods_info['stock']=$send_info['apply_num'];
                    $goods_info['uid']=$send_info['uid'];
                    $goods_info['stock_trial']=$send_info['apply_trial_num'];
                    Db::name('user_goods')->insert($goods_info);
                }
                //更新为已发货
                Db::name('send_cargo')->where(array("id"=>$post['id']))->update(array("status"=>1,"send_type"=>$post['send_type']));
                $Account_log->accountLog($send_info['uid'],$send_info['goods_id'],"+".$send_info['apply_num'],"+".$send_info['apply_trial_num'],"管理员发货");

        }else{

        }
        echo "<script> alert('操作成功'); location.href='/index.php/Index/index/send_cargo';</script>";exit;

    }

    public function addeditgoods(){
        $get=input('get.');
        if(isset($get['goods_id'])){
            $goods_info=Db::name('goods')->where(array("goods_id"=>$get['goods_id']))->find();
        }else{
            $goods_info=array(
                'goods_id'=>'',
                'goods_name'=>'',
                'price'=>'',
                'spec_name'=>'',
                'pattern'=>'',
                'brand'=>'',
                'stock'=>'',
                'stock_trial'=>'',
                'unit'=>'',
            );
        }
        $post=input('post.');
        if($post){
            if($post['goods_id']==''){
                $info_id=Db::name('goods')->strict(true)->insertGetId($post);
            }else{
                $info_id=Db::name('goods')->where(array("goods_id"=>$post['goods_id']))->update($post);
            }
            $this->redirect('/index.php/Index/index/stock');
        }
        $this->assign('goods_info',$goods_info);
        return $this->fetch();
    }
    //采购商品
    public function buy_num(){
        $post=input('post.');
        if($post){
            $info_id=Db::name('goods')->where(array("goods_id"=>$post['goods_id']))->setInc('stock',$post['buy_num']);
            $info_id=Db::name('goods')->where(array("goods_id"=>$post['goods_id']))->setInc('stock_trial',$post['buy_trial_num']);
            $this->redirect('/index.php/Index/index/stock');
        }

    }

    /**
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 删除商品
     */
    public function remove(){
        $get=input('get.');
        if(isset($get['goods_id'])) {
            $goods_info = Db::name('goods')->where(array("goods_id" => $get['goods_id']))->delete();
        }
        $this->redirect('/index.php/Index/index/stock');
    }

    /**
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 删除用户
     */
     public function del_user(){
        $request=request();
       	$id=$request->param('id');
   		
           $user = Db::name('userlogin')->where(array("id" =>$id))->delete();
         
     

        if($user){
          
                $this->redirect('/index.php/Admin/index/user_info');
          
        }
      
    }
    
    
  
  
      /**
   * 密码修改   总管理
   */
    public function mima(){
          $post=input('post.');
         if($post){
             
             $post['password']=md5($post['password']);
               $data=array(
               
                'password'=>$post['password'],
                'uid'=>$post['uid'],
               );   

              $info=Db::name('user')->update($data);
               if($info){
                $this->redirect('/index.php/Admin/Index/selfcenter');
               }
        }
            $user=Session::get('user_info');
            //获取用户id
             $uid=$user['uid'];
              
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
             $this->assign('user',$user);
           
      
      
        return $this->fetch();
    }
    /**
  
  
  
  
  
  
  
  
  
      /**
   * 密码修改   市级经销商
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
                $this->redirect('/index.php/Index/index/city_dealer');
               }
        }
            $request = request();
            $iid=$request->param('iid');
            $uid=$request->param('uid');
            $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
            $this->assign('user',$user);
            $this->assign('info',$info);
        return $this->fetch();
    }
    /**
     
     * 密码修改     县经销商
     */
    public function dmima(){
          $post=input('post.');
         if($post){
             $post['password']=md5($post['password']);
               $data=array(
                
                'password'=>$post['password'],
                'uid'=>$post['uid'],
               );   

              $info=Db::name('user')->update($data);
               if($info){
                $this->redirect('/index.php/Index/index/dist_dealer');
               }
        }
            $request = request();
            $iid=$request->param('iid');
            $uid=$request->param('uid');
            $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
           $this->assign('user',$user);
            $this->assign('info',$info);
      
      
        return $this->fetch();
    }
  
  
    //修改县经销商
      public function  dedit(){
        $post=input('post.');
         if($post){
              $info=Db::name('user_info')->update($post);
               if($info){
                $this->redirect('/index.php/Index/index/dist_dealer');
               }
        }
            $request = request();
            $iid=$request->param('iid');
            $uid=$request->param('uid');
            $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
            $users=Db::name('user_info')->where(array("type"=>2))->select();
            $user =Db::name('user')->where(array('uid'=>$uid))->find();
            $this->assign('city_users',$users);
            $this->assign('info',$info);
            $this->assign('user',$user);
            return $this->fetch();

    }
  
  	//批量添加商品
  
  	  public function  addgoods(){
      
           $request =request();

         $file=$request->file("file");
        if($file){
                   //获取数据
        $file=$request->file("file");
        //移动
        $info =$file->move(ROOT_PATH.'public'.DS.'/uploads');
         //引入文件
        \think\Loader::import('classes.PHPExcel');
        $objPHPExcel = new \PHPExcel();

         $exclePath = $info->getSaveName();
        
        //上传文件的地址
        $filename = ROOT_PATH . 'public' . DS . 'uploads'.DS . $exclePath;
	
          $extension = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );
	
        \think\Loader::import('PHPExcel.IOFactory.PHPExcel_IOFactory');
        if ($extension =='xlsx') {
            $objReader = new \PHPExcel_Reader_Excel2007();
            $objExcel = $objReader ->load($filename);

        } else if ($extension =='xls') {

            $objReader = new \PHPExcel_Reader_Excel5();
            $objExcel = $objReader->load($filename);


        }

        $sheet =$objExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
          
        $highestColumn =$sheet->getHighestColumn(); //取得总列数
   
       $excel_array=$objExcel->getsheet(0)->toArray();   //转换为数组格式
    
        array_shift($excel_array);  //删除第一个数组(标题);
      //  array_shift($excel_array);  //删除th
      	
       $data=[];
      
        foreach ($excel_array as $k=>$v){
            $data[$k]["goods_name"]=$v[0];//商品名称
            $data[$k]["price"]=(int)$v[1];//价格
            $data[$k]["spec_name"]=$v[2];//规格
            $data[$k]["pattern"]=$v[3];//商品型号
            $data[$k]["brand"]=$v[4];//品牌
          	 $data[$k]["stock"]=$v[5];		// 库存 stock
          	 $data[$k]["stock_trial"]=$v[6];		//体检机 stock_trial
         	 $data[$k]["unit"]=$v[7];	//单位
          
          // $data[$k]['itime']=$v[4];//时间
        
           // $data[$k]['itime']=$arr[0]."年".$arr[2]."月".$arr[4]."日";
             //dump($data[$k]);exit;
             $info=Db::name('goods')->insert($data[$k]);
          
          
        }
       	if($info){
           $this->redirect('/index.php/Index/index/stock');
        
        
        }
          //$this->success("添加成功","/phone/index");
 
       
        }
      
         
            return $this->fetch();

    }
  
  
  
  
   //发货列表
      public function  send_list(){
    		$list=Db::name('factory_send')->alias('f')->join('user_info u','f.to_user = u.uid')->join('send_cargo s','f.car_go_id = s.id')->order("f.id DESC")->paginate(8);
     	
	
         	//$cargo_list=Db::name('send_cargo')->where($wher;
            $this->assign('list',$list);
            return $this->fetch();

    }

    
    //个人中心
    public function selfcenter(){
        $user=Session::get('user_info');
        //获取用户id
        $uid=$user['uid'];
        $this->assign('uid',$uid);
        return $this->fetch();
    }

    //编辑个人信息
    public function editinfo(){
        $post=input('post.');
        if($post){
            $info=Db::name('user_info')->where(array("info_id"=>$post['info_id']))->update($post);
            if($info){
                $this->redirect('/index.php/Admin/index/selfcenter');
            }
        }
        return $this->fetch();
    }
    //发货信息详情
    public function cargo_detail(){
        $get=input('get.');
        $cargo_info=Db::name('send_cargo')->alias("sc")->field("sc.*,g.unit")->join("goods g","sc.goods_id=g.goods_id","LEFT")->where(array("sc.id"=>$get['id']))->find();
        $user_info=Db::name('user_info')->where(array("uid"=>$cargo_info['uid']))->find();
        $this->assign('cargo_info',$cargo_info);
        $this->assign('apply_user',$user_info);
        return $this->fetch();
    }
	 public function uname(){
     	   header('Content-type: text/json;charset=utf-8'); 
       	$request=request();
   		$name=$request->get('name');
   	
       //获取数据库用户名
   		$arr=Db::name("user")->column('username');	
   		//判断是否在数组里
   		if(in_array($name,$arr)){
   		echo 1;

   		}else{
   			echo 0;
   		}
     		
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

    /**
     *大库管理员
     */
    public function depot_manager(){
        $user_list=Db::name('user_info')->where(array("type"=>1))->paginate(8);
        $this->assign('user_list',$user_list);
        return $this->fetch();
    }
	
    //大库发货记录
    public function library(){
       $get=input('get.');
      //创建请求对象
      $where="status =1 and send_type=1 ";
      
  
     	  if(isset($get['name'])){
            $name=$get['name'];
            $where=$where." and goods_name like '%$name%' OR user_name like '%$name%'";
        }
      
      $list=Db::name('send_cargo')->where($where)->paginate(8);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 添加大库管理员
     */
    public function add_manager(){
        $post=input('post.');
        if($post){
            $post['password']=md5($post['password']);
            $data=array(
                'username'=>$post['username'],
                'password'=>$post['password']
            );
            $uid=Db::name('user')->strict(true)->insertGetId($data);
            $data2=array(
                'uid'=>$uid,
                'name'=>$post['name'],
                'type'=>1,
                'mobile'=>$post['mobile'],
                'e_mail'=>$post['e_mail'],
            );
            $info_id=Db::name('user_info')->strict(true)->insertGetId($data2);
            $user_info=Db::name('user_info')->where(array('info_id'=>$info_id))->find();
            if($user_info['type']==2){
                $this->redirect('/index.php/Index/index/city_dealer');
            }elseif($user_info['type']==3){
                $this->redirect('/index.php/Index/index/dist_dealer');
            }else{
                $this->redirect('/index.php/Index/index/depot_manager');
            }
        }
        return $this->fetch();
    }

    /**
     * 修改大库管理员信息
     */
    public function edit_manager(){
        $post=input('post.');
        if($post){
            $info=Db::name('user_info')->update($post);
            if($info){
                $this->redirect('/index.php/Index/index/depot_manager');
            }
        }
        $request = request();
        $iid=$request->param('iid');
        $uid=$request->param('uid');
        $info=Db::name('user_info')->where(array('info_id'=>$iid))->find();
        $users=Db::name('user_info')->where(array("type"=>2))->select();
        $user =Db::name('user')->where(array('uid'=>$uid))->find();
        $this->assign('city_users',$users);
        $this->assign('info',$info);
        $this->assign('user',$user);
        return $this->fetch();

    }

    /**
     * @return mixed
     * @throws \think\exception\DbException
     * 分公司库存
     */
    public function branch_stock(){

        $user_list=Db::name('user_info')->where(array("type"=>2))->paginate(8);
        $this->assign('user_list',$user_list);
        return $this->fetch();

    }

    /**
     * 分公司处理详情
     */
    public function branch_detail(){
        $get=input('get.');
        $uid=$get['uid'];
        $user_info=Db::name('user_info')->where(array("uid"=>$user_info['uid']))->find();
        $where=" 1 = 1 and uid = $uid";
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
            $where=$where." and goods_name like '%$name%' OR spec_name like '%$name%' OR brand like '%$name%' OR pattern like '%$name%'";
        }
        $goods_lists=Db::name('user_goods')->where($where)->order("goods_id DESC")->paginate(8);
        $goods_list=Db::name('user_goods')->where("uid = $uid")->order("goods_id DESC")->select();
        $goods_id="";
        if($goods_list){
            foreach ($goods_list as $key=>$value){
                if($key==0){
                    $goods_id=$value['goods_id'];
                }else{
                    $goods_id=$goods_id.",".$value['goods_id'];
                }
            }
        }
        if($goods_id==""){
            $nhave_goods=Db::name('goods')->select();
        }else{
            $nhave_goods=Db::name('goods')->where("goods_id not in ($goods_id)")->select();
        }
        $this->assign('goods_list',$goods_lists);
        $this->assign('tag',$tag);
        $this->assign('nhave_goods',$nhave_goods);
        return $this->fetch();

        return $this->fetch();
    }


}
