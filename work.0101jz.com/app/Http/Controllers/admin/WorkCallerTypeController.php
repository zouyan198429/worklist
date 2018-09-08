<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class WorkCallerTypeController extends AdminController
{
    /**
     * 列表页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        return view('admin.work_caller_type.index',[]);
    }


}
