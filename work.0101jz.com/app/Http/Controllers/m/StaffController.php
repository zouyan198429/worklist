<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class StaffController extends WorksController
{

    /**
     * 个人中心--个人主页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.staff.index', $reDataArr);
    }

    /**
     * 我的帐号--帐号信息
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.staff.info', $reDataArr);
    }

    /**
     * 我的同事--同事列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.staff.list', $reDataArr);
    }
}
