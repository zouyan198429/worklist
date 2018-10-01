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
            'count_date' => $currentNow->toDateString(),
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

    /**
     * 统计工单
     *
     * @param int $company_id 公司id
     * @param int $staff_id 员工id
     * @param int $status 状态
     * @param int $operate_staff_id 添加员工id
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function getCount($company_id, $staff_id = 0 , $status = 0, $operate_staff_id = 0, $otherWhere = [])
    {
        $where = [
            ['company_id', '=', $company_id],
            // ['send_staff_id', '=', $staff_id],
        ];
        if (!empty($otherWhere)) {
            $where = array_merge($where, $otherWhere);
        }

        if ($staff_id > 0) {
            array_push($where, ['send_staff_id', '=', $staff_id]);
        }

        if ($operate_staff_id > 0) {
            array_push($where, ['operate_staff_id', '=', $operate_staff_id]);
        }
        $dataList = CompanyWorkDoing::whereIn('status', $status)->where($where)
            ->select(DB::raw('count(*) as status_count, status'))
            ->groupBy('status')
            ->get();
        $requestData = [];
        foreach ($dataList as $info) {
            $requestData[$info['status']] = $info['status_count'];
        }
        foreach ($status as $temStatus) {
            if (isset($requestData[$temStatus])) {
                continue;
            }
            $requestData[$temStatus] = 0;
        }
        return $requestData;
    }
}
