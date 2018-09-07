<?php

namespace App\Http\Controllers\huawu;

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
        return view('huawu.exam.index',[]);
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
        return view('huawu.exam.doing',[]);
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
        return view('huawu.exam.win',[]);
    }

}
