<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ExamController extends WorksController
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
        return view('weixiu.exam.index',[]);
    }

    /**
     * 在线考试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function doing(Request $request)
    {
        return view('weixiu.exam.doing',[]);
    }

    /**
     * 在线考试完成
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function win(Request $request)
    {
        return view('weixiu.exam.win',[]);
    }

}
