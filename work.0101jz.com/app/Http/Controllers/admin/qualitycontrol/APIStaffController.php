<?php

namespace App\Http\Controllers\admin\qualitycontrol;

use App\Business\CompanyStaff;
use App\Http\Controllers\admin\StaffController;
use App\Services\Common;
use Illuminate\Http\Request;

class APIStaffController extends StaffController
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
        return  CompanyStaff::getList($request, $this, 2 + 4);
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
        $work_num = Common::get($request, 'work_num');
        $department_id = Common::getInt($request, 'department_id');
        $group_id = Common::getInt($request, 'group_id');
        $position_id = Common::getInt($request, 'position_id');
        $real_name = Common::get($request, 'real_name');
        $sex = Common::getInt($request, 'sex');
        $mobile = Common::get($request, 'mobile');
//        $tel = Common::get($request, 'tel');
//        $qq_number = Common::get($request, 'qq_number');
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
//            'tel' => $tel,
//            'qq_number' => $qq_number,
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
//        return ajaxDataArr(1, $resultDatas, '');
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
        return CompanyStaff::delAjax($request, $this);
    }
}
