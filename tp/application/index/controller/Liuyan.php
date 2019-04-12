<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 14:38
 */

namespace app\Index\controller;


use app\model\Message as MessageModel;

class Liuyan
{
    function index(){
        $data=MessageModel::limit(0,10)->select();
        return view('index',['active'=>"留言建议",'data'=>$data]);
    }
    function add(){
        $data=input("post.");
        $code=input("post.code");
        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check($code)) {
            return json(["msg" => "验证码错误", 'code' => 404]);
        } else {
            $obj=new MessageModel;
            $data['time']=time();
            $r=$obj->allowField(true)->save($data);
            if ($r) {
                return json(['msg' => "留言成功", 'code' => 200]);
            } else {
                return json(['msg' => "留言失败", 'code' => 404]);
            }
        }
    }
    /*
     * 打开提交留言界面
     * */
    function tijiao(){
        return view('tijiao',['active'=>"留言建议"]);
    }
}