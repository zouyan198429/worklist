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
use Illuminate\Support\Facades\Schema;

class CompanyWorkController extends CompController
{
    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function addInit(Request $request)
    {

        $this->InitParams($request);
        $company_id = $this->company_id;
        // operate_no 操作编号
        $operate_no = Common::getInt($request, 'operate_no');

        // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
        if( ($this->cache_sel & 1) == 1){
            $cachePre = __FUNCTION__;// 缓存前缀
            $cacheKey = '';// 缓存键[没算前缀]
            $paramKeyValArr = $request->input();//[$company_id, $operate_no];// 关键参数  $request->input()
            $cacheResult =$this->getCacheData($cachePre,$cacheKey, $paramKeyValArr );
            if($cacheResult !== false) return $cacheResult;
        }

        $listData = [];
        //工单分类第一级 1
        if(($operate_no & 1) == 1 ){
            $workFirstList = CompanyWorkType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['type_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['workFirstList'] = Tool::formatArrKeyVal($workFirstList, 'id', 'type_name');
        }

        //工单来电类型 2
        if(($operate_no & 2) == 2 ) {
            $workCallTypeList = CompanyWorkCallerType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['workCallTypeList'] = Tool::formatArrKeyVal($workCallTypeList, 'id', 'type_name');
        }

        //业务标签 4
        if(($operate_no & 4) == 4 ) {
            $serviceTagList = CompanyServiceTags::select(['id', 'tag_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['serviceTagList'] = Tool::formatArrKeyVal($serviceTagList, 'id', 'tag_name');
        }
        // 业务时间 8
        if(($operate_no & 8) == 8 ) {
            $serviceTimeList = CompanyServiceTime::select(['id', 'time_name', 'second_num'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['serviceTimeList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'time_name');
            $listData['serviceTimeMinList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'second_num');
        }

        // 客户类型 16
        if(($operate_no & 16) == 16 ) {
            $customerTypeList = CompanyCustomerType::select(['id', 'type_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                ])
                ->get()->toArray();
            $listData['customerTypeList'] = Tool::formatArrKeyVal($customerTypeList, 'id', 'type_name');
        }

        // 客户地区 32
        if(($operate_no & 32) == 32 ) {
            $areaCityList = CompanyArea::select(['id', 'area_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['area_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['areaCityList'] = Tool::formatArrKeyVal($areaCityList, 'id', 'area_name');
        }

        // 部门信息 64
        if(($operate_no & 64) == 64 ) {
            $departmentFirstList = CompanyDepartment::select(['id', 'department_name'])
                ->orderBy('sort_num', 'desc')->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['department_parent_id', '=', 0],
                ])
                ->get()->toArray();
            $listData['departmentFirstList'] = Tool::formatArrKeyVal($departmentFirstList, 'id', 'department_name');
        }
//        $listData = [
//            'workFirstList' => $workFirstList,// 工单分类第一级
//            'workCallTypeList' => $page,// 工单来电类型
//            'serviceTagList' => $total,//业务标签
//            'serviceTimeList' => $aaa,// 业务时间
//            'customerTypeList' => $requestData,// 客户类型
//            'areaCityList' => $requestData,// 客户地区
//            'departmentFirstList' => $requestData,// 部门信息
//        ];
        $resultData = okArray($listData);
        // 缓存数据
        if( ($this->cache_sel & 2) == 2) {
            $this->setCacheData($cachePre, $cacheKey, $resultData, 60, 1);
        }
        return $resultData;
    }

    /**
     * 测试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        $staff_id = 1;
        $company_id = 1;
        $mainObj = null;
        Common::getObjByModelName("CompanyStaff", $mainObj);
        $historyObj = null;
        Common::getObjByModelName("CompanyStaffHistory", $historyObj);
        $historySearch = [
            'company_id' => $company_id,
            'staff_id' => $staff_id,
        ];

        Common::getHistory($mainObj, $staff_id, $historyObj,'company_staff_history', $historySearch, []);

        pr($historyObj->id);
//        // 获得员操作员工信息
//        $resourceInfoObj = CompanyStaff::find($staff_id);
//        if(empty($resourceInfoObj)){
//            throws("员工[" . $staff_id  . "] 不存在");
//        }
//        $versionNum = $resourceInfoObj->version_num;
//        // 获得所有字段
//        $historyColumns = Schema::getColumnListing('company_staff_history');
//
//        // 历史表需要保存的字段
//        $historyData = [];
//        foreach($historyColumns as $field){
//            if(isset($resourceInfoObj->$field) && !in_array($field,['id','updated_at']) ){
//                $historyData[$field] = $resourceInfoObj->$field;
//            }
//        }
//        if(isset($resourceInfoObj->updated_at)){
//            $historyData['created_at'] = $resourceInfoObj->updated_at;
//        }
//
//        // 查找历史员工表当前版本
//        $historyObj = CompanyStaffHistory::firstOrCreate(
//            [
//                'company_id' => $company_id,
//                'staff_id' => $staff_id,
//                'version_num' => $versionNum
//            ]
//            , $historyData
//        );
//        pr($historyObj->id);
       // if($staffInfoObj->created_at >)
      //  $staffInfoObj->version_num++;
       // $staffInfoObj->save();
       // var_dump($staffInfoObj);

//        $company_id = 1;
//        $call_number = "15829686965";
//        $customerList = CompanyCustomer::select(['id', 'call_num'])
//            ->where([
//                ['company_id', '=', $company_id],
//                ['call_number', '=', $call_number],
//            ])->limit(1)
//            ->get()->toArray();
       // return $customerList;
    }

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
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');
        // 工单号
        if($work_id <= 0){
            $save_data['work_num'] = Tool::order_sn($staff_id);
           // $save_data['work_num'] = Tool::createUniqueNumber(16);
        }
        //业务时间秒数  业务时间名称  到期时间
        $time_id = $save_data['time_id'] ?? 0;
        Common::judgeInitParams($request, 'time_id', $time_id);
        $serviceTimeObj = CompanyServiceTime::select(['id', 'time_name', 'second_num'])->find($time_id);
        if(empty($serviceTimeObj)){
            throws("没有业务时间信息");
        }
        $second_num = $serviceTimeObj->second_num;

        $save_data['time_name'] = $serviceTimeObj->time_name;
        $save_data['second_num'] = $second_num;
        $save_data['expiry_time']  = Carbon::now()->addMinute($second_num);

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

        // 来电类型名称
        $caller_type_id = $save_data['caller_type_id'] ?? 0;
        Common::judgeInitParams($request, 'caller_type_id', $caller_type_id);
        $workCallerTypeObj = CompanyWorkCallerType::select(['id', 'type_name'])->find($caller_type_id);
        if(empty($workCallerTypeObj)){
            throws("没有业务时间信息");
        }
        $save_data['caller_type_name'] = $workCallerTypeObj->type_name ?? '';

        // 获得员工历史记录id-- 工单接收员工
        $send_staff_id = $save_data['send_staff_id'] ?? 0;
        if($send_staff_id > 0){ // 指定了员工
            $sendStaffObj = null;
            Common::getObjByModelName("CompanyStaff", $sendStaffObj);
            $sendStaffHistoryObj = null;
            Common::getObjByModelName("CompanyStaffHistory", $sendStaffHistoryObj);
            $sendStaffHistorySearch = [
                'company_id' => $company_id,
                'staff_id' => $send_staff_id,
            ];

            Common::getHistory($sendStaffObj, $send_staff_id, $sendStaffHistoryObj,'company_staff_history', $sendStaffHistorySearch, []);
            $send_staff_history_id = $sendStaffHistoryObj->id;
            Common::judgeEmptyParams($request, '指派员工历史记录ID', $send_staff_history_id);
            $save_data['send_staff_history_id'] = $send_staff_history_id;
            $save_data['status'] = 2;//2待反馈工单
        }

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

        // 对客户信息进行处理 [更新或新建]
        $call_number = $save_data['call_number'] ?? '';
        // 获得客户信息:没有新建，有则更新来电次数
        $customer = [
            'company_id' => $company_id,// $save_data['company_id'] ?? '',
            'call_number' => $save_data['call_number'] ?? '',// 来电号码
            'type_id' => $save_data['type_id'] ?? '',// 客户类别id
            'customer_name' => $save_data['customer_name'] ?? '',// 客户姓名
            'sex' => $save_data['sex'] ?? '',// 性别0未知1男2女
            'city_id' => $save_data['city_id'] ?? '',// 县/区id
            'area_id' => $save_data['area_id'] ?? '',// 街道id
            'address' => $save_data['address'] ?? '',// 详细地址
            // 'call_num' => 1,// 来电次数,放后面单独自加1
            'last_call_date' => date("Y-m-d H:i:s",time()),// 上次到访时间
            'operate_staff_id' => $staff_id,//  操作员工id
            'operate_staff_history_id' => $operate_staff_history_id,// 操作员工历史id
        ];
        // 创建或更新客户信息
        $customerObj = null;
        Common::getObjByModelName("CompanyCustomer", $customerObj);

        $searchCustomerConditon = [
            'company_id' => $company_id,
            'call_number' => $call_number,
        ];

        Common::updateOrCreate($customerObj, $searchCustomerConditon, $customer );
        $customer_id = $customerObj->id;
        $save_data['customer_id'] = $customer_id;

         // 来电次数加1;;修改也算
        //if($work_id <=0 ){
            $customerObj->call_num++;
            $customerObj->save();
        //}

        // 判断版本号是否要+1

        $compareHistoryObj = null;
        Common::getObjByModelName("CompanyCustomerHistory", $compareHistoryObj);
        $customerHistorySearch = [
            'company_id' => $company_id,
            'customer_id' => $customer_id,
        ];

        $customerIgnoreFields = ['call_num', 'last_call_date'];

        $diffDataArr = Common::compareHistoryOrUpdateVersion($customerObj, $customer_id,
            $compareHistoryObj,'company_customer_history',
            $customerHistorySearch, $customerIgnoreFields, 0);
        if(! empty($diffDataArr)){// 客户有新信息，版本号+1
            // 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
            $customerObj->version_num++ ;
            $customerObj->save();
        }

        // 客户历史id
        //$customerObj = null;
        // Common::getObjByModelName("CompanyCustomer", $customerObj);
        $customerHistoryObj = null;
        Common::getObjByModelName("CompanyCustomerHistory", $customerHistoryObj);
        $customerHistorySearch = [
            'company_id' => $company_id,
            'customer_id' => $customer_id,
        ];

        Common::getHistory($customerObj, $customer_id, $customerHistoryObj,'company_customer_history', $customerHistorySearch, []);
        $customer_history_id = $customerHistoryObj->id;
        Common::judgeEmptyParams($request, '客户历史记录ID', $customer_history_id);
        $save_data['customer_history_id'] = $customer_history_id;


        // 保存或修改工单
        $workObj = null;
        Common::getObjByModelName("CompanyWork", $workObj);
        $workSearchConditon = [
            'company_id' => $company_id,
            'id' => $work_id,
        ];
        Common::updateOrCreate($workObj, $workSearchConditon, $save_data );
        return  okArray($workObj);
    }

}
