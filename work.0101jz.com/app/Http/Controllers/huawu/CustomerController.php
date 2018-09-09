<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class CustomerController extends WorksController
{

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.customer.index', $reDataArr);
    }
    /**
     * 按日统计
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function dayCount(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.customer.dayCount', $reDataArr);
    }

}
