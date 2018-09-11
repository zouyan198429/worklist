<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyDepartment;
use App\Business\CompanyStaff;
use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class StaffController extends WorksController
{
    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得第一级分类
        $parentData = CompanyDepartment::getChildList($request, $this, 0, 1 + 0);
        $reDataArr['parent_list'] = $parentData['result']['data_list'] ?? [];
        return view('weixiu.staff.index', $reDataArr);
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyStaff::getList($request, $this, 2 + 4);
    }

}
