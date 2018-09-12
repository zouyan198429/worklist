<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class DepartmentController extends AdminController
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
        return view('admin.department.index', $reDataArr);
    }

    /**
     * 添加/修改
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
            'department_parent_id' => -1,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyDepartment::getInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);


        // 获得第一级分类
        $parentData = CompanyDepartment::getChildList($request, $this, 0, 1 + 0);
        $reDataArr['parent_list'] = $parentData['result']['data_list'] ?? [];

        return view('admin.department.add', $reDataArr);
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
        return  CompanyDepartment::getList($request, $this, 1 + 0);
    }

    /**
     * ajax获得获得子类部门数据--一维
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $parent_id = Common::getInt($request, 'parent_id');
        // 获得第一级部门分类一维数组[$k=>$v]
        $departmentChildKV = CompanyDepartment::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $departmentChildKV, '');;
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
        $company_id = $this->company_id;
        $department_name = Common::get($request, 'department_name');
        $sort_num = Common::getInt($request, 'sort_num');
        $department_parent_id = Common::getInt($request, 'department_parent_id');

        $saveData = [
            'department_name' => $department_name,
            'sort_num' => $sort_num,
            'department_parent_id' => $department_parent_id,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyDepartment::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
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
        return CompanyDepartment::delAjax($request, $this);
    }

}
