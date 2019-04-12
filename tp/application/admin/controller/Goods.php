<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/2
 * Time: 08:52
 */

namespace app\admin\controller;


use app\model\Content;
use think\Controller;

class Goods extends Controller
{
    public function _initialize(){
        if (!check()) {
            $this->redirect("/admin/admin/login");
        }
    }
    function index(){
        return view();
    }
    function select(){
        $page=input("get.page")?input("get.page"):1;//page第几页
        $pageSize=input("get.pageSize")?input("get.pageSize"):5;//pageSize每页取几条
        $search=input("get.search");
        $offset=($page-1)*$pageSize;
        $where=["type"=>1];
        if ($search!==""){
            $where['title']=['like',"%$search%"];
        }
        $data=Content::field("id,title,time,name,thumb")
            ->where($where)
            ->order("time","desc")
            ->limit($offset,$pageSize)
            ->select();
        $total=Content::where($where)
            ->count();
        if($data){
            return json(["msg"=>"获取成功",'code'=>200,'data'=>$data,'total'=>$total]);
        }else{
            return json(["msg"=>"获取失败",'code'=>404]);
        }
    }
    function delete(){
        $id=$_GET["id"];
        $r=Content::destroy($id);
        if ($r){
            return json(["msg"=>"删除成功",'code'=>200]);
        }else{
            return json(["msg"=>"删除失败",'code'=>404]);
        }
    }

}