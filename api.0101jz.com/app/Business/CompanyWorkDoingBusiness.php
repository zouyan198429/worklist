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
class CompanyWorkDoingBusiness extends CompanyWorkBusiness
{
    protected static $model_name = 'CompanyWorkDoing';

    /**
     * 工单结单
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public static function autoSiteMsg(){
        DB::beginTransaction();
        try {
            $companysObj = Company::get();
            $currentNow = Carbon::now();
            foreach($companysObj as $companyObj){
                // 重点关注
                $focusWhere = [
                    ['company_id', '=', $companyObj->id],
                    ['is_focus', '=', 0],
                    ['status', '<>', 8],
                    ['expiry_time', '>=', Carbon::now()->toDateTimeString()],
                    ['expiry_time', '<=', Carbon::now()->addMinutes(self::$focusTime)],
                ];
                $worksList = CompanyWorkDoing::where($focusWhere)->get();

                // CompanyStaffCustomer::where($where)->update($batchModifyStaffCustomer);
                // 发送消息
                $work_ids = [];
                $doingId = [];
                foreach($worksList as $work){
                    // 发送消息
                    CompanySiteMsgBusiness::sendSiteMsg($work, null, null,
                        '工单即将逾期提醒', '工单[' . $work->work_num . ']即将逾期提醒,请尽快处理！');
                    // $work->is_focus = 1;
                   //  $work->save();
                    array_push($work_ids, $work->work_id);
                    array_push($doingId, $work->id);
                }
                $mainWorkWhere = [
                    ['company_id', '=', $companyObj->id],
                    ['is_focus', '=', 0],
                    ['status', '<>', 8],
                ];
                if(count($work_ids) > 0) {
                    $updateData = ['is_focus' => 1];
                    CompanyWork::whereIn('id', $work_ids)->where($mainWorkWhere)->update($updateData);
                    CompanyWorkDoing::whereIn('id', $doingId)->where($mainWorkWhere)->update($updateData);
                }
                // 逾期判断
                $overdueWhere = [
                    ['company_id', '=', $companyObj->id],
                    ['is_overdue', '=', 0],
                    ['status', '<>', 8],
                    ['expiry_time', '<', Carbon::now()->toDateTimeString()],
                ];

                $overdueWorksObj = CompanyWorkDoing::where($overdueWhere)->get();
                $overdueWorksList = $overdueWorksObj->toArray();
                $overdueIds = array_column($overdueWorksList, 'work_id');
                $doingIds = array_column($overdueWorksList, 'id');
                if(count($overdueIds) > 0){
                    $updateData = ['is_overdue' => 1];
                    CompanyWork::whereIn('id', $overdueIds)->where($overdueWhere)->update($updateData);
                    CompanyWorkDoing::whereIn('id', $doingIds)->where($overdueWhere)->update($updateData);
//                    foreach($overdueWorksObj as $temWork){
                        // 发送消息
//                        CompanySiteMsgBusiness::sendSiteMsg($temWork, null, null,
//                            '工单已经逾期提醒', '工单[' . $temWork->work_num . ']已经逾期,请尽快处理！');

//                    }
                }
            }
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('工单即将逾期提醒失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();


    }

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
        return CompanyWorkDoing::where($where)->count();
    }

    /**
     * 根据工单号查询工单
     *
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @return array 工单信息 一维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getWorkInfo($company_id, $work_id){
        $where = [
            ['company_id', '=', $company_id],
            ['work_id', '=', $work_id],
        ];
        $worksObj = CompanyWorkDoing::where($where)->limit(1)->get();
        return $worksObj[0] ?? [];
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
        $workObj = self::getWorkInfo($company_id, $work_id);

        foreach($saveData as $field => $val){
            $workObj->{$field} = $val;
        }
        return $workObj->save();
    }

    /**
     * 通过id删除记录
     *
     * @param int $company_id 公司id
     * @param int $work_id 工单id
     * @author zouyan(305463219@qq.com)
     */
    public static function delById($company_id, $work_id){
        $where = [
            ['company_id', '=', $company_id],
            ['work_id', '=', $work_id],
        ];
        return CompanyWorkDoing::where($where)->limit(1)->delete();
    }
}
