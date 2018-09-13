<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
use App\Business\CompanyPosition;
use App\Business\CompanyStaff;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class StaffController extends AdminController
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
        return view('admin.staff.index', $reDataArr);
    }


    /**
     * 添加
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            'department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyStaff::getInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);
        // 获得第一级职位分类一维数组[$k=>$v]
        $reDataArr['position_kv'] = CompanyPosition::getListKeyVal($request, $this, 1 + 0);

        return view('admin.staff.add', $reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $work_num = Common::get($request, 'work_num');
        $department_id = Common::getInt($request, 'department_id');
        $group_id = Common::getInt($request, 'group_id');
        $position_id = Common::getInt($request, 'position_id');
        $real_name = Common::get($request, 'real_name');
        $sex = Common::getInt($request, 'sex');
        $mobile = Common::get($request, 'mobile');
        $tel = Common::get($request, 'tel');
        $qq_number = Common::get($request, 'qq_number');
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');

        $saveData = [
            'work_num' => $work_num,
            'department_id' => $department_id,
            'group_id' => $group_id,
            'position_id' => $position_id,
            'real_name' => $real_name,
            'sex' => $sex,
            'mobile' => $mobile,
            'tel' => $tel,
            'qq_number' => $qq_number,
            'admin_username' => $admin_username,
        ];
        if($admin_password != '' || $sure_password != ''){
            if ($admin_password != $sure_password){
                return ajaxDataArr(0, null, '密码和确定密码不一致！');
            }
            $saveData['admin_password'] = $admin_password;
        }

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyStaff::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
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
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyStaff::delAjax($request, $this);
    }

}
