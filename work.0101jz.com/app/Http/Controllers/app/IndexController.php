<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class IndexController extends WorksController
{

    /**
     * 登陆
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request)
    {
        return view('app.login',[]);
    }

    /**
     * 显示
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        return view('app.admin.info',[]);
    }

}
