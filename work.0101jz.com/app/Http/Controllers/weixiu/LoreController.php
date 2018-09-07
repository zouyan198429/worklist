<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class LoreController extends LoginController
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
        return view('weixiu.lore.index',[]);
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
        return view('weixiu.lore.info',[]);
    }


}
