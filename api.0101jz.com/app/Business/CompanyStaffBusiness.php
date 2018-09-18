<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyStaffBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyStaff';


    /**
     * 员工获得主表+历史表对象
     *
     * @param obj $workObj 员工主对象
     * @param obj $staffHistoryObj 员工历史对象
     * @param int $company_id 公司id
     * @param int $staff_id 员工id
     * @return int 员工历史记录id
     * @author zouyan(305463219@qq.com)
     */
    public static function getHistoryStaff(&$staffObj = null , &$staffHistoryObj = null, $company_id = 0, $staff_id = 0 ){

        // 获是员工历史记录id-- 操作员工
        //$staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        // $staffHistoryObj = null;
        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
        $StaffHistorySearch = [
            'company_id' => $company_id,
            'staff_id' => $staff_id,
        ];

        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);
        $operate_staff_history_id = $staffHistoryObj->id;
        return $operate_staff_history_id;

    }
}
