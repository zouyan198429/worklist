<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class StaffController extends WorksController
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
        return view('weixiu.staff.index',[]);
    }

}
