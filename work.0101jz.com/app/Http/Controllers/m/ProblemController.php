<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ProblemController extends WorksController
{

    /**
     * 问题反馈-提交问题
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.problem.add', $reDataArr);
    }

}
