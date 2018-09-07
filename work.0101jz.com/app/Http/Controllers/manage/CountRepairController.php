<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class CountRepairController extends LoginController
{
    /**
     * 来电统计-维修改数量
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        return view('manage.count_repair.index',[]);
    }


}
