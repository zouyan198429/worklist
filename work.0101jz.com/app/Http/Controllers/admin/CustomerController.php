<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class CustomerController extends AdminController
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
        $this->InitParams($request);
        return view('admin.customer.index',[]);
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
        $this->InitParams($request);
        return view('admin.customer.dayCount',[]);
    }

}
