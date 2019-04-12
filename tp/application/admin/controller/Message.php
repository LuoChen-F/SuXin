<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/3
 * Time: 11:00
 */

namespace app\admin\controller;
use app\model\Message As MessageModel;
use think\Controller;

class Message extends Controller
{
    public function _initialize(){
        if (!check()) {
            $this->redirect("/admin/admin/login");
        }
    }
    /*
     * 打开留言列表页面
     * */
    function index(){
        return view();
    }
    function select(){
        $page=input("get.page")?input("get.page"):1;//page第几页
        $pageSize=input("get.pageSize")?input("get.pageSize"):5;//pageSize每页取几条
        $search=input("get.search");//pageSize每页取几条
        $offset=($page-1)*$pageSize;
        $where=[];
        if ($search!==""){
            $where['name']=['like',"%$search%"];
        }
        $data=MessageModel::field("id,name,tel,email,content,time,reply")
            ->where($where)
            ->order("time","desc")
            ->limit($offset,$pageSize)
            ->select();
        $total=MessageModel::where($where)
            ->count();
        if($data){
            return json(["msg"=>"获取成功",'code'=>200,'data'=>$data,'total'=>$total]);
        }else{
            return json(["msg"=>"获取失败",'code'=>404]);
        }
    }
    /*
     * 用来删除留言信息
     * */
    function delete(){
        $id=$_GET["id"];
        $r=MessageModel::destroy($id);
        if ($r){
            return json(["msg"=>"删除成功",'code'=>200]);
        }else{
            return json(["msg"=>"删除失败",'code'=>404]);
        }
    }
    /*
     * 用来回复留言信息
     * */
    function reply(){
        $id=input("id");
        $reply=input("post.reply");
        $obj=MessageModel::get($id);
        $obj->reply=$reply;
        $r=$obj->save();
        if ($r){
            return json(["msg"=>"回复成功",'code'=>200]);
        }else{
            return json(["msg"=>"回复失败",'code'=>404]);
        }
    }


}
