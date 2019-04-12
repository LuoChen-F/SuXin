<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/4
 * Time: 14:42
 */

namespace app\Index\controller;


class Guanyu
{
    function index(){
        return view('index',['active'=>"关于雨辰"]);
    }

}