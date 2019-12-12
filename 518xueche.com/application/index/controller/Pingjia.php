<?php

namespace app\index\controller;

use think\Db;
use think\Session;
use app\index\logic\MyLogic;

/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 11:22
 */
class Pingjia extends Allow
{

    public function assess()
    {
        $request = request();
        //获取教练
        $cid = $request->post('cid');
        //获取内容
        $content = $request->post('content');
        $user_info = Session::get('user');
        $user_info=DB::name("userlogin")->where(array("id"=>$user_info['id']))->find();
        if(!$user_info){
            //请登陆
            echo 4;exit;
        }
        //var_dump($user_info);exit;
        $uid = $user_info['id'];
        $uname = $user_info['name'];
        $superior = $user_info['superior'];
        $pingjia = Db::table('che_pingjia')->where(array("uid" => $uid))->find();
        if ($pingjia and $pingjia['state'] == 1) {
            // 判断是否评价   已经评价2
            echo 2;exit;
        }
        if ($superior == 0) {
            //没有教练   
            echo 0;exit;
        }
        $result=DB::table('che_coach')->where('cid ='.$cid)->find();
        if ($superior == $result['ss_uid']) {
            //可以评价
            $time = date("Y-m-d", time());
            $type = Db::table('che_pingjia')->insert(array('uid' => $uid, 'content' => $content, 'cid' => $cid, 'state' => 1, 'uname' => $uname, 'time' => $time));
            if ($type) {
                echo 1;exit;
            } else {
                echo 3;exit;
            }
        }else{

         echo 5;exit;

        }
    }


}
