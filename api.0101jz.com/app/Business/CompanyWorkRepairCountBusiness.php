<?php
// 工单
namespace App\Business;


use App\Services\Common;
use App\Models\CompanyWorkRepairCount;
use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class CompanyWorkRepairCountBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWorkRepairCount';


    /**
     * 工单维修统计
     *
     * @param obj $workObj 当前工单对象
     * @param int $department_id 员工部门id
     * @param int $group_id 员工小组id
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    public static function workRepairCount($workObj, $department_id , $group_id, $operate_staff_id , $operate_staff_history_id){
        $currentNow = Carbon::now();
        // 工单来电统计

        $workRepairCountObj = null;
        Common::getObjByModelName("CompanyWorkRepairCount", $workRepairCountObj);
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

        Common::firstOrCreate($workRepairCountObj, $searchConditon, $updateFields );
        $workRepairCountObj->amount++;
        $workRepairCountObj->save();
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
            $where = $otherWhere;// array_merge($where, $otherWhere);
        }

        $select = 'sum(amount) as amount ';
        array_push($selectArr, $select);

        $obj = CompanyWorkRepairCount::where($where)
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

        $dataList = $obj->get()->toArray();

        return $dataList;
    }

    /**
     * 统计工单 --总量统计、按日期统计
     *
     * @param int $company_id 公司id
     * @param int $count_type 统计类型 0 总量统计, 1 日期统计[按日] ;2日期统计[按月];3日期统计[按年],4 其它统计[自己处理]
     * @param string $begin_date 开始日期 YYYY-MM-DD
     * @param string $end_date 结束日期 YYYY-MM-DD
     * @param int $operate_staff_id 添加员工id
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @return array
     * @author zouyan(305463219@qq.com)
     */
    public static function getCountAmount($company_id = 0, $count_type = 0, $begin_date = '', $end_date = '',  $operate_staff_id = 0, $department_id = 0, $group_id = 0){
        $selectArr = [];// company_id,operate_staff_id,count_date,SUM(amount) AS amount
        $otherWhere = [];
        $inWhereArr = [];
        $groupByArr = [];
        $havingRaw = '';
        $orderByArr = [];


        $temDataArr = ['amount' => 0];
        if($company_id > 0){
            array_push($otherWhere, ['company_id', '=', $company_id]);
            array_push($selectArr, 'company_id');
            array_push($groupByArr, 'company_id');
            $temDataArr['company_id'] = $company_id;
        }

        if($operate_staff_id > 0){// 操作人员统计
            array_push($otherWhere, ['operate_staff_id', '=', $operate_staff_id]);
            array_push($selectArr, 'operate_staff_id');
            array_push($groupByArr, 'operate_staff_id');
            $temDataArr['operate_staff_id'] = $operate_staff_id;
        }

        if($department_id > 0){// 部门统计
            array_push($otherWhere, ['department_id', '=', $department_id]);
            array_push($selectArr, 'department_id');
            array_push($groupByArr, 'department_id');
            $temDataArr['department_id'] = $department_id;
        }

        if($group_id > 0){// 小组统计
            array_push($otherWhere, ['group_id', '=', $group_id]);
            array_push($selectArr, 'group_id');
            array_push($groupByArr, 'group_id');
            $temDataArr['group_id'] = $group_id;
        }

        if(!empty($begin_date)){
            array_push($otherWhere, ['count_date', '>=', $begin_date]);
        }

        if(in_array($count_type, [1,2,3]) && empty($end_date)) $end_date = date("Y-m-d");

        if(!empty($end_date)){
            array_push($otherWhere, ['count_date', '<=', $end_date]);
        }

        switch ($count_type)
        {
            case 1:// 按日统计
                array_push($selectArr, 'count_date');
                array_push($groupByArr, 'count_date');
                $orderByArr['count_date'] = 'asc';
                $temDataArr['count_date'] = '';
                break;
            case 2:// 2日期统计[按月]
                array_push($selectArr, 'count_year', 'count_month');
                array_push($groupByArr, 'count_year', 'count_month');
                $orderByArr['count_year'] = 'asc';
                $orderByArr['count_month'] = 'asc';
                $temDataArr['count_year'] = '';
                $temDataArr['count_month'] = '';
                break;
            case 3:// 3日期统计[按年]
                array_push($selectArr, 'count_year');
                array_push($groupByArr, 'count_year');
                $orderByArr['count_year'] = 'asc';
                $temDataArr['count_year'] = '';
                break;
            default:
        }
        $countList = self::getCount($company_id, $selectArr, $otherWhere, $inWhereArr, $groupByArr, $havingRaw , $orderByArr);

        //直接返回
        if( !in_array($count_type, [1,2,3]) ) return $countList;

        // 没有开始日期
        if(empty($begin_date)){
            $begin_date = $countList[0]['count_date'] ?? '';
            // 没有数据
            if(empty($begin_date)){
                if(empty($end_date)) return $countList;
                $begin_date = $end_date;
            }
        }

        $formatCountList = [];
        switch ($count_type)
        {
            case 1:// 按日统计
                $formatCountList = Tool::arrUnderReset($countList, 'count_date', 1);
                $dataRange = Tool::dateRange($begin_date, $end_date);
                break;
            case 2:// 2日期统计[按月]
                foreach($countList as $v){
                    $count_year = $v['count_year'];
                    $count_month = sprintf('%02s', $v['count_month']);// 2位，不够左补0
                    $v['count_month'] = $count_month;
                    $formatCountList[$count_year . $count_month] = $v;
                }
                $dataRange = Tool::showMonthRange($begin_date, $end_date);
                break;
            case 3:// 3日期统计[按年]
                $formatCountList = Tool::arrUnderReset($countList, 'count_year', 1);
                $dataRange = Tool::showYearRange($begin_date, $end_date);
                break;
            default:
        }

        $returnData = [];
        foreach($dataRange as $temDate){
            if(isset($formatCountList[$temDate])){
                $returnData[] = $formatCountList[$temDate];
            }else{
                $temDatas = $temDataArr;
                switch ($count_type)
                {
                    case 1:// 按日统计
                        $temDatas['count_date'] = $temDate;
                        break;
                    case 2:// 2日期统计[按月]
                        $temDatas['count_year'] = substr($temDate,0,4);// 前4位
                        $temDatas['count_month'] = substr($temDate,-2);;// 后2位
                        break;
                    case 3:// 3日期统计[按年]
                        $temDatas['count_year'] = $temDate;
                        break;
                    default:
                }
                $returnData[] = $temDatas;
            }
        }
        $countList = $returnData;
        return $countList;
    }
}
