<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class ExamScoreController extends AdminController
{
    /**
     * 列表
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        return view('manage.exam_score.index',[]);
    }


}
