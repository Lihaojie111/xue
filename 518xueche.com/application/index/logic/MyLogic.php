<?php
/**
 * Author: Biao
 * Date: 2018-08-16
 */

namespace app\index\logic;
use think\Model;
use think\Db;
/**
 * 新定义逻辑
 * Class CatsLogic
 * @package Home\Logic
 */
class MyLogic extends Model
{
    /**
     * @param $uid         变更账户ID
     * @param $goods_id    商品ID
     * @param $change_num　变更数量
     * @param $content　　　变更原因
     * @return string
     */
    public function accountLog($uid,$goods_id,$change_num,$trial_num,$content)
    {
        $date=date("Y-m-d H:i:s",time()) ;
        $data=array(
            "uid"=>$uid,
            "goods_id"=>$goods_id,
            "change_num"=>$change_num,
            "content"=>$content,
            "chenge_time"=>$date,
            "trial_num"=>$trial_num,
        );
        $log_id=Db::name('account_log')->insertGetId($data);
        if($log_id){
            $data['id']=$log_id;
        }
        return $data; // 返回新增的订单列表
    }

    public function getSalesRecord($record_list)
    {
        return $record_list; // 返回新增的订单列表
    }

    /**
     * @param $user_info 用户信息
     * @param $buy_num   进货量
     * @param $buy_trial_num 试用机进货量
     * @param $goods_info 商品信息
     * @return array
     * BD修改通知
     */
    public function inStockApply($user_info,$id)
    {
        $date=date("Y-m-d H:i:s",time()) ;
        $data=array(
            "uid"=>$user_info['uid'],
           
            "time"=>$date,
           
            "status"=>0,
           
            "c_id"=>$id,
        );
        $send_cargo=Db::name('send_cargo')->insertGetId($data);
        //生成通知
        $noticeData=array(
            "uid"=>1,
            "content"=>"BD采集员".$user_info['name']."修改审核",
            "add_time"=>$date,
            "status"=>0,
        );
        $note=Db::name('notice')->insertGetId($noticeData);
        if($send_cargo){
           $resault=array("code"=>1,"msg"=>"申请成功");
        }else{
            $resault=array("code"=>2,"msg"=>"申请失败");
        }

        return $resault; // 返回新增的订单列表
    }

}