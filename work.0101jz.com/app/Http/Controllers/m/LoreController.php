<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class LoreController extends WorksController
{

    /**
     * 学习中心--知识列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.lore.index', $reDataArr);
    }

    /**
     * 学习中心-知识详情页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.lore.info', $reDataArr);
    }
}
