<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyDepartment;
use App\Business\CompanyStaff;
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
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
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
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
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
        // 按部门分组同事/员工数据
        $department_list = CompanyStaff::staffGroupDepartment($request, $this);
        $reDataArr['department_list'] = $department_list;
        return view('mobile.staff.list', $reDataArr);
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
