<?php 

$cookie_jar ="pic.cookie";
$url = 'http://nanyang.vjiaxiao.cn/wxpay/weixinpay_ren_wft';
$opt_data = 'openid=ozTrJt6hhEnW1OUQsrN3NHNWef0c&sfzmhm=411325197606109477';


$curl = curl_init();  //初始化
curl_setopt($curl,CURLOPT_URL,$url);  //设置url
curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);  //设置http验证方法
curl_setopt($curl,CURLOPT_HEADER,0);  //设置头信息
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  //设置curl_exec获取的信息的返回方式
curl_setopt($curl,CURLOPT_POST,1);  //设置发送方式为post请求
curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($curl,CURLOPT_POSTFIELDS,$opt_data);  //设置post的数据

$result = curl_exec($curl);
//$result=str_replace('../wxpay/pay_ren_wft', 'http://nanyang.vjiaxiao.cn/wxpay/pay_ren_wft', $result);

//echo $result;
if($result === false){
    echo curl_errno($curl);
    exit();
}
curl_close($curl);


$url = 'http://nanyang.vjiaxiao.cn/wxpay/pay_ren_wft';
$opt_data = 'openid=ozTrJt6hhEnW1OUQsrN3NHNWef0c&sfzmhm=411325197606109477&xm=**星&jfxm=2180308498503|k3b|1';


$curl = curl_init();  //初始化
curl_setopt($curl,CURLOPT_URL,$url);  //设置url
curl_setopt($curl,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);  //设置http验证方法
curl_setopt($curl,CURLOPT_HEADER,0);  //设置头信息
curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  //设置curl_exec获取的信息的返回方式
curl_setopt($curl,CURLOPT_POST,1);  //设置发送方式为post请求
curl_setopt($curl,CURLOPT_POSTFIELDS,$opt_data);  //设置post的数据

$result = curl_exec($curl);
if($result === false){
    echo curl_errno($curl);
    exit();
}
echo $result;
curl_close($curl);
?>