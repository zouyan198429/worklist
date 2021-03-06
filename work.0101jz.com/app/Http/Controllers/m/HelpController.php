<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class HelpController extends WorksController
{

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.help.index', $reDataArr);
    }

}
