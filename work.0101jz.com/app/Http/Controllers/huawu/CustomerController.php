<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class CustomerController extends LoginController
{

    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        return view('huawu.customer.index',[]);
    }
    /**
     * 按日统计
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function dayCount(Request $request)
    {
        return view('huawu.customer.dayCount',[]);
    }

}
