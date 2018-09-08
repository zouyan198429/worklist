<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class CustomerController extends WorksController
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
        return view('weixiu.customer.index',[]);
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
        return view('weixiu.customer.dayCount',[]);
    }

}
