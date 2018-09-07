<?php

namespace App\Http\Controllers\app;

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
        return view('app.customer.index',[]);
    }

}
