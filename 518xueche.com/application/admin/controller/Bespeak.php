<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\File;

class Bespeak extends Base{

//添加教练
    public function bespeak_school()
    {
        $result=Db::table('che_bespeak')->where('state=0')->paginate(8);
        $this->assign('bespeak',$result);

      
        
        return $this->fetch();

    }

	  public function bespeak_coach()
    {
        $result=Db::table('che_bespeak')->where('state=1')->paginate(8);
        $this->assign('bespeak',$result);

      
        
        return $this->fetch();

    }
  //
    public function bespeak_banxing()
    {
        $result=Db::table('che_banxing_mack')->paginate(8);
        $this->assign('bespeak',$result);

        return $this->fetch();

    }


        public function teamservice()
    {

        $result=Db::table('che_teamservice')->order('id desc')->paginate(8);
        $this->assign('result',$result);
        return $this->fetch();

    }
 
  
  
  
  
  
  
  
}