<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/1
 * Time: 10:50
 */

namespace app\admin\controller;

use app\model\Admin as AdminModel;
use think\Session;
use app\model\Content;
use app\model\Message;

class Admin
{
    /*
     * 用来返回主界面
     * */
    function main()
    {
        if (check()) {
            return view();
        } else {
            return redirect("/admin/admin/login");
        }
    }

    /*
     * 用来返回密码修改页面
     * */
    function password()
    {
        return view();
    }

    /*
     * 用来修改密码验证
     * */
    function changepass()
    {
//        try{
        $data = input("post.");
        if (empty($data)) {
            return json(["msg" => "请输入要提交的数据", 'code' => 404]);
        }
        if (!isset($data['password'])) {
            return json(["msg" => "请输入原始密码", 'code' => 404]);
        }
        if (!isset($data['newpassword'])) {
            return json(["msg" => "请输入新密码", 'code' => 404]);
        }
        if (!isset($data['newpassword2'])) {
            return json(["msg" => "请再次输入新密码", 'code' => 404]);
        }
        if ($data['newpassword'] !== $data['newpassword2']) {
            return json(["msg" => "请保证两次输入一致", 'code' => 404]);
        }

        //判断旧密码是否正确
        $originData = AdminModel::get("1");
        if (md5($data["password"]) !== $originData['password']) {
            return ["msg" => "原始密码错误", 'code' => 404];
        }
        $originData->password = md5($data["newpassword"]);

        $r = $originData->save();
        if ($r === 1) {
            return json(["msg" => "修改成功", 'code' => 200]);
        } else {
            return json(["msg" => "修改失败", 'code' => 404]);
        }
        /*
    }catch (\Exception $e){
        return json(["msg"=>"服务器错误",'code'=>500]);
    }*/
    }

    /*
     * 打开登录页面
     * */
    function login()
    {
        return view();
    }

    /*
   * 验证登录
   * */
    function checkLogin()
    {
        $code = input("post.code");
        $data = input("post.");
        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check($code)) {
            return json(["msg" => "验证码错误", 'code' => 404]);
        } else {
           if (!(isset($data['username']) && $data['username'] != "")) {
                return json(["msg" => "请输入用户名", 'code' => 404]);
            }
            if (!(isset($data['password']) && $data['username'] != "")) {
                return json(["msg" => "请输入密码", 'code' => 404]);
            }
            $r = AdminModel::getByUsername(input("post.username"));
            if ($r) {
                if ($r['password'] === md5($data['password'])) {
                    Session::set('login', $data['username']);
                    return json(["msg" => "登录成功", 'code' => 200]);
                } else {
                    return json(["msg" => "密码错误", 'code' => 404]);
                }
            } else {
                return json(["msg" => "用户名不存在", 'code' => 404]);
            }
        }
    }
    /*
     * 数据统计
     * */
    function count(){
        return view();
    }
    /*
     * 统计文章数量
     * */
    function charts1(){
        $num1=Content::where("type",1)->count();
        $num2=Content::where("type",2)->count();
        $num3=Content::where("type",3)->count();
//        Db::query("SELECT COUNT(*) AS num FROM content GROUP BY type");
        return json(['code'=>200,'msg'=>"获取成功",'data'=>[$num1,$num2,$num3]]);
    }
    /*
     * 统计留言回复比例
     * */
    function charts2(){
        $replynum1=Message::where("reply","<>","")->count();//回复的
        $replynum2=Message::where("reply","=","")->count();
        return json(['code'=>200,'msg'=>"获取成功",'data'=>[['name'=>"已回复",'value'=>$replynum1],['name'=>"未回复",'value'=>$replynum2]]]);
    }
    /*
    * 统计留言回复比例
    * */
    function charts3(){
        $data1=Content::field("view")->where("type",1)->order("view")->limit(0,1)->select();
        $data2=Content::field("view")->where("type",2)->order("view")->limit(0,1)->select();
        $data3=Content::field("view")->where("type",3)->order("view")->limit(0,1)->select();
        $data4=Content::field("view")->where("type",1)->order("view","desc")->limit(0,1)->select();
        $data5=Content::field("view")->where("type",2)->order("view","desc")->limit(0,1)->select();
        $data6=Content::field("view")->where("type",3)->order("view","desc")->limit(0,1)->select();$high=[$data1,$data2,$data3];
        $low=[$data4,$data5,$data6];
        $i=0;
        foreach ($high as $v){
            foreach ($v as $k){
                $i++;
                $arr[$i]=$k->view;
            }
        }
        foreach ($low as $v){
            foreach ($v as $k){
                $i++;
                $arr1[$i]=$k->view;
            }
        }
        return json(['code'=>200,'msg'=>"获取成功",'high'=>$arr1,'low'=>$arr]);
    }

    /*
     * 退出
     * */
    function logout(){
        Session::delete("login");
        return redirect("/admin/admin/login");//重定向
    }
}
