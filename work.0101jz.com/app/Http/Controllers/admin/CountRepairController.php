<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class CountRepairController extends AdminController
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
        $this->InitParams($request);
        return view('admin.count_repair.index',[]);
    }


}
