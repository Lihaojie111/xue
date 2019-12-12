<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Cookie;

class Pay extends Controller
{



	public function add_order(){
		$post = input('post.');
		if(!Session::get('user')){
			$arr=array('code'=>2,'msg' => '/index.php/index/index/ulogin.html'); return json_encode($arr); exit;
		}
		$usxx=Session::get('user');
		$post['time']=time();
		$post['pay_state']=0;   
		$post['order_sn']=date('YmdHis').rand(1000,9999);
		$post['uid']=$usxx['id'];
		$result=Db::table('che_order')->insertGetId($post);
		if($result){
			$arr=array('code'=>2,'msg' => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf4152152ada16af9&redirect_uri=http://518xueche.com/index.php/index/Pay/wxlogin?zifu='.$post['price'].'|'.$post['order_sn'].'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect'); return json_encode($arr); exit;
		}else{

			$arr=array('code'=>1,'msg' => '订单创建失败，请联系管理员！'); return json_encode($arr); exit;

		}

	}



	public function news_detail(){
		return $this->fetch('index/newsdetail');   
	}





	public function wxlogin(){
		$code = input('get.code');
		$sz=explode('|',$_GET['zifu']);
		include('application/extend/wxpay/weixinpay.php');
		$wxpay = new \weixinpay();
		$data = $wxpay->getParameters($sz['1'],$code,$sz['0']);
		return $this->fetch('pay', [
			'data'=>json_encode($data)
			]);



//全付通支付的逻辑参数 改为原生支付后 先注释掉
// $code = input('get.code');
// $result=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxf4152152ada16af9&secret=2d64c7d5f6c4cd12d68101e490c22f8d&code={$code}&grant_type=authorization_code");
// $shuzu=json_decode($result,true);
// $this->redirect('http://518xueche.com/index.php/index/Pay/qfpay?openid='.$shuzu['openid'].'out_trade_no='.$_GET['order_sn'].'&order_sn='.$_GET['order_sn'].'&order_id='.$result.'&order_amount='.$_GET['price']);
	}

	public function qfpay(){
		import('qft.quanfut', EXTEND_PATH,'.class.php');
		$zfname = '\\quanfut'; 
		$quanfu = new $zfname();
		$order['out_trade_no']=$_GET['order_sn'];
		$order['order_sn']=$_GET['order_sn'];
		$order['order_id']=$_GET['order_id'];
		$order['order_amount']=$_GET['price'];
		$order['openid']=$_GET['openid'];
		$result = $quanfu -> submitOrderInfo($order); 
		$result=json_decode($result, true); 
		$sz=$result['pay_info'];
		$sz=json_decode($sz, true); 
		$sz['lx']='dingdan';
		$sz['order_id']=$order['order_id'];
		$result2 = $quanfu -> getzhifu($sz); 
		exit($result2);
	}



	public function jc_order(){
		import('qft.quanfut', EXTEND_PATH,'.class.php');
		$zfname = '\\quanfut'; 
		$quanfu = new $zfname();
		$get=I('param.');
		$dingdan= Db::table('che_order')->where("order_id" , $get['order_id'])->find();

		$quanfu -> queryOrder($dingdan);

		if($get['lx'] == 'dingdan')
		{
			$this->redirect('/index.php/index/Pay/jc_success',array('id'=>$get['order_id']));
		}else{

			$this->redirect('/index.php/index/Pay/jc_success',array('type'=>'recharge'));	
		}

	}


	public function back(){
		import('qft.quanfut', EXTEND_PATH,'.class.php');
		$zfname = '\\quanfut'; 
		$quanfu = new $zfname();
		$get=I('param.');
		$dingdan= M('order')->where('order_sn ='.$id)->find();
		echo $quanfu -> queryOrder($dingdan);

	}

	public function zhifuchenggong(){
		return $this->fetch('index/zhifuchenggong');
	}

	public function loading(){
		return $this->fetch('index/loading');
	}


	public function notify()
	{
        $xml=file_get_contents('php://input', 'r');
		include('application/extend/wxpay/weixinpay.php');
		$wxpay = new \weixinpay();
		$result=$wxpay->notify($xml);
		if ($result) {
        $data['pay_time']=time();
        $data['pay_state']=1;
        $data['sf_price']=$result['total_fee']/100;
        Db::table('che_order')->where('order_sn ='.$result['out_trade_no'])->update($data);
		}
	}
}
