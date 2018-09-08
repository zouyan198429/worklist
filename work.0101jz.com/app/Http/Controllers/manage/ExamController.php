<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class ExamController extends AdminController
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
        $this->InitParams($request);
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
        $this->InitParams($request);
        return view('manage.exam.add',[]);
    }


}
