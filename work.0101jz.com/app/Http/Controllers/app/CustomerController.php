<?php

namespace App\Http\Controllers\app;

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
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('app.customer.index', $reDataArr);
    }

}
