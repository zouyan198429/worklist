<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class WorkController extends LoginController
{

    /**
     * 列表
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        return view('weixiu.work.list',[]);
    }


}
