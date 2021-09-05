<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanyWork;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class CountRepairController extends AdminController
{
    /**
     * 来电统计-维修改数量
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['count_types'] =  CompanyWork::$countTypeArr;
        $reDataArr['defaultCountType'] = 1;// 列表页默认状态
        return view('manage.count_repair.index', $reDataArr);
    }


}
