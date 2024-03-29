<?php

namespace App\Http\Controllers;


use App\Business\CompanyAreaBusiness;
use App\Business\CompanyBusiness;
use App\Business\CompanyCustomerTypeBusiness;
use App\Business\CompanyDepartmentBusiness;
use App\Business\CompanyProblemTypeBusiness;
use App\Business\CompanyServiceTagsBusiness;
use App\Business\CompanyServiceTimeBusiness;
use App\Business\CompanySiteMsgBusiness;
use App\Business\CompanyStaffBusiness;
use App\Business\CompanyWorkBusiness;
use App\Business\CompanyWorkCallCountBusiness;
use App\Business\CompanyWorkCallerTypeBusiness;
use App\Business\CompanyWorkDoingBusiness;
use App\Business\CompanyWorkLogBusiness;
use App\Business\CompanyWorkRepairCountBusiness;
use App\Business\CompanyWorkSendsBusiness;
use App\Business\CompanyWorkTypeBusiness;
use App\Models\CompanyArea;
use App\Models\CompanyCustomerHistory;
use App\Models\CompanyCustomerType;
use App\Models\CompanyDepartment;
use App\Models\CompanyServiceTime;
use App\Models\CompanyStaffCustomer;
use App\Models\CompanyWork;
use App\Models\CompanyWorkCallerType;
use App\Models\CompanyWorkType;
use App\Services\Common;
use App\Services\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $cacheResult =$this->getCacheData($cachePre,$cacheKey, $paramKeyValArr, 1, 1 + 2 );
            if($cacheResult !== false) return $cacheResult;
        }

        $listData = [];
        //工单分类第一级 1
        if(($operate_no & 1) == 1 ){
             $listData['workFirstList'] = CompanyWorkTypeBusiness::getWorkTypeKeyVals($company_id, 0, true) ?? [];
        }

        //工单来电类型 2
        if(($operate_no & 2) == 2 ) {
            $listData['workCallTypeList'] = CompanyWorkCallerTypeBusiness::getWorkCallerTypeKeyVals($company_id, true) ?? [];
        }

        //业务标签 4
        if(($operate_no & 4) == 4 ) {
            $listData['serviceTagList'] = CompanyServiceTagsBusiness:: getServiceTagsKeyVals($company_id, true) ?? [];
        }
        // 业务时间 8
        if(($operate_no & 8) == 8 ) {
            $serviceTimeList = CompanyServiceTimeBusiness::getServiceTimeKeyVals($company_id, false) ?? [];
            $listData['serviceTimeList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'time_name');
            $listData['serviceTimeMinList'] = Tool::formatArrKeyVal($serviceTimeList, 'id', 'second_num');
        }

        // 客户类型 16
        if(($operate_no & 16) == 16 ) {
            $listData['customerTypeList'] = CompanyCustomerTypeBusiness::getCustomerTypeKeyVals($company_id, true) ?? [];
        }

        // 客户地区 32
        if(($operate_no & 32) == 32 ) {
            $listData['areaCityList'] = CompanyAreaBusiness::getWorkTypeKeyVals($company_id, 0, true) ?? [];
        }

        // 部门信息 64
        if(($operate_no & 64) == 64 ) {
            $listData['departmentFirstList'] = CompanyDepartmentBusiness::getDepartmentKeyVals($company_id,  0,  true) ?? [];

        }

        //反馈分类第一级 1
        if(($operate_no & 128) == 128 ){
            $listData['problemFirstList'] = CompanyProblemTypeBusiness::getWorkTypeKeyVals($company_id, 0, true) ?? [];

        }

//        $listData = [
//            'workFirstList' => $workFirstList,// 工单分类第一级
//            'workCallTypeList' => $page,// 工单来电类型
//            'serviceTagList' => $total,//业务标签
//            'serviceTimeList' => $aaa,// 业务时间
//            'customerTypeList' => $requestData,// 客户类型
//            'areaCityList' => $requestData,// 客户地区
//            'departmentFirstList' => $requestData,// 部门信息
//            'problemFirstList' => $requestData,// 反馈分类第一级
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
        CompanyBusiness::autoCompanyStatusMsg();// 更新过期 ，快到期
        pr(11);

//         CompanyWorkDoingBusiness::autoSiteMsg();
         // $worksObj = CompanyWorkDoingBusiness::getWorkInfo(1, 3);
         //var_dump(empty($worksObj));
        /*
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
        */

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
//        $currentNow = Carbon::now();
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');

        $sendLogs = ["派发工单"];//指派日志

        // 工单号
        $operate_txt = "修改";
        $is_add_new = false;// 是否新加 true:新加 ; false：修改
        if($work_id <= 0){
            $operate_txt = "创建";
            $is_add_new = true;
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

        $expiry_time =  Carbon::now()->addMinute($second_num);
        $save_data['time_name'] = $serviceTimeObj->time_name;
        $save_data['second_num'] = $second_num;
        $save_data['expiry_time']  = $expiry_time;
        $save_data['book_time']  = $expiry_time;
            //客户类型[分类一级]
        $type_id = $save_data['type_id'] ?? 0;
        if($type_id > 0 ){
            Common::judgeInitParams($request, 'type_id', $type_id);
            $customerTypeObj = CompanyCustomerType::select(['id', 'type_name'])->find($type_id);
            if(empty($customerTypeObj)){
                throws("没有客户类型信息");
            }
            $save_data['customer_type_name'] = $customerTypeObj->type_name ?? '';
        }


        //县/区id[分类一级]
        $city_id = $save_data['city_id'] ?? 0;
        if($city_id > 0){
            Common::judgeInitParams($request, 'city_id', $city_id);
            $cityObj = CompanyArea::select(['id', 'area_name'])->find($city_id);
            if(empty($cityObj)){
                throws("没有区/县信息");
            }
            $save_data['city_name'] = $cityObj->area_name ?? '';
        }

        //街道id[分类二级]
        $area_id = $save_data['area_id'] ?? 0;
        if($area_id > 0 ){
            Common::judgeInitParams($request, 'area_id', $area_id);
            $areaObj = CompanyArea::select(['id', 'area_name'])->find($area_id);
            if(empty($areaObj)){
                throws("没有街道信息");
            }
            $save_data['area_name'] = $areaObj->area_name ?? '';
        }


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
//
//        // 部门名称
//        $send_department_name = '';
//        $send_department_id = $save_data['send_department_id'] ?? 0;
//        if($send_department_id > 0){
//            // Common::judgeInitParams($request, 'send_department_id', $send_department_id);
//            $departmentObj = CompanyDepartment::select(['id', 'department_name'])->find($send_department_id);
//            if(empty($departmentObj)){
//                throws("没有部门信息");
//            }
//            $send_department_name = $departmentObj->department_name ?? '';
//        }
//        $save_data['send_department_name'] = $send_department_name;
//        array_push($sendLogs, $send_department_name);// 指派日志
//
//        // 小组名称
//        $send_group_name = '';
//        $send_group_id = $save_data['send_group_id'] ?? 0;
//        if($send_group_id > 0){
//            // Common::judgeInitParams($request, 'send_group_id', $send_group_id);
//            $groupObj = CompanyDepartment::select(['id', 'department_name'])->find($send_group_id);
//            if(empty($groupObj)){
//                throws("没有小组信息");
//            }
//            $send_group_name = $groupObj->department_name ?? '';
//        }
//        $save_data['send_group_name'] = $send_group_name;
//        array_push($sendLogs, $send_group_name);// 指派日志

        DB::beginTransaction();
        try {
            // 获得员工历史记录id-- 工单接收员工
            $send_staff_id = $save_data['send_staff_id'] ?? 0;
            if($work_id <= 0) {
                $save_data['status'] = 0; // 默认状态

            }else{
                $save_data['status'] = 0; // 默认状态
                $save_data['is_focus'] = 0;//是否重点关注
                $save_data['is_overdue'] = 0;//是否逾期
            }
            if($send_staff_id > 0){ // 指定了员工
                $sendStaffObj = null;
    //            Common::getObjByModelName("CompanyStaff", $sendStaffObj);
                $sendStaffHistoryObj = null;
    //            Common::getObjByModelName("CompanyStaffHistory", $sendStaffHistoryObj);
    //            $sendStaffHistorySearch = [
    //                'company_id' => $company_id,
    //                'staff_id' => $send_staff_id,
    //            ];
    //
    //            Common::getHistory($sendStaffObj, $send_staff_id, $sendStaffHistoryObj,'company_staff_history', $sendStaffHistorySearch, []);
                // $this->getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id);
                CompanyStaffBusiness::getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id );
                $send_staff_history_id = $sendStaffHistoryObj->id;
                Common::judgeEmptyParams($request, '指派员工历史记录ID', $send_staff_history_id);
                $save_data['send_staff_history_id'] = $send_staff_history_id;
                $save_data['status'] = 1;//1待确认工单

                // 部门名称
                $send_department_name = '';
                $send_department_id = $sendStaffObj->department_id;// $save_data['send_department_id'] ?? 0;
                if($send_department_id > 0){
                    // Common::judgeInitParams($request, 'send_department_id', $send_department_id);
                    $departmentObj = CompanyDepartment::select(['id', 'department_name'])->find($send_department_id);
                    if(empty($departmentObj)){
                        throws("没有部门信息");
                    }
                    $send_department_name = $departmentObj->department_name ?? '';
                }
                $save_data['send_department_id'] = (int)$send_department_id;
                $save_data['send_department_name'] = $send_department_name;
                array_push($sendLogs, $send_department_name);// 指派日志

                // 小组名称
                $send_group_name = '';
                $send_group_id = $sendStaffObj->group_id;// $save_data['send_group_id'] ?? 0;
                if($send_group_id > 0){
                    // Common::judgeInitParams($request, 'send_group_id', $send_group_id);
                    $groupObj = CompanyDepartment::select(['id', 'department_name'])->find($send_group_id);
                    if(empty($groupObj)){
                        throws("没有小组信息");
                    }
                    $send_group_name = $groupObj->department_name ?? '';
                }
                $save_data['send_group_id'] = (int)$send_department_id;
                $save_data['send_group_name'] = $send_group_name;
                array_push($sendLogs, $send_group_name);// 指派日志

                // 指派到员工
                array_push($sendLogs, $sendStaffHistoryObj->real_name . '[' . $sendStaffHistoryObj->work_num . '；' . $sendStaffHistoryObj->mobile . ']');// 指派日志


            }

            // 获是员工历史记录id-- 操作员工
            $staffObj = null;
    //        Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
    //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
    //        $StaffHistorySearch = [
    //            'company_id' => $company_id,
    //            'staff_id' => $staff_id,
    //        ];
    //
    //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

            // $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
            CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );

            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);
            $operate_department_id = $staffObj->department_id;
            $operate_group_id = $staffObj->group_id;
            $save_data['operate_staff_id'] = $staff_id;
            $save_data['operate_staff_history_id'] = $operate_staff_history_id;

            $department_id = $staffObj->department_id;
            // 获得部门名称
            $department_name = '';
            if($department_id > 0){
                $departmentObj = CompanyDepartment::select(['id', 'department_name'])->find($department_id);
                if(empty($departmentObj)){
                    throws("没有部门信息");
                }
                $department_name = $departmentObj->department_name ?? '';
            }
            $save_data['department_id'] = $department_id;
            $save_data['department_name'] = $department_name;

            $group_id = $staffObj->group_id;
            // 小组名称
            $group_name = '';
            if($group_id > 0){
                $groupObj = CompanyDepartment::select(['id', 'department_name'])->find($group_id);
                if(empty($groupObj)){
                    throws("没有小组信息");
                }
                $group_name = $groupObj->department_name ?? '';
            }
            $save_data['group_id'] = $group_id;
            $save_data['group_name'] = $group_name;

            // 对客户信息进行处理 [更新或新建]
            $call_number = $save_data['call_number'] ?? '';
            // 获得客户信息:没有新建，有则更新来电次数
            $customer = [
                'company_id' => $company_id,// $save_data['company_id'] ?? '',
                'call_number' => $save_data['call_number'] ?? '',// 来电号码
                'contact_number' => $save_data['contact_number'] ?? '',// 联系电话
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
            if($work_id <=0 ){
                $customerObj->call_num++;
                $customerObj->save();
            }

            // 判断版本号是否要+1

            $compareHistoryObj = null;
            Common::getObjByModelName("CompanyCustomerHistory", $compareHistoryObj);
            $customerHistorySearch = [
                'company_id' => $company_id,
                'customer_id' => $customer_id,
            ];

            $customerIgnoreFields = ['customer_id', 'call_num', 'last_call_date'];
            $customerIsUpdate = false;// 客户表是否更新,false:没有更新,true：有更新
            $diffDataArr = Common::compareHistoryOrUpdateVersion($customerObj, $customer_id,
                $compareHistoryObj,'company_customer_history',
                $customerHistorySearch, $customerIgnoreFields, 0);
            if(! empty($diffDataArr)){// 客户有新信息，版本号+1
                // 对比主表和历史表是否相同，相同：不更新版本号，不同：版本号+1
                $customerObj->version_num++ ;
                $customerObj->save();
                $customerIsUpdate = true;
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


            // 保存添加员工的客户信息--创建或修改

            $staffCustomerData = $customerObj->toArray();
    //        $staffCustomerNeedFields = ['company_id', 'version_num', 'call_number', 'type_id', 'customer_name', 'sex'
    //            , 'city_id', 'area_id', 'address', 'call_num', 'last_call_date', 'operate_staff_id', 'operate_staff_history_id'];
    //        Tool::formatTwoArrKeys($staffCustomerData, Tool::arrEqualKeyVal($staffCustomerNeedFields), $needNotIn = false);

            if($staffCustomerData['id']) unset($staffCustomerData['id']);
            if($staffCustomerData['sex_text']) unset($staffCustomerData['sex_text']);
            if($staffCustomerData['is_tab_text']) unset($staffCustomerData['is_tab_text']);
            if($staffCustomerData['created_at']) unset($staffCustomerData['created_at']);
            if($staffCustomerData['updated_at']) unset($staffCustomerData['updated_at']);
            $batchModifyStaffCustomer = $staffCustomerData;
            $staffCustomerData['customer_id'] = $customer_id;
            $staffCustomerData['customer_history_id'] = $customer_history_id;
            $staffCustomerData['staff_id'] = $staff_id;
            $staffCustomerData['staff_history_id'] = $operate_staff_history_id;

            // 创建或更新员工客户信息
            $StaffCustomerObj = null;
            Common::getObjByModelName("CompanyStaffCustomer", $StaffCustomerObj);

            $searchStaffCustomerConditon = [
                'company_id' => $company_id,
                'staff_id' => $staff_id,
                'customer_id' => $customer_id,
            ];

            Common::updateOrCreate($StaffCustomerObj, $searchStaffCustomerConditon, $staffCustomerData );

            //更新使用了此用户表的，员工表-- 自动更新
            if($customerIsUpdate){
                $where = [
                    ['company_id', '=', $company_id],
                    ['customer_id', '=', $customer_id],
                ];
                CompanyStaffCustomer::where($where)->update($batchModifyStaffCustomer);
            }

            // 保存指派员工的客户信息
            $staffCustomerData['staff_id'] = $send_staff_id;
            $staffCustomerData['staff_history_id'] = $send_staff_history_id;
            // 创建或更新员工客户信息
            $sendStaffCustomerObj = null;
            Common::getObjByModelName("CompanyStaffCustomer", $sendStaffCustomerObj);

            $searchSendStaffCustomerConditon = [
                'company_id' => $company_id,
                'staff_id' => $send_staff_id,
                'customer_id' => $customer_id,
            ];

            Common::updateOrCreate($sendStaffCustomerObj, $searchSendStaffCustomerConditon, $staffCustomerData );


            // 保存或修改工单
            $workObj = null;
            Common::getObjByModelName("CompanyWork", $workObj);
            $workSearchConditon = [
                'company_id' => $company_id,
                'id' => $work_id,
            ];
            Common::updateOrCreate($workObj, $workSearchConditon, $save_data );
            $work_id = $workObj->id;
            // 保存或修改工单进行表
            $workDoingObj = null;
            Common::getObjByModelName("CompanyWorkDoing", $workDoingObj);
            $workSearchConditon = [
                'company_id' => $company_id,
                'work_num' => $workObj->work_num,
                'work_id' => $work_id,
            ];
            $workDoingSaveData = $workObj->toArray();
            $workDoingSaveData['work_id'] = $work_id;
            if(isset($workDoingSaveData['status_text'])) unset($workDoingSaveData['status_text']);
            if(isset($workDoingSaveData['sex_text'])) unset($workDoingSaveData['sex_text']);
            Common::updateOrCreate($workDoingObj, $workSearchConditon, $workDoingSaveData );

            // 工单派发记录
            if($send_staff_id > 0) { // 指定了员工

                // $this->saveSends($workObj, $workObj->operate_staff_id, $workObj->operate_staff_history_id);
                CompanyWorkSendsBusiness::saveSends($workObj, $workObj->operate_staff_id , $workObj->operate_staff_history_id);                // 发送消息
                CompanySiteMsgBusiness::sendSiteMsg($workObj, null, null,
                    '指派新工单提醒', '工单[' . $workObj->work_num . ']已指派给您,请注意安排处理！');
            }
    //        $workSends = [
    //            'company_id' => $workObj->company_id,
    //            'work_id' => $workObj->id,
    //            'work_status' => $workObj->status,
    //            'send_department_id' => $workObj->send_department_id,
    //            'send_department_name' => $workObj->send_department_name,
    //            'send_group_id' => $workObj->send_group_id,
    //            'send_group_name' => $workObj->send_group_name,
    //            'send_staff_id' => $workObj->send_staff_id,
    //            'send_staff_history_id' => $workObj->send_staff_history_id,
    //            'status' => 0, // 状态0可处理;1不可处理
    //            'operate_staff_id' => $workObj->operate_staff_id,
    //            'operate_staff_history_id' => $workObj->operate_staff_history_id,
    //        ];
    //        $workSendsObj = null;
    //        Common::getObjByModelName("CompanyWorkSends", $workSendsObj);
    //        Common::create($workSendsObj, $workSends);

            // 工单操作日志
            // $this->saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id, "创建工单");

            CompanyWorkLogBusiness::saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id,  $operate_txt . "工单");
            if($send_staff_id > 0) { // 指定了员工
                //$this->saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id, implode(",", $sendLogs));
                CompanyWorkLogBusiness::saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id,  implode(",", $sendLogs));
            }
    //        $workLog = [
    //            'company_id' => $workObj->company_id,
    //            'work_id' => $workObj->id,
    //            'work_status_new' => $workObj->status,
    //            'content' => "创建工单", // 操作内容
    //            'operate_staff_id' => $workObj->operate_staff_id,
    //            'operate_staff_history_id' => $workObj->operate_staff_history_id,
    //        ];
    //
    //        $workLogObj = null;
    //        Common::getObjByModelName("CompanyWorkLog", $workLogObj);
    //        Common::create($workLogObj, $workLog);

            // 工单来电统计
            if($is_add_new) {
                // $this->workCallCount($workObj, $workObj->operate_staff_id , $workObj->operate_staff_history_id);
                CompanyWorkCallCountBusiness::workCallCount($workObj, $operate_department_id , $operate_group_id, $workObj->operate_staff_id , $workObj->operate_staff_history_id);
            }
    //        $workCallCountObj = null;
    //        Common::getObjByModelName("CompanyWorkCallCount", $workCallCountObj);
    //
    //        $searchConditon = [
    //            'company_id' => $workObj->company_id,
    //            'operate_staff_id' => $workObj->operate_staff_id,
    //            'count_year' => $currentNow->year,
    //            'count_month' => $currentNow->month,
    //            'count_day' => $currentNow->day,
    //        ];
    //        $updateFields = [
    //            'amount' => 0,
    //            'operate_staff_history_id' => $workObj->operate_staff_history_id,
    //        ];
    //
    //        Common::firstOrCreate($workCallCountObj, $searchConditon, $updateFields );
    //        $workCallCountObj->amount++;
    //        $workCallCountObj->save();
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray($workObj);
    }

    /**
     * 删除
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function del(Request $request)
    {
        $this->InitParams($request);
//        $currentNow = Carbon::now();
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $workObj = CompanyWork::find($work_id);
        // $workObj = CompanyWorkDoingBusiness::getWorkInfo($company_id, $work_id); // $workDoingObj
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if($workObj->status == 8 || $workObj->company_id != $company_id ){// || $workObj->send_staff_id != $staff_id
            throws("此工单不可进行此操作!");
        }

        $result = 0;
        DB::beginTransaction();
        try {
            // 删除正在进行的工单
            $result = CompanyWorkBusiness::delById(1, $company_id, $work_id);
            // 删除全表中的工单
            // $result = CompanyWorkBusiness::delById(0, $company_id, $work_id);
            $result = $workObj->delete();
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('操作失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray($result);
    }

    /**
     * 手机站首页初始化数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function mobile_index(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // operate_no 操作编号
        $operate_no = Common::getInt($request, 'operate_no');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工

        $listData = [];
        //待确认工单数量 1
        if(($operate_no & 1) == 1 ){
            $waitSureCount = CompanyWorkBusiness::getCount($company_id, $staff_id, 1);
        }
        $listData['waitSureCount'] = $waitSureCount ?? 0;

        //处理中工单数量 2
        if(($operate_no & 2) == 2 ){
            $doingCount = CompanyWorkBusiness::getCount($company_id, $staff_id, 2);
        }
        $listData['doingCount'] = $doingCount ?? 0;

        // 未读消息
        if(($operate_no & 4) == 4 ) {
            $msgList = CompanySiteMsgBusiness::getSiteMsgByIsRead($company_id, $staff_id, 0);
        }
        $listData['msgList'] = $msgList ?? [];
//        $listData = [
//            'waitSureCount' => $waitSureCount,// 待确认工单数量
//            'doingCount' => $doingCount,// 处理中工单数量
//            'msgList' => $total,//未读消息
//        ];
       return okArray($listData);

    }


    /**
     * 确认工单
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function workSure(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
//        $save_data = Common::get($request, 'save_data');
//        Common::judgeEmptyParams($request, 'save_data', $save_data);
//        // json 转成数组
//        jsonStrToArr($save_data, 1, '参数[save_data]格式有误!');
        // 获取工单信息
        $workObj = CompanyWork::find($work_id);
        // $workObj = CompanyWorkDoingBusiness::getWorkInfo($company_id, $work_id); // $workDoingObj
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if($workObj->status != 1 || $workObj->company_id != $company_id || $workObj->send_staff_id != $staff_id){
            throws("此工单不可进行此操作!");
        }
        DB::beginTransaction();
        try {

            // 获是员工历史记录id-- 操作员工
            $staffObj = null;
    //        Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
    //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
    //        $StaffHistorySearch = [
    //            'company_id' => $company_id,
    //            'staff_id' => $staff_id,
    //        ];
    //
    //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

            //$this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
            CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );

            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

    //        $save_data['operate_staff_id'] = $staff_id;
    //        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

            // 修改状态
            $saveData = ['status' => 2,'confirm_time' => Carbon::now()->toDateTimeString()];
            foreach($saveData as $field => $val){
                $workObj->{$field} = $val;
            }
            $workObj->save();
            // CompanyWorkBusiness::saveById($work_id, $saveData);
            CompanyWorkBusiness::saveById(1, $company_id, $work_id, $saveData);
            // 日志
            // $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "确认工单!");
            CompanyWorkLogBusiness::saveWorkLog($workObj , $staff_id , $operate_staff_history_id,  "确认工单!");
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray([]);
    }

    /**
     * 工单重新指定
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function workReSend(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data, 1, '参数[save_data]格式有误!');
        $sendLogs = [];//指派日志
        // 获取工单信息
        $workObj = CompanyWork::find($work_id);
        // $workObj = CompanyWorkDoingBusiness::getWorkInfo($company_id, $work_id); // $workDoingObj
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if( !in_array($workObj->status, [0,1]) || $workObj->company_id != $company_id){//  || $workObj->operate_staff_id != $staff_id
            throws("此工单不可进行此操作!");
        }
        $oldSendStaffId = $workObj->send_staff_id;// 源来指定的员工信息

        // 部门名称
        $send_department_name = '';
        $send_department_id = $save_data['send_department_id'] ?? 0;
        if($send_department_id > 0){
            // Common::judgeInitParams($request, 'send_department_id', $send_department_id);
            $departmentObj = CompanyDepartment::select(['id', 'department_name'])->find($send_department_id);
            if(empty($departmentObj)){
                throws("没有部门信息");
            }
            $send_department_name = $departmentObj->department_name ?? '';
        }
        $save_data['send_department_name'] = $send_department_name;
        array_push($sendLogs, $send_department_name);// 指派日志

        // 小组名称
        $send_group_name = '';
        $send_group_id = $save_data['send_group_id'] ?? 0;
        if($send_group_id > 0){
            // Common::judgeInitParams($request, 'send_group_id', $send_group_id);
            $groupObj = CompanyDepartment::select(['id', 'department_name'])->find($send_group_id);
            if(empty($groupObj)){
                throws("没有小组信息");
            }
            $send_group_name = $groupObj->department_name ?? '';
        }
        $save_data['send_group_name'] = $send_group_name;
        array_push($sendLogs, $send_group_name);// 指派日志


        DB::beginTransaction();
        try {
            // 获得员工历史记录id-- 工单接收员工
            $send_staff_id = $save_data['send_staff_id'] ?? 0;
            $save_data['status'] = 0; // 默认状态
            if($send_staff_id > 0){ // 指定了员工
                $sendStaffObj = null;
    //            Common::getObjByModelName("CompanyStaff", $sendStaffObj);
                $sendStaffHistoryObj = null;
    //            Common::getObjByModelName("CompanyStaffHistory", $sendStaffHistoryObj);
    //            $sendStaffHistorySearch = [
    //                'company_id' => $company_id,
    //                'staff_id' => $send_staff_id,
    //            ];
    //
    //            Common::getHistory($sendStaffObj, $send_staff_id, $sendStaffHistoryObj,'company_staff_history', $sendStaffHistorySearch, []);
               // $this->getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id);
                CompanyStaffBusiness::getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id );

                $send_staff_history_id = $sendStaffHistoryObj->id;
                Common::judgeEmptyParams($request, '指派员工历史记录ID', $send_staff_history_id);
                $save_data['send_staff_history_id'] = $send_staff_history_id;
                $save_data['status'] = 1;//1待确认工单

                array_push($sendLogs, $sendStaffHistoryObj->real_name . '[' . $sendStaffHistoryObj->work_num . '；' . $sendStaffHistoryObj->mobile . ']');// 指派日志
            }else{
                throws("没有指派的员工!");
            }

            // 获是员工历史记录id-- 操作员工
            $staffObj = null;
    //        Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
    //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
    //        $StaffHistorySearch = [
    //            'company_id' => $company_id,
    //            'staff_id' => $staff_id,
    //        ];
    //
    //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

            // $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
            CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );

            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

    //        $save_data['operate_staff_id'] = $staff_id;
    //        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

            // 修改主表
            $save_data['reply_time'] = Carbon::now()->toDateTimeString();
            foreach($save_data as $field => $val){
                $workObj->{$field} = $val;
            }
            $workObj->save();
            CompanyWorkBusiness::saveById(1,$company_id, $work_id, $save_data);
            if($send_staff_id > 0 && $oldSendStaffId != $send_staff_id){ // 指定的员工不同，需要把客户也复制一份给新指定的员工。
                // 从用户表
                $customer_id = $workObj->customer_id;
                $customer_history_id = $workObj->customer_history_id;
                // $customerObj = CompanyCustomer::find($workObj->customer_id);
                $customerObj = CompanyCustomerHistory::find($customer_history_id);
                // 保存指定员工的客户信息--创建或修改

                $staffCustomerData = $customerObj->toArray();
                //        $staffCustomerNeedFields = ['company_id', 'version_num', 'call_number', 'type_id', 'customer_name', 'sex'
                //            , 'city_id', 'area_id', 'address', 'call_num', 'last_call_date', 'operate_staff_id', 'operate_staff_history_id'];
                //        Tool::formatTwoArrKeys($staffCustomerData, Tool::arrEqualKeyVal($staffCustomerNeedFields), $needNotIn = false);

                if($staffCustomerData['id']) unset($staffCustomerData['id']);
                if($staffCustomerData['sex_text']) unset($staffCustomerData['sex_text']);
                if($staffCustomerData['is_tab_text']) unset($staffCustomerData['is_tab_text']);
                if($staffCustomerData['created_at']) unset($staffCustomerData['created_at']);
                if($staffCustomerData['updated_at']) unset($staffCustomerData['updated_at']);
                $batchModifyStaffCustomer = $staffCustomerData;
                $staffCustomerData['customer_id'] = $customer_id;
                $staffCustomerData['customer_history_id'] = $customer_history_id;
                $staffCustomerData['staff_id'] = $send_staff_id;
                $staffCustomerData['staff_history_id'] = $send_staff_history_id;

                // 创建或更新员工客户信息
                $sendStaffCustomerObj = null;
                Common::getObjByModelName("CompanyStaffCustomer", $sendStaffCustomerObj);

                $searchSendStaffCustomerConditon = [
                    'company_id' => $company_id,
                    'staff_id' => $send_staff_id,
                    'customer_id' => $customer_id,
                ];

                Common::updateOrCreate($sendStaffCustomerObj, $searchSendStaffCustomerConditon, $staffCustomerData );

                //        $customerList = CompanyCustomer::select(['id', 'call_num'])
//            ->where([
//                ['company_id', '=', $company_id],
//                ['call_number', '=', $call_number],
//            ])->limit(1)
//            ->get()->toArray();

            }
            // 日志
            // $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "重新派发员工!");
            CompanyWorkLogBusiness::saveWorkLog($workObj , $staff_id , $operate_staff_history_id,  "重新派发员工!");
            if($send_staff_id > 0) { // 指定了员工
                // 工单派发记录
                // $this->saveSends($workObj,  $staff_id , $operate_staff_history_id);
                CompanyWorkSendsBusiness::saveSends($workObj, $staff_id , $operate_staff_history_id);
                // $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, implode(",", $sendLogs));
                CompanyWorkLogBusiness::saveWorkLog($workObj , $staff_id , $operate_staff_history_id,  implode(",", $sendLogs));
            }

        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray([]);
    }

    /**
     * 工单结单
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function workWin(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data, 1, '参数[save_data]格式有误!');

        $win_content = $save_data['win_content'] ?? '';// 内容说明
        // 获取工单信息
        $workObj = CompanyWork::find($work_id);
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if($workObj->status != 2 || $workObj->company_id != $company_id ){// || $workObj->send_staff_id != $staff_id
            throws("不可进行此操作!");
        }


        DB::beginTransaction();
        try {
            // 获是员工历史记录id-- 操作员工
            $staffObj = null;
    //        Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
    //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
    //        $StaffHistorySearch = [
    //            'company_id' => $company_id,
    //            'staff_id' => $staff_id,
    //        ];
    //
    //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

            //$this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
            CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );

            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);
            $department_id = $staffObj->department_id;
            $group_id = $staffObj->group_id;
    //        $save_data['operate_staff_id'] = $staff_id;
    //        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

            // 修改状态
            $save_data['status'] = 4;
            $save_data['win_time'] = Carbon::now()->toDateTimeString();
            foreach($save_data as $field => $val){
                $workObj->{$field} = $val;
            }
            // $workObj->status = 4;
            $workObj->save();
            CompanyWorkBusiness::saveById(1, $company_id, $work_id, $save_data);
            // 日志
            // $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "确认工单结单!内容："  . $win_content);
            CompanyWorkLogBusiness::saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "确认工单结单!内容："  . $win_content);
            // 统计处理数量
            //$this->workRepairCount($workObj, $staff_id , $operate_staff_history_id);
            CompanyWorkRepairCountBusiness::workRepairCount($workObj, $department_id , $group_id, $staff_id , $operate_staff_history_id);
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray([]);
    }

    /**
     * 工单回访
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function workReply(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data, 1, '参数[save_data]格式有误!');


        $reply_content = $save_data['reply_content'] ?? '';// 内容说明


        DB::beginTransaction();
        try {

            // 获取工单信息
            $workObj = CompanyWork::find($work_id);
            if(empty($workObj)){
                throws("工单记录不存在!");
            }
            if($workObj->status != 4 || $workObj->company_id != $company_id ){// || $workObj->send_staff_id != $staff_id
                throws("此工单不可进行此操作!");
            }

            // 获是员工历史记录id-- 操作员工
            $staffObj = null;
    //        Common::getObjByModelName("CompanyStaff", $staffObj);
            $staffHistoryObj = null;
    //        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
    //        $StaffHistorySearch = [
    //            'company_id' => $company_id,
    //            'staff_id' => $staff_id,
    //        ];
    //
    //        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);

            // $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);
            CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );

            $operate_staff_history_id = $staffHistoryObj->id;
            Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

    //        $save_data['operate_staff_id'] = $staff_id;
    //        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

            // 修改状态
            $save_data['status'] = 8;
            $save_data['is_reply'] = 1;
            $save_data['reply_time'] = Carbon::now()->toDateTimeString();
            foreach($save_data as $field => $val){
                $workObj->{$field} = $val;
            }
            // $workObj->status = 8;
            $workObj->save();
            // 删除操作表
            CompanyWorkBusiness::delById(1, $company_id, $work_id);
            // 日志
            // $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "工单回访!内容：" . $reply_content);
            CompanyWorkLogBusiness::saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "工单回访!内容：" . $reply_content);
        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        return  okArray([]);
    }

    /**
     * 工单状态统计
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function statusCount(Request $request)
    {
        $this->InitParams($request);

        // 工单状态自动监控
        $temPre = 'work:';// 前缀
        $temKey = 'DoUnixTime';// 名称
        // 获得上次请求的时间
        $lastDoUnixTime = Tool::getRedis($temPre . $temKey, 3);
        $isDo = false;
        if(!is_numeric($lastDoUnixTime)){
            $lastDoUnixTime = time();
            $isDo = true;
        }else{
            $recordUnixTime = time();
            if( ($recordUnixTime - $lastDoUnixTime) >= 60 * 3 ){
                $isDo = true;
                $lastDoUnixTime = $recordUnixTime;
            }
        }
        if($isDo){
            CompanyWorkDoingBusiness::autoSiteMsg();// 工单状态自动监控
            // 缓存上次请求的时间
            Tool::setRedis($temPre, $temKey, $lastDoUnixTime, 0 , 3);
        }

        $company_id = $this->company_id;
        $staff_id = Common::getInt($request, 'staff_id');// 接收员工id
        $operate_staff_id = Common::getInt($request, 'operate_staff_id');// 添加员工id

        $send_department_id = Common::getInt($request, 'send_department_id');// 派到部门id
        $send_group_id = Common::getInt($request, 'send_group_id');// 派到小组id

        $department_id = Common::getInt($request, 'department_id');// 部门id
        $group_id = Common::getInt($request, 'group_id');// 小组id

        // 统计工单状态
        $status = [0,1,2];
        $result = CompanyWorkBusiness::getGroupCount($company_id, $status, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, $operate_staff_id);
        // 统计  4待回访工单
        $otherWhere = [];
        $result[4] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, 4, $operate_staff_id, $otherWhere);
        // -8重点关注
        $otherWhere = [['is_focus', '=', 1]];
        $result[-8] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, [0,1,2], $operate_staff_id, $otherWhere);
        // -4过期未处理
        $otherWhere = [['is_overdue', '=', 1]];
        $result[-4] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, [0,1,2], $operate_staff_id, $otherWhere);

        return  okArray($result);
    }

    /**
     * 工单统计
     *
     * @param int $id
     * @return Response
        status 1统计工单状态
        callCount 2工单来电统计-总量统计
        callCountDay 4 工单来电统计-按日期统计
        callCountMonth 8 工单来电统计-按月统计
        callCountYear 16 工单来电统计-按年统计
        callCountSelf 32 工单来电统计-按其它统计
        repairCountDay 128 工单维修统计-按日期统计
        repairCountMonth 256 工单维修统计-按月统计
        repairCountYear 512 工单维修统计-按年统计
        repairCountSelf 1024 工单维修统计-按其它统计
        2048 工单数量统计
        sum_operate_no
            callSumDateRange 1 工单数量统计-时间段
            callSumToday 2 工单数量统计-今日
            callSumCurrentWeek 4 工单数量统计-本周
            callSumPreWeek 8 工单数量统计-上周
            callSumCurrentMonth 16 工单数量统计-本月
            callSumPreMonth 32 工单数量统计-上月
            callSumCurrentYear 64 工单数量统计-本年
            callSumPreYear 128 工单数量统计-上年
        4096 工单维修数量统计
        sum_repair_operate_no
            repairSumDateRange 1 工单维修数量统计-时间段
            repairSumToday 2 工单维修数量统计-今日
            repairSumCurrentWeek 4 工单数量统计-本周
            repairSumPreWeek 8 工单维修数量统计-上周
            repairSumCurrentMonth 16 工单维修数量统计-本月
            repairSumPreMonth 32 工单维修数量统计-上月
            repairSumCurrentYear 64 工单维修数量统计-本年
            repairSumPreYear 128 工单维修数量统计-上年
     * @author zouyan(305463219@qq.com)
     */
    public function workCount(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // operate_no 操作编号
        $operate_no = Common::getInt($request, 'operate_no');

        $send_department_id = Common::getInt($request, 'send_department_id');// 派到部门id
        $send_group_id = Common::getInt($request, 'send_group_id');// 派到小组id

        $department_id = Common::getInt($request, 'department_id');// 部门id
        $group_id = Common::getInt($request, 'group_id');// 小组id

        $staff_id = Common::getInt($request, 'staff_id');// 接收员工id
        $operate_staff_id = Common::getInt($request, 'operate_staff_id');// 添加员工id

        $begin_date = Common::get($request, 'begin_date');// 开始日期
        $end_date = Common::get($request, 'end_date');// 结束日期

        $nowTime = time();
        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_date, $end_date, 4 + 32 + 256);
        // errorArray('结束日期不能小于开始日期');

        $listData = [];
        //处理状态中的状态统计
        if(($operate_no & 1) == 1 ) {
            // 统计工单状态
            $status = [0, 1, 2];
            $result = CompanyWorkBusiness::getGroupCount($company_id, $status, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, $operate_staff_id);
            // 4 待回访工单
            $otherWhere = [];
            $result[4] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, 4, $operate_staff_id, $otherWhere);
            // -8重点关注   ]
            $otherWhere = [['is_focus', '=', 1]];
            $result[-8] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, [0, 1, 2], $operate_staff_id, $otherWhere);
            // -4过期未处理
            $otherWhere = [['is_overdue', '=', 1]];
            $result[-4] = CompanyWorkBusiness::getCount($company_id, $send_department_id, $send_group_id, $department_id, $group_id, $staff_id, [0, 1, 2], $operate_staff_id, $otherWhere);
            $listData['status'] = $result;
        }
        //工单来电统计-总量统计
        if(($operate_no & 2) == 2 ) {
            $listData['callCount'] = CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $begin_date, $end_date,  $operate_staff_id, $department_id, $group_id);
        }
        //工单来电统计-按日期统计
        if(($operate_no & 4) == 4 ) {
            $listData['callCountDay'] = CompanyWorkCallCountBusiness::getCountAmount($company_id, 1, $begin_date, $end_date, $operate_staff_id, $department_id, $group_id);
        }
        //工单来电统计-按月统计
        if(($operate_no & 8) == 8 ) {
            $listData['callCountMonth'] = CompanyWorkCallCountBusiness::getCountAmount($company_id, 2, $begin_date, $end_date, $operate_staff_id, $department_id, $group_id);
        }
        //工单来电统计-按年统计
        if(($operate_no & 16) == 16 ) {
            $listData['callCountYear'] = CompanyWorkCallCountBusiness::getCountAmount($company_id, 3, $begin_date, $end_date, $operate_staff_id, $department_id, $group_id);
        }
        //工单来电统计-按其它统计
        if(($operate_no & 32) == 32 ) {
            $listData['callCountSelf'] = CompanyWorkCallCountBusiness::getCountAmount($company_id, 4, $begin_date, $end_date, $operate_staff_id, $department_id, $group_id);
        }


        //工单维修统计-总量统计
        if(($operate_no & 64) == 64 ) {
            $listData['repairCount'] = CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $begin_date, $end_date,  $staff_id, $send_department_id, $send_group_id);
        }

        //工单维修统计-按日期统计
        if(($operate_no & 128) == 128 ) {
            $listData['repairCountDay'] = CompanyWorkRepairCountBusiness::getCountAmount($company_id, 1, $begin_date, $end_date, $staff_id, $send_department_id, $send_group_id);
        }

        //工单维修统计-按月统计
        if(($operate_no & 256) == 256 ) {
            $listData['repairCountMonth'] = CompanyWorkRepairCountBusiness::getCountAmount($company_id, 2, $begin_date, $end_date, $staff_id, $send_department_id, $send_group_id);
        }

        //工单维修统计-按年统计
        if(($operate_no & 512) == 512 ) {
            $listData['repairCountYear'] = CompanyWorkRepairCountBusiness::getCountAmount($company_id, 3, $begin_date, $end_date, $staff_id, $send_department_id, $send_group_id);
        }

        //工单维修统计-按其它统计
        if(($operate_no & 1024) == 1024 ) {
            $listData['repairCountSelf'] = CompanyWorkRepairCountBusiness::getCountAmount($company_id, 4, $begin_date, $end_date, $staff_id, $send_department_id, $send_group_id);
        }
        // 2048 工单数量统计
        if(($operate_no & 2048) == 2048 ) {
            // sum_operate_no 数量统计操作编号
            $sum_operate_no = Common::getInt($request, 'sum_operate_no');
            // 1 工单数量统计-时间段
            if(($sum_operate_no & 1) == 1 ) {
                $listData['callSumDateRange'] = [
                    'begin_date' => $begin_date,
                    'end_date' => $end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $begin_date, $end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 2 工单数量统计-今日
            if(($sum_operate_no & 2) == 2 ) {
                $tem_begin_date = date("Y-m-d");
                $tem_end_date = date("Y-m-d");
                $listData['callSumToday'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 4 工单数量统计-本周
            if(($sum_operate_no & 4) == 4 ) {
                $tem_begin_date = Tool::getDateByType(1);// 1本周一
                $tem_end_date = Tool::getDateByType(2);// 2 本周日;
                $listData['callSumCurrentWeek'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 8 工单数量统计-上周
            if(($sum_operate_no & 8) == 8 ) {
                $tem_begin_date = Tool::getDateByType(3);//3 上周一;
                $tem_end_date = Tool::getDateByType(4);// 4 上周日;
                $listData['callSumPreWeek'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 16 工单数量统计-本月
            if(($sum_operate_no & 16) == 16 ) {
                $tem_begin_date = Tool::getDateByType(5);// 5 本月一日;
                $tem_end_date = Tool::getDateByType(6);// 6 本月最后一日;
                $listData['callSumCurrentMonth'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 32 工单数量统计-上月
            if(($sum_operate_no & 32) == 32 ) {
                $tem_begin_date = Tool::getDateByType(7);// 7 上月一日;
                $tem_end_date = Tool::getDateByType(8);// 8 上月最后一日
                $listData['callSumPreMonth'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 64 工单数量统计-本年
            if(($sum_operate_no & 64) == 64 ) {
                $tem_begin_date = Tool::getDateByType(9);// 9 本年一日
                $tem_end_date = Tool::getDateByType(10);// 10 本年最后一日
                $listData['callSumCurrentYear'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }
            // 128 工单数量统计-上年
            if(($sum_operate_no & 128) == 128 ) {
                $tem_begin_date = Tool::getDateByType(11);//11 上年一日
                $tem_end_date = Tool::getDateByType(12);// 12 上年最后一日
                $listData['callSumPreYear'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkCallCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $operate_staff_id, $department_id, $group_id),
                ];
            }

        }

        // 4096 工单维修数量统计
        if(($operate_no & 4096) == 4096 ) {
            // sum_operate_no 数量统计操作编号
            $sum_repair_operate_no = Common::getInt($request, 'sum_repair_operate_no');
            // 1 工单维修数量统计-时间段
            if(($sum_repair_operate_no & 1) == 1 ) {
                $listData['repairSumDateRange'] = [
                    'begin_date' => $begin_date,
                    'end_date' => $end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $begin_date, $end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 2 工单维修数量统计-今日
            if(($sum_repair_operate_no & 2) == 2 ) {
                $tem_begin_date = date("Y-m-d");
                $tem_end_date = date("Y-m-d");
                $listData['repairSumToday'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 4 工单数量统计-本周
            if(($sum_repair_operate_no & 4) == 4 ) {
                $tem_begin_date = Tool::getDateByType(1);// 1本周一
                $tem_end_date = Tool::getDateByType(2);// 2 本周日;
                $listData['repairSumCurrentWeek'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 8 工单维修数量统计-上周
            if(($sum_repair_operate_no & 8) == 8 ) {
                $tem_begin_date = Tool::getDateByType(3);//3 上周一;
                $tem_end_date = Tool::getDateByType(4);// 4 上周日;
                $listData['repairSumPreWeek'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 16 工单维修数量统计-本月
            if(($sum_repair_operate_no & 16) == 16 ) {
                $tem_begin_date = Tool::getDateByType(5);// 5 本月一日;
                $tem_end_date = Tool::getDateByType(6);// 6 本月最后一日;
                $listData['repairSumCurrentMonth'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 32 工单维修数量统计-上月
            if(($sum_repair_operate_no & 32) == 32 ) {
                $tem_begin_date = Tool::getDateByType(7);// 7 上月一日;
                $tem_end_date = Tool::getDateByType(8);// 8 上月最后一日
                $listData['repairSumPreMonth'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 64 工单维修数量统计-本年
            if(($sum_repair_operate_no & 64) == 64 ) {
                $tem_begin_date = Tool::getDateByType(9);// 9 本年一日
                $tem_end_date = Tool::getDateByType(10);// 10 本年最后一日
                $listData['repairSumCurrentYear'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }
            // 128 工单维修数量统计-上年
            if(($sum_repair_operate_no & 128) == 128 ) {
                $tem_begin_date = Tool::getDateByType(11);//11 上年一日
                $tem_end_date = Tool::getDateByType(12);// 12 上年最后一日
                $listData['repairSumPreYear'] = [
                    'begin_date' => $tem_begin_date,
                    'end_date' => $tem_end_date,
                    'amount' => CompanyWorkRepairCountBusiness::getCountAmount($company_id, 0, $tem_begin_date, $tem_end_date, $staff_id, $send_department_id, $send_group_id),
                ];
            }

        }
        return  okArray($listData);
    }

}
