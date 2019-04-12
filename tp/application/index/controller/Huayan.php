<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 09:23
 */

namespace app\Index\controller;


use app\model\Content;

class Huayan
{
    /*
     * 打开花言花语页面
     * */
    function index(){
        $data=Content::where("type",1)->order('time','desc')->paginate(3);
        $data1=Content::field('title,id')->where("type",1)->order('id')->limit(0,3)->select();
        return view('index',['active'=>"花言花语",'data'=>$data,'data1'=>$data1]);
    }
    /*
     * 打开花言花语详情
     * */
    function show($id){
        $data=Content::get($id);
        $data['view']+=1;
        $data->save();
        $next=Content::field('title,id')
            ->where(['id'=>['>',$id],'type'=>1])
            ->limit(0,1)->find();
        $prev=Content::field('title,id')
            ->where(['id'=>['<',$id],'type'=>1])
            ->order('id','desc')
            ->limit(0,1)->find();
        return view('show',['active'=>"花言花语",'data'=>$data,'next'=>$next,'prev'=>$prev]);
    }
}