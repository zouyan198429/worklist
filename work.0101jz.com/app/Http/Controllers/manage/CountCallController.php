<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class CountCallController extends AdminController
{
    /**
     * 来电统计
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        return view('manage.count_call.index',[]);
    }

}
