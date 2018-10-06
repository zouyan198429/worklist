<?php
// 工单
namespace App\Business;


use App\Models\CompanyWorkCallCount;
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
     * @param array $selectArr 返回字段数组 一维
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @param array $inWhereArr in条件 一维数组 ['字段'->[数组值]]
     * @param array $groupByArr 分组字段数组 一维
     * @param string $havingRaw 分组过滤条件
     * @param array $orderByArr in条件 一维数组 ['字段'->'asc/desc']
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getCount($company_id, $selectArr = [] , $otherWhere = [], $inWhereArr = [], $groupByArr = [], $havingRaw = '', $orderByArr = [])
    {
        $where = [
            ['company_id', '=', $company_id],
            // ['send_staff_id', '=', $staff_id],
        ];
        if (!empty($otherWhere)) {
            $where = array_merge($where, $otherWhere);
        }

        $select = 'sum(amount) as amount ';
        array_push($selectArr, $select);
        $obj = CompanyWorkCallCount::where($where)
            ->select(DB::raw(implode(',', $selectArr)));

        foreach($inWhereArr as $field => $inWhere){
            $obj->whereIn($field,$inWhere);
        }

        foreach($groupByArr as $group){
            $obj->groupBy($group);
        }

        if(!empty($havingRaw)){
            $obj->havingRaw($havingRaw);
        }

        foreach($orderByArr as $field => $order){
            $obj->orderBy($field, $order);
        }

        $dataList = $obj->get();

        return $dataList;
    }
}
