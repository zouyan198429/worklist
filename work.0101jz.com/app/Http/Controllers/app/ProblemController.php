<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ProblemController extends WorksController
{
    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('app.problem.add', $reDataArr);
    }


}
