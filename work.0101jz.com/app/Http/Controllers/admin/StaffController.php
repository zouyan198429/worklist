<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
use App\Business\CompanyStaff;
use App\Http\Controllers\AdminController;
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
        // 获得第一级分类
        $parentData = CompanyDepartment::getChildList($request, $this, 0, 1 + 0);
        $reDataArr['parent_list'] = $parentData['result']['data_list'] ?? [];

        return view('admin.staff.index', $reDataArr);
    }


    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;

        // 获得第一级分类
        $parentData = CompanyDepartment::getChildList($request, $this, 0, 1 + 0);
        $reDataArr['parent_list'] = $parentData['result']['data_list'] ?? [];
        return view('manage.staff.list', $reDataArr);
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
//        $resultDatas = [
//            'id' => 0,
//            'account_issuper' =>0,
//            'account_status' => 0,
//
//        ];
//        if ($id > 0) { // 获得详情数据
//            $resultDatas = $this->getinfoApi($this->model_name, '', $this->company_id , $id);
//            // 判断权限
//            $judgeData = [
//                'company_id' => $this->company_id,
//            ];
//            $this->judgePowerByObj($request,$resultDatas, $judgeData );
//        }
//        $remarks = $resultDatas['remarks'] ?? '';
//        $resultDatas['remarks'] = replace_enter_char($remarks,2);

        return view('manage.staff.add', $reDataArr);
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
