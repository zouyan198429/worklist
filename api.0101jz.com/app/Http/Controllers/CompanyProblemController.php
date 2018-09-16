<?php

namespace App\Http\Controllers;


use App\Models\CompanyArea;
use App\Models\CompanyCustomer;
use App\Models\CompanyCustomerHistory;
use App\Models\CompanyCustomerType;
use App\Models\CompanyDepartment;
use App\Models\CompanyServiceTags;
use App\Models\CompanyServiceTime;
use App\Models\CompanyStaff;
use App\Models\CompanyStaffHistory;
use App\Models\CompanyWorkCallerType;
use App\Models\CompanyWorkType;
use App\Services\Common;
use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompanyProblemController extends CompController
{
    /**
     * 添加/修改
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add_save(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $problem_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');


        //县/区id[分类一级]
        $city_id = $save_data['city_id'] ?? 0;
        Common::judgeInitParams($request, 'city_id', $city_id);
        $cityObj = CompanyArea::select(['id', 'area_name'])->find($city_id);
        if(empty($cityObj)){
            throws("没有区/县信息");
        }
        $save_data['city_name'] = $cityObj->area_name ?? '';

        //街道id[分类二级]
        $area_id = $save_data['area_id'] ?? 0;
        Common::judgeInitParams($request, 'area_id', $area_id);
        $areaObj = CompanyArea::select(['id', 'area_name'])->find($area_id);
        if(empty($areaObj)){
            throws("没有街道信息");
        }
        $save_data['area_name'] = $areaObj->area_name ?? '';


        //业务分类id[分类一级]
        $work_type_id = $save_data['work_type_id'] ?? 0;
        Common::judgeInitParams($request, 'work_type_id', $work_type_id);
        $workTypeObj = CompanyWorkType::select(['id', 'type_name'])->find($work_type_id);
        if(empty($workTypeObj)){
            throws("没有业务时间信息");
        }
        $save_data['type_name'] = $workTypeObj->type_name ?? '';

        //业务id[分类二级]
        $business_id = $save_data['business_id'] ?? 0;
        Common::judgeInitParams($request, 'business_id', $business_id);
        $businessWorkTypeObj = CompanyWorkType::select(['id', 'type_name'])->find($business_id);
        if(empty($businessWorkTypeObj)){
            throws("没有业务时间信息");
        }
        $save_data['business_name'] = $businessWorkTypeObj->type_name ?? '';

        DB::beginTransaction();
        try {
                // 获是员工历史记录id-- 操作员工
            $staffObj = null;
            Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
            Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
            $StaffHistorySearch = [
                'company_id' => $company_id,
                'staff_id' => $staff_id,
            ];

            Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);
            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

            $save_data['operate_staff_id'] = $staff_id;
            $save_data['operate_staff_history_id'] = $operate_staff_history_id;
            $save_data['operate_staff_name'] = $staffHistoryObj->real_name;

            // 保存或修改反馈问题
            $problemObj = null;
            Common::getObjByModelName("CompanyProblem", $problemObj);
            $problemSearchConditon = [
                'company_id' => $company_id,
                'id' => $problem_id,
            ];

            Common::updateOrCreate($problemObj, $problemSearchConditon, $save_data );


        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray($problemObj);
    }

}
