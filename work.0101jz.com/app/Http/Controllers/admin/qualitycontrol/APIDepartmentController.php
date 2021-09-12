<?php

namespace App\Http\Controllers\admin\qualitycontrol;

use App\Business\CompanyDepartment;
use App\Http\Controllers\admin\DepartmentController;
use App\Services\Common;
use Illuminate\Http\Request;

class APIDepartmentController extends DepartmentController
{
    public $source = 3;// 来源-1网站页面，2ajax；3小程序

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitAPIParams($request);
        return  CompanyDepartment::getList($request, $this, 1 + 0);
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
//        try{

        $this->InitAPIParams($request);
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
        // return ajaxDataArr(1, $resultDatas, '');
        return ajaxDataArr(1, $id, '');
//        } catch ( \Exception $e) {
//            $errmsg = $e->getMessage();
//            pr($errmsg);
//        }
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
        $this->InitAPIParams($request);
        return CompanyDepartment::delAjax($request, $this);
    }
}
