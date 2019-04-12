<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 08:48
 */
namespace app\Index\controller;

use app\model\Content;

class Index
{
    function index(){
        $data1=$skills=Content::where(['type'=>1,'position'=>1])->limit('0','8')->select();
        $data2=$skills=Content::where(['type'=>2,'position'=>1])->limit('0','8')->select();
        $data3=$skills=Content::where(['type'=>3,'position'=>1])->limit('0','5')->select();
        return view('index',['active'=>"首页",'data1'=>$data1,'data2'=>$data2,'data3'=>$data3]);
    }
}