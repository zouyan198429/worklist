<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyWorkSendsBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWorkSends';


    /**
     * 工单派发记录
     *
     * @param obj $workObj 当前工单对象
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function saveSends($workObj, $operate_staff_id , $operate_staff_history_id){
        // 工单派发记录
        $workSends = [
            'company_id' => $workObj->company_id,
            'work_id' => $workObj->id,
            'work_status' => $workObj->status,
            'send_department_id' => $workObj->send_department_id,
            'send_department_name' => $workObj->send_department_name,
            'send_group_id' => $workObj->send_group_id,
            'send_group_name' => $workObj->send_group_name,
            'send_staff_id' => $workObj->send_staff_id,
            'send_staff_history_id' => $workObj->send_staff_history_id,
            'status' => 0, // 状态0可处理;1不可处理
            'operate_staff_id' => $operate_staff_id,// $workObj->operate_staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,// $workObj->operate_staff_history_id,
        ];
        $workSendsObj = null;
        Common::getObjByModelName("CompanyWorkSends", $workSendsObj);
        Common::create($workSendsObj, $workSends);
    }
}
