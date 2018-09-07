<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class ExamController extends LoginController
{

    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        return view('manage.exam.index',[]);
    }

    /**
     * 添加
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        return view('manage.exam.add',[]);
    }


}
