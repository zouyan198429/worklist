<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class CountCallController extends LoginController
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
        return view('admin.count_call.index',[]);
    }


}
