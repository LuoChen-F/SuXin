<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 09:23
 */

namespace app\Index\controller;


use app\model\Content;

class News
{
    /*
     * 打开素馨动态页面
     * */
    function index(){
        $data=Content::where("type",2)->order('time','desc')->paginate(3);
        $data1=Content::field('title,id')->where("type",2)->order('id')->limit(0,3)->select();
        return view('index',['active'=>"雨辰动态",'data'=>$data,'data1'=>$data1]);
    }
    /*
     * 打开素馨动态详情
     * */
    function show($id){
        $data=Content::get($id);
        $data['view']+=1;
        $data->save();
        $next=Content::field('title,id')
            ->where(['id'=>['>',$id],'type'=>2])
            ->limit(0,1)->find();
        $prev=Content::field('title,id')
            ->where(['id'=>['<',$id],'type'=>2])
            ->order('id','desc')
            ->limit(0,1)->find();
        return view('show',['active'=>"雨辰动态",'data'=>$data,'next'=>$next,'prev'=>$prev]);
    }
}