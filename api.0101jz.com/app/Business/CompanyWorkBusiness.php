<?php
// 工单
namespace App\Business;

use App\Models\Company;
use App\Models\CompanyWork;
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
     * @param int $staff_id 员工id
     * @param int $is_read 是否已读
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function getCount($company_id, $staff_id = 0 , $status = 0){
        $where = [
            ['company_id', '=', $company_id],
            // ['send_staff_id', '=', $staff_id],
            ['status', '=', $status],
        ];
        if($staff_id > 0){
            array_push($where,['send_staff_id', '=', $staff_id]);
        }
        return CompanyWork::where($where)->count();
    }

    /**
     * 通过id修改记录
     *
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @return array $saveData 需要更新的记录 一维数组 ['字段'=>'字段值']
     * @author zouyan(305463219@qq.com)
     */
    public static function saveById($company_id, $work_id, $saveData = []){
        $workObj = CompanyWork::find($work_id);

        foreach($saveData as $field => $val){
            $workObj->{$field} = $val;
        }
        return $workObj->save();
    }
}
