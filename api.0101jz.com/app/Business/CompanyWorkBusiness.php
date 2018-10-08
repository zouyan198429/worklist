<?php
// 工单
namespace App\Business;

use App\Models\Company;
use App\Models\CompanyWork;
use App\Models\CompanyWorkDoing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class CompanyWorkBusiness extends BaseBusiness
{
    protected static $model_name = 'CompanyWork';
    protected static $model_doing_name = 'CompanyWorkDoing';


    // 状态 0新工单2待反馈工单[处理中];4待回访工单;8已完成工单
    public static  $status_arr = [
        //'0' => '待确认接单',
        '1' => '待确认',
        '2' => '处理中',
        '4' => '待回访',
        '8' => '已完成',
    ];

    public static $focusTime = 60;// 重点关注 判断时间，单位分钟 1小时

    /**
     * 工单结单
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
//    public static function autoSiteMsg(){
//        $currentNow = Carbon::now();
//        DB::beginTransaction();
//        try {
//            $companysObj = Company::get();
//            foreach($companysObj as $companyObj){
//                // 重点关注
//                $focusWhere = [
//                    ['company_id', '=', $companyObj->id],
//                    ['is_focus', '=', 0],
//                    ['status', '<>', 8],
//                    ['expiry_time', '>=', $currentNow->toDateTimeString()],
//                    ['expiry_time', '<=', $currentNow->addMinutes(self::$focusTime)],
//                ];
//                $worksList = CompanyWork::where($focusWhere)->get();
//
//                // CompanyStaffCustomer::where($where)->update($batchModifyStaffCustomer);
//                // 发送消息
//                foreach($worksList as $work){
//                    // 发送消息
//                    CompanySiteMsgBusiness::sendSiteMsg($work, null, null, '工单即将逾期提醒', '工单[' . $work->work_num . ']即将逾期提醒,请尽快处理！');
//                    $work->is_focus = 1;
//                    $work->save();
//                }
//                // 逾期处理
//                $overdueWhere = [
//                    ['company_id', '=', $companyObj->id],
//                    ['is_overdue', '=', 0],
//                    ['status', '<>', 8],
//                    ['expiry_time', '<', $currentNow->toDateTimeString()],
//                ];
//                CompanyWork::where($overdueWhere)->update(['is_overdue' => 1]);
//            }
//        } catch ( \Exception $e) {
//            DB::rollBack();
//            throws('工单即将逾期提醒失败；信息[' . $e->getMessage() . ']');
//            // throws($e->getMessage());
//        }
//        DB::commit();
//
//    }

    /**
     * 按状态统计工单数量
     *
     * @param int $company_id 公司id
     * @param int $send_department_id 派到部门id
     * @param int $send_group_id 派到小组id
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param int $staff_id 员工id
     * @param int $status 状态
     * @param int $operate_staff_id 添加员工id
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function getCount($company_id, $send_department_id = 0, $send_group_id = 0, $department_id = 0, $group_id = 0, $staff_id = 0 , $status = 0, $operate_staff_id = 0, $otherWhere = []){
        $where = [
            ['company_id', '=', $company_id],
            // ['send_staff_id', '=', $staff_id],
            // ['status', '=', $status],
        ];
        if(!empty($otherWhere)){
            $where = array_merge($where, $otherWhere);
        }

        if(is_numeric($status)){
            array_push($where,['status', '=', $status]);
        }

        if($send_department_id > 0){
            array_push($where,['send_department_id', '=', $send_department_id]);
        }

        if($send_group_id > 0){
            array_push($where,['send_group_id', '=', $send_group_id]);
        }

        if($department_id > 0){
            array_push($where,['department_id', '=', $department_id]);
        }

        if($group_id > 0){
            array_push($where,['group_id', '=', $group_id]);
        }

        if($staff_id > 0){
            array_push($where,['send_staff_id', '=', $staff_id]);
        }

        if($operate_staff_id > 0){
            array_push($where,['operate_staff_id', '=', $operate_staff_id]);
        }

        if(is_array($status)){
            if(in_array(8,$status)){
                $dataCount = CompanyWork::whereIn('status',$status)->where($where)->count();
            }else{
                $dataCount = CompanyWorkDoing::whereIn('status',$status)->where($where)->count();
            }
        }else{
            if($status == '8'){
                $dataCount = CompanyWork::where($where)->count();
            }else{
                $dataCount = CompanyWorkDoing::where($where)->count();
            }
        }
        return $dataCount;
    }

    /**
     * 按状态分组统计工单数量
     *
     * @param int $company_id 公司id
     * @param array $status 状态  一维数组
     * @param int $send_department_id 派到部门id
     * @param int $send_group_id 派到小组id
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @return array ['状态'=> 数量,...]
     * @author zouyan(305463219@qq.com)
     */
    public static function getGroupCount($company_id, $status, $send_department_id = 0, $send_group_id = 0, $department_id = 0, $group_id = 0, $staff_id = 0, $operate_staff_id = 0, $otherWhere = []){
        $where = [
            ['company_id', '=', $company_id],
            // ['send_staff_id', '=', $staff_id],
            // ['status', '=', $status],
        ];
        if(!empty($otherWhere)){
            $where = array_merge($where, $otherWhere);
        }

        if($send_department_id > 0){
            array_push($where,['send_department_id', '=', $send_department_id]);
        }

        if($send_group_id > 0){
            array_push($where,['send_group_id', '=', $send_group_id]);
        }

        if($department_id > 0){
            array_push($where,['department_id', '=', $department_id]);
        }

        if($group_id > 0){
            array_push($where,['group_id', '=', $group_id]);
        }

        if($staff_id > 0){
            array_push($where,['send_staff_id', '=', $staff_id]);
        }

        if($operate_staff_id > 0){
            array_push($where,['operate_staff_id', '=', $operate_staff_id]);
        }

        if(empty($status) || in_array(8, $status)){
            $dataList = CompanyWork::whereIn('status',$status)->where($where)
                ->select(DB::raw('count(*) as status_count, status'))
                ->groupBy('status')
                ->get();
        } else {
            $dataList = CompanyWorkDoing::whereIn('status',$status)->where($where)
                ->select(DB::raw('count(*) as status_count, status'))
                ->groupBy('status')
                ->get();
        }

        $requestData = [];
        foreach($dataList as $info){
            $requestData[$info['status']] = $info['status_count'];
        }
        foreach ($status as $temStatus){
            if(isset($requestData[$temStatus])){
                continue;
            }
            $requestData[$temStatus] = 0;
        }
        return $requestData;
    }

    /**
     * 根据工单号查询工单
     *
     * @param int $db_num 操作库 0 主库  1 doing库
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @return array 工单信息 一维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkInfo($db_num = 0, $company_id, $work_id, $otherWhere = []){
        $where = [
            ['company_id', '=', $company_id],
            // ['work_id', '=', $work_id],
        ];
        if(!empty($otherWhere)){
            $where = array_merge($where, $otherWhere);
        }
        if($db_num == 0 ){
            array_push($where,['id', '=', $work_id]);
            $worksObj = CompanyWork::where($where)->limit(1)->get();
        }else{
            array_push($where,['work_id', '=', $work_id]);
            $worksObj = CompanyWorkDoing::where($where)->limit(1)->get();
        }
        return $worksObj[0] ?? [];
    }

    /**
     * 通过id修改记录
     *
     * @param int $db_num 操作库 0 主库  1 doing库
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @return array $saveData 需要更新的记录 一维数组 ['字段'=>'字段值']
     * @author zouyan(305463219@qq.com)
     */
    public static function saveById($db_num = 0, $company_id = 0, $work_id, $saveData = []){
        if($db_num == 0){
            $workObj = CompanyWork::find($work_id);
        }else{
            $workObj = self::getWorkInfo($db_num, $company_id, $work_id);
            // $workObj = CompanyWorkDoing::find($work_id);
        }

        foreach($saveData as $field => $val){
            $workObj->{$field} = $val;
        }
        return $workObj->save();
    }


    /**
     * 通过id删除记录
     *
     * @param int $db_num 操作库 0 主库  1 doing库
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @param array $otherWhere 其它条件[['company_id', '=', $company_id],...]
     * @author zouyan(305463219@qq.com)
     */
    public static function delById($db_num = 0, $company_id, $work_id, $otherWhere = []){
        $where = [
            ['company_id', '=', $company_id],
            // ['work_id', '=', $work_id],
        ];
        if(!empty($otherWhere)){
            $where = array_merge($where, $otherWhere);
        }
        if($db_num == 0){
            array_push($where,['id', '=', $work_id]);
            return CompanyWork::find($where)->limit(1)->delete();
        }else{
            array_push($where,['work_id', '=', $work_id]);
            return CompanyWorkDoing::where($where)->limit(1)->delete();
        }
    }
}
