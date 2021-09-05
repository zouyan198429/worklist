<?php
// 工单
namespace App\Business;


use App\Services\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

/**
 *
 */
class CompanyWorkLogBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWorkLog';

    /**
     * 工单日志
     *
     * @param obj $workObj 当前工单对象
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @param string $logContent 操作说明
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function saveWorkLog($workObj , $operate_staff_id , $operate_staff_history_id, $logContent){
        // 工单操作日志
        $workLog = [
            'company_id' => $workObj->company_id,
            'work_id' => $workObj->id,
            'work_status_new' => $workObj->status,
            'content' => $logContent,// "创建工单", // 操作内容
            'operate_staff_id' => $operate_staff_id,//$workObj->operate_staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,//$workObj->operate_staff_history_id,
        ];
        $workLogObj = null;
        Common::getObjByModelName("CompanyWorkLog", $workLogObj);
        Common::create($workLogObj, $workLog);
    }


}
