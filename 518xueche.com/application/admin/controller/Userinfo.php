<?php
/**
 * Created by PhpStorm.
 * User: Biao
 * Date: 2018/8/22
 * Time: 15:44
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class Userinfo extends Base
{

    /**
     * @return mixed
     * 留言
     */
    public function liuyan(){
        $liuyan=Db::name('liuyan')->order("id desc")->paginate(8);
      	$this->assign('liuyan',$liuyan);
      return $this->fetch();
    }
    /**
     * @return mixed
     * 详情
     */
    public function info(){
   
	   $info=Db::name('user_address')->order("id desc")->paginate(8);
     		$this->assign('info',$info);
      return $this->fetch();
    }


}