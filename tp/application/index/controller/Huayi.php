<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 14:31
 */

namespace app\Index\controller;
use app\model\Content;


class Huayi
{
    function index(){
        $data=$skills=Content::where(['type'=>3,'position'=>1])->limit('0','4')->select();
        return view('index',['active'=>"雨辰花艺",'data'=>$data]);
    }
    /*
     * 打开素馨花艺详情
     * */
    function show($id){
        $data=Content::get($id);
        $data['view']+=1;
        $data->save();
        $next=Content::field('title,id')
            ->where(['id'=>['>',$id],'type'=>3])
            ->limit(0,1)->find();
        $prev=Content::field('title,id')
            ->where(['id'=>['<',$id],'type'=>3])
            ->order('id','desc')
            ->limit(0,1)->find();
        return view('show',['active'=>"雨辰花艺",'data'=>$data,'next'=>$next,'prev'=>$prev]);
    }
}