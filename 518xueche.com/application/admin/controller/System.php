<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;

class System extends Base
{
//轮播图
    public function map()
    {
        $result = Db::table('che_map')->order('id desc')->paginate(8);


        $this->assign('map', $result);
        return $this->fetch();

    }


//添加轮播图
    public function add_map()
    {
        $post = input('post.');
        $request = request();
        $file = $request->file("pic");


        if ($file) {

            //移动
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            //获取上传文件信息
            $path = $info->getSavename();

            $post['pic'] = '/public/uploads/' . $path;
            $post['time'] = date("Y-m-d", time());
            $type = Db::table('che_map')->Insert($post);
            if ($type) {
                $this->redirect('/index.php/Admin/system/map');
            }

        }

        return $this->fetch();
    }

    //删除轮播图
    public function mdel()
    {
        $get = input('get.');
        if (isset($get['id'])) {

            $data = Db::table('che_map')->find($get['id']);

            $path = $data['pic'];
            //删除图片
            unlink(ROOT_PATH . $path);
            $goods_info = Db::table('che_map')->where(array("id" => $get['id']))->delete();
        }
        $this->redirect('/index.php/Admin/system/map');

    }

    //修改
    public function edit()
    {
        $post = input('post.');
        $request = request();
        $file = $request->file("spic");

        if ($post) {
            $t = $post['type'];

            $ty = implode(',', $t);

            $typ = ',' . $ty . ',';
            $post['type'] = $typ;

            if ($file) {

                //移动
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

                //获取上传文件信息
                $path = $info->getSavename();

                $post['spic'] = '/public/uploads/' . $path;
            }


            $info = Db::table('che_school')->update($post);
            if ($info) {
                $this->redirect('/index.php/Admin/school/indexs');
            }
        }


        $request = request();
        $id = $request->param('id');

        $school = Db::table('che_school')->where(array('id' => $id))->find();
        $class = Db::table('che_shoolclass')->select();
        $type = Db::table('che_shooltype')->select();
        foreach ($type as $key => $value) {
            $ishave = strpos($school['type'], $value['type']);
            if ($ishave) {
                $ishave = "Y";
            } else {
                $ishave = "N";
            }
            $type[$key]['ishave'] = $ishave;
        }

        $this->assign('type', $type);
        $this->assign('class', $class);
        $this->assign('type', $type);

        $this->assign('school', $school);
        return $this->fetch();

    }


    /**
     * 客服电话
     */
    public function hotline()
    {
        $post=input('post.');
        $hotline=Db::name('hotline')->order("line_id DESC")->find();
        if($post){
            //有就修改，无则添加
            if($hotline){
                Db::name('hotline')->update($post);
                echo "<script>alert('修改成功');location.href='/index.php/Admin/system/hotline'</script>";exit;
            }else{
                $post['add_time']=time();
                Db::name('hotline')->insert($post);
                echo "<script>alert('添加成功');location.href='/index.php/Admin/system/hotline'</script>";exit;
            }
        }
        $this->assign('hotline', $hotline);
        return $this->fetch();
    }
	//修改轮播图跳转地址 up_map
  	    public function up_map()
    {
              $request = request();
          	 $id=$request->param("id");
            $result = Db::table('che_map')->where('id',$id)->find();
           $file = $request->file("pic");
        	$post=input('post.');
        if($post){
        
			 if ($file) {

                //移动
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

                //获取上传文件信息
                $path = $info->getSavename();

                $post['pic'] = '/public/uploads/' . $path;
            }


            $info = Db::table('che_map')->update($post);
            if ($info) {
                $this->redirect('/index.php/admin/system/map');
            }
          
          
          
        }
        $this->assign('url',$result);
        return $this->fetch();
    }

}