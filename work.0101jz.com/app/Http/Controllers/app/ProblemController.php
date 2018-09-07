<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class ProblemController extends LoginController
{
    /**
     * 添加
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        return view('app.problem.add',[]);
    }


}
