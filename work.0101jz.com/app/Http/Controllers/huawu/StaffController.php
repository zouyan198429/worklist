<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class StaffController extends LoginController
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
        return view('huawu.staff.index',[]);
    }


}
