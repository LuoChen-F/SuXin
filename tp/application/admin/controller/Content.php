<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2019/4/2
 * Time: 14:18
 */

namespace app\admin\controller;

use app\model\Content as ContentModel;
use think\Request;
use think\Controller;


class Content extends Controller
{
    public function _initialize(){
        if (!check()) {
            $this->redirect("/admin/admin/login");
        }
    }
    /*
     * 打开内容发布页面
     * */
    function index()
    {
        return view();
    }

    /*
     * 处理图片上传
     * */
    function upload(Request $request)
    {
        try {
            //获取表单上传文件
            $file = $request->file('file');
            if (empty($file)) {
                return json(["msg" => "请选择文件", 'code' => 404]);
            }
            //	移动到框架应用根目录/public/uploads/	目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $path = $info->getSaveName();   //获取文件路径  结果类似于  \20190405515/akjsdfkjfdfafhk
                $path = str_replace("\\", "/", $path);  //调用str_replace函数  将 \ 转为 /
                return json(["msg" => "上传成功", 'code' => 200, 'src' => "/uploads/" . $path]);
            } else {
                //	上传失败获取错误信息
                return json(["msg" => "上传失败", 'code' => 404]);
            }
        } catch (Exception $e) {
            return json(["msg" => "服务器错误", 'code' => 500]);
        }
    }

    /*
    * 处理富文本图片上传
    * */
    function up(Request $request)
    {
        //获取表单上传文件
        $file = $request->file('file');
        if (empty($file)) {
            return json(["msg" => "请选择文件", 'code' => 404]);
        }
        //	移动到框架应用根目录/public/uploads/	目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            $path = $info->getSaveName();   //获取文件路径  结果类似于  \20190405515/akjsdfkjfdfafhk
            $path = str_replace("\\", "/", $path);  //调用str_replace函数  将 \ 转为 /
            return json(["errno" => 0, 'data' => ["/uploads/" . $path]]);
        } else {
            //	上传失败获取错误信息
            return json(["msg" => "上传失败", 'code' => 404]);
        }
    }

    /*
    * 进行增加验证
    * */
    function insert()
    {
        $data = input("post.");
        if (empty($data)) {
            return json(["msg" => "请添加提交信息", 'code' => 404]);
        }
        if (!(isset($data['title'])&&$data['title']!="")) {
            return json(["msg" => "请输入标题", 'code' => 404]);
        }
        if (!(isset($data['description'])&&$data['description']!="")) {
            return json(["msg" => "请输入简介", 'code' => 404]);
        }
        if (!(isset($data['name'])&&$data['name']!="")) {
            return json(["msg" => "请输入姓名", 'code' => 404]);
        }
        if (!(isset($data['thumb'])&&$data['thumb']!="")) {
            return json(["msg" => "请提交缩略图", 'code' => 404]);
        }
        if (!(isset($data['content'])&&$data['content']!="")) {
            return json(["msg" => "请输入内容", 'code' => 404]);
        }
        if (!(isset($data['type'])&&$data['type']!="")) {
            return json(["msg" => "请选择要发布的栏目", 'code' => 404]);
        }

        $data['time'] = time();
        $con = new ContentModel($data);
        $r = $con->allowField(true)->save();
        if ($r) {
            return json(["msg" => "添加成功", 'code' => 200]);
        } else {
            return json(["msg" => "添加失败", 'code' => 404]);
        }
    }

    /*
    * 打开修改页面
    * */
    function edit()
    {
        $id = input("get.id");
        $r = ContentModel::get($id);
        return view("edit", ["data" => $r]);
    }

    /*
    * 更新内容
    * */
    function update()
    {
        $data = input("post.");
        if (empty($data)) {
            return json(["msg" => "请添加提交信息", 'code' => 404]);
        }
        if (!(isset($data['title'])&&$data['title']!="")) {
            return json(["msg" => "请输入标题", 'code' => 404]);
        }
        if (!(isset($data['description'])&&$data['description']!="")) {
            return json(["msg" => "请输入简介", 'code' => 404]);
        }
        if (!(isset($data['name'])&&$data['name']!="")) {
            return json(["msg" => "请输入姓名", 'code' => 404]);
        }
        if (!(isset($data['thumb'])&&$data['thumb']!="")) {
            return json(["msg" => "请提交缩略图", 'code' => 404]);
        }
        if (!(isset($data['content'])&&$data['content']!="")) {
            return json(["msg" => "请输入内容", 'code' => 404]);
        }
        if (!(isset($data['type'])&&$data['type']!="")) {
            return json(["msg" => "请选择要发布的栏目", 'code' => 404]);
        }
        if (!isset($data['position'])) {
            $data['position'] = 0;
        }
        $data['time'] = time();
        $r = ContentModel::update($data);
        if ($r) {
            return json(["msg" => "修改成功", 'code' => 200]);
        } else {
            return json(["msg" => "修改失败", 'code' => 404]);
        }


    }
}
