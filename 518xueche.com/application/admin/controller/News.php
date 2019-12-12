<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\File;

class News extends Base{


	public function news_list()
	{
		$result=Db::table('che_news')->order('id desc')->paginate(8);
		$this->assign('result',$result);
		return $this->fetch();
	}

	public function add_news()
	{
		$post=input('post.');
		if($post){
			$post['time']=time();
			$result=Db::table('che_news')->insert($post);
			if($result){ $this->success('信息添加成功'); }else{ $this->success('信息添加失败'); }
		}
		return $this->fetch();
	}


	public function del_news()
	{
		$id=input('param.id');
		$result=Db::table('che_news')->where('id ='.$id)->delete();
		if($result){ $this->success('删除成功'); }else{ $this->success('删除失败'); }
	}



	public function exit_news(){
		$id=input('param.id');
		$post=input('post.');
		if($post)
		{
			$xid=$post['xid'];
			unset($post['xid']);
			Db::table('che_news')->where('id ='.$xid)->update($post);
			$this->success('修改成功'); 
		}
		$result=Db::table('che_news')->where('id ='.$id)->find();
		$this->assign('result',$result);
		return $this->fetch();
	}


		public function jian(){
		$id=input('param.id');
		$jian=input('param.jian');
		$gx=Db::table('che_news')->where('id ='.$id)->update(['jian'=>$jian]);
		$this->success('修改成功'); 
	}


			public function xxhf(){
				$result=DB::table('che_xxhf')->order('id desc')->paginate(10);
				$this->assign('result',$result);
				return $this->fetch();
			}

			public function change(){
				$id=input('param.id');
				$state=input('param.state');
				Db::table('che_xxhf')->where('id ='.$id)->update(['state' => $state]);
                $result=Db::table('che_xxhf')->where('id ='.$id)->find();
				file_get_contents('http://120.25.105.164:8888/sms.aspx?action=send&userid=1195&account=518xueche&password=518xueche360888&mobile='.$result['mobile'].'&content=【518学车网】尊敬的用户您好，您的先学后付申请已通过，请耐心等待工作人员为您办理相关手续。&sendTime=&extno=');
				$this->success('审核成功！');
			}
			public function view_img(){
				$post=input('post.geturl');
				$fg=explode('|',$post);
                $result=Db::table('che_xxhf')->where('id ='.$fg['1'])->find();
                if($fg[0] == 'a'){ $arr=array('imgurl' => $result['img1']); return json_encode($arr); exit;}
                if($fg[0] == 'b'){ $arr=array('imgurl' => $result['img2']); return json_encode($arr); exit;}
			}

}