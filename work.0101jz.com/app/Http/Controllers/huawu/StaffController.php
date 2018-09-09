<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class StaffController extends WorksController
{
    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.staff.index', $reDataArr);
    }


}
