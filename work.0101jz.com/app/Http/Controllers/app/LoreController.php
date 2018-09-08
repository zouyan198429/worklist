<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class LoreController extends WorksController
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
        return view('app.lore.index',[]);
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
        return view('app.lore.info',[]);
    }


}
