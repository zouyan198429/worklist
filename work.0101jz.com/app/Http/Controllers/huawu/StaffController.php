<?php

namespace App\Http\Controllers\huawu;

use App\Business\CompanyDepartment;
use App\Business\CompanyExam;
use App\Business\CompanyPosition;
use App\Business\CompanyStaff;
use App\Http\Controllers\WorksController;
use App\Services\Common;
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
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('huawu.staff.index', $reDataArr);
    }

    /**
     * 同事选择-弹窗
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function select(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);
        // 获得第一级职位分类一维数组[$k=>$v]
        $reDataArr['position_kv'] = CompanyPosition::getListKeyVal($request, $this, 1 + 0);
        return view('huawu.staff.select', $reDataArr);
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

    /**
     * 导出
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function export(Request $request){
        $this->InitParams($request);
        CompanyStaff::getList($request, $this, 1 + 0);
    }

    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function import_template(Request $request){
        $this->InitParams($request);
        CompanyStaff::importTemplate($request, $this);
    }

    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $department_id = Common::getInt($request, 'department_id');
        $group_id = Common::getInt($request, 'group_id');
        // 获得所属部门小组下的员工一维数组[$k=>$v]
        $staffChildKV = CompanyStaff::getChildListKeyValByDepartment($request, $this, $department_id, $group_id,1 + 0);
        return  ajaxDataArr(1, $staffChildKV, '');;
    }


    /**
     * ajax根据部门id,小组id获得所属部门小组下的员工数组[kv一维数组]
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_areachild(Request $request){
        $this->InitParams($request);
        $city_id = Common::getInt($request, 'city_id');
        $area_id = Common::getInt($request, 'area_id');
        // 获得所属区县街道下的员工一维数组[$k=>$v]
        $staffChildKV = CompanyStaff::getChildListKeyValByArea($request, $this, $city_id, $area_id,1 + 0);
        return  ajaxDataArr(1, $staffChildKV, '');;
    }
    /**
     * ajax增加员工数据-根据试卷id,多个,号分隔
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_add_staff(Request $request){
        $this->InitParams($request);
        $relations = ['subjectAnswer', 'answerType'];
        $subjectData = CompanyExam::addStaffData($request, $this, '', $relations);
        return ajaxDataArr(1, $subjectData, '');
    }

    /**
     * ajax增加员工数据-根据试卷id,多个,号分隔
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_add_staff_single(Request $request){
        $this->InitParams($request);
        $relations = ['subjectAnswer', 'answerType'];
        $subjectData = CompanyExam::addStaffData($request, $this, '', $relations);
        $subjectData = $subjectData[0] ?? [];
        return ajaxDataArr(1, $subjectData, '');
    }

}
