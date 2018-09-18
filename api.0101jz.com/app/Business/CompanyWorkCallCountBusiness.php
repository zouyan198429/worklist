<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyWorkCallCountBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWorkCallCount';


    /**
     * 工单来电统计
     *
     * @param obj $workObj 当前工单对象
     * @param int $department_id 员工部门id
     * @param int $group_id 员工小组id
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function workCallCount($workObj, $department_id , $group_id, $operate_staff_id , $operate_staff_history_id){
        $currentNow = Carbon::now();
        // 工单来电统计

        $workCallCountObj = null;
        Common::getObjByModelName("CompanyWorkCallCount", $workCallCountObj);
        $searchConditon = [
            'company_id' => $workObj->company_id,
            'department_id' => $department_id,
            'group_id' => $group_id,
            'operate_staff_id' => $operate_staff_id,// $workObj->operate_staff_id,
            'count_year' => $currentNow->year,
            'count_month' => $currentNow->month,
            'count_day' => $currentNow->day,
        ];
        $updateFields = [
            'amount' => 0,
            'operate_staff_history_id' => $operate_staff_history_id,//$workObj->operate_staff_history_id,
        ];

        Common::firstOrCreate($workCallCountObj, $searchConditon, $updateFields );
        $workCallCountObj->amount++;
        $workCallCountObj->save();
    }

}
