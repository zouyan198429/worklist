<?php

namespace App\Http\Controllers;


use App\Models\CompanyArea;
use App\Models\CompanyCustomer;
use App\Models\CompanyCustomerHistory;
use App\Models\CompanyCustomerType;
use App\Models\CompanyDepartment;
use App\Models\CompanyServiceTags;
use App\Models\CompanyServiceTime;
use App\Models\CompanySiteMsg;
use App\Models\CompanyStaff;
use App\Models\CompanyStaffHistory;
use App\Models\CompanyWork;
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
        $currentNow = Carbon::now();
        $company_id = $this->company_id;
        $work_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');

        $sendLogs = [];//指派日志

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

        //客户类型[分类一级]
        $type_id = $save_data['type_id'] ?? 0;
        Common::judgeInitParams($request, 'type_id', $type_id);
        $customerTypeObj = CompanyCustomerType::select(['id', 'type_name'])->find($type_id);
        if(empty($customerTypeObj)){
            throws("没有客户类型信息");
        }
        $save_data['customer_type_name'] = $customerTypeObj->type_name ?? '';


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

        // 来电类型名称
        $caller_type_id = $save_data['caller_type_id'] ?? 0;
        Common::judgeInitParams($request, 'caller_type_id', $caller_type_id);
        $workCallerTypeObj = CompanyWorkCallerType::select(['id', 'type_name'])->find($caller_type_id);
        if(empty($workCallerTypeObj)){
            throws("没有业务时间信息");
        }
        $save_data['caller_type_name'] = $workCallerTypeObj->type_name ?? '';

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
            $this->getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id);

            $send_staff_history_id = $sendStaffHistoryObj->id;
            Common::judgeEmptyParams($request, '指派员工历史记录ID', $send_staff_history_id);
            $save_data['send_staff_history_id'] = $send_staff_history_id;
            $save_data['status'] = 1;//1待确认工单

            array_push($sendLogs, $sendStaffHistoryObj->real_name . '[' . $sendStaffHistoryObj->work_num . ']');// 指派日志
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

        $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);

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

        // 工单派发记录
        if($send_staff_id > 0) { // 指定了员工
            $this->saveSends($workObj, $workObj->operate_staff_id, $workObj->operate_staff_history_id);
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
        $this->saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id, "创建工单");
        if($send_staff_id > 0) { // 指定了员工
            $this->saveWorkLog($workObj , $workObj->operate_staff_id , $workObj->operate_staff_history_id, implode(",", $sendLogs));
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
        $this->workCallCount($workObj, $workObj->operate_staff_id , $workObj->operate_staff_history_id);
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
        return  okArray($workObj);
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
        $waitSureCount = 0;
        if(($operate_no & 1) == 1 ){
            $waitSureCount = CompanyWork::where([
                    ['company_id', '=', $company_id],
                    ['send_staff_id', '=', $staff_id],
                    ['status', '=', 1],
                ])->count();
        }
        $listData['waitSureCount'] = $waitSureCount;

        //处理中工单数量 2
        $doingCount = 0;
        if(($operate_no & 2) == 2 ){
            $doingCount = CompanyWork::where([
                ['company_id', '=', $company_id],
                ['send_staff_id', '=', $staff_id],
                ['status', '=',2],
            ])->count();
        }
        $listData['doingCount'] = $doingCount;

        // 未读消息
        $msgList = [];
        if(($operate_no & 4) == 4 ) {
            $msgList = CompanySiteMsg::select(['id', 'msg_name', 'mst_content', 'is_read', 'accept_staff_id'
                , 'operate_staff_id', 'created_at'])
               ->orderBy('id', 'desc')
                ->where([
                    ['company_id', '=', $company_id],
                    ['accept_staff_id', '=', $staff_id],
                    ['is_read', '=', 0],
                ])->get();
        }
        $listData['msgList'] = $msgList;
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
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if($workObj->status != 1 || $workObj->company_id != $company_id || $workObj->send_staff_id != $staff_id){
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

        $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);

        $operate_staff_history_id = $staffHistoryObj->id;
        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

//        $save_data['operate_staff_id'] = $staff_id;
//        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

        // 修改状态
        $workObj->status = 2;
        $workObj->save();
        // 日志
        $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "确认工单!");
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
        if(empty($workObj)){
            throws("工单记录不存在!");
        }
        if( !in_array($workObj->status, [0,1]) || $workObj->company_id != $company_id){//  || $workObj->operate_staff_id != $staff_id
            throws("此工单不可进行此操作!");
        }

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
            $this->getHistoryStaff($sendStaffObj , $sendStaffHistoryObj, $company_id, $send_staff_id);

            $send_staff_history_id = $sendStaffHistoryObj->id;
            Common::judgeEmptyParams($request, '指派员工历史记录ID', $send_staff_history_id);
            $save_data['send_staff_history_id'] = $send_staff_history_id;
            $save_data['status'] = 1;//1待确认工单

            array_push($sendLogs, $sendStaffHistoryObj->real_name . '[' . $sendStaffHistoryObj->work_num . ']');// 指派日志
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

        $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);

        $operate_staff_history_id = $staffHistoryObj->id;
        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

//        $save_data['operate_staff_id'] = $staff_id;
//        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

        // 修改主表
        foreach($save_data as $field => $val){
            $workObj->{$field} = $val;
        }
        $workObj->save();
        // 日志
        $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "重新派发员工!");
        if($send_staff_id > 0) { // 指定了员工
            // 工单派发记录
            $this->saveSends($workObj,  $staff_id , $operate_staff_history_id);
            $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, implode(",", $sendLogs));
        }
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
        if($workObj->status != 2 || $workObj->company_id != $company_id || $workObj->send_staff_id != $staff_id){
            throws("不可进行此操作!");
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

        $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);

        $operate_staff_history_id = $staffHistoryObj->id;
        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

//        $save_data['operate_staff_id'] = $staff_id;
//        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

        // 修改状态
        foreach($save_data as $field => $val){
            $workObj->{$field} = $val;
        }
        $workObj->status = 4;
        $workObj->save();
        // 日志
        $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "确认工单结单!内容："  . $win_content);
        // 统计处理数量
        $this->workRepairCount($workObj, $staff_id , $operate_staff_history_id);
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


        $re_content = $save_data['re_content'] ?? '';// 内容说明

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

        $this->getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id);

        $operate_staff_history_id = $staffHistoryObj->id;
        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

//        $save_data['operate_staff_id'] = $staff_id;
//        $save_data['operate_staff_history_id'] = $operate_staff_history_id;

        // 修改状态
        foreach($save_data as $field => $val){
            $workObj->{$field} = $val;
        }
        $workObj->status = 8;
        $workObj->save();
        // 日志
        $this->saveWorkLog($workObj , $staff_id , $operate_staff_history_id, "工单回访!内容：" . $re_content);
        return  okArray([]);
    }

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
    protected function saveWorkLog($workObj , $operate_staff_id , $operate_staff_history_id, $logContent){
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


    /**
     * 工单派发记录
     *
     * @param obj $workObj 当前工单对象
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    protected function saveSends($workObj, $operate_staff_id , $operate_staff_history_id){
        // 工单派发记录
        $workSends = [
            'company_id' => $workObj->company_id,
            'work_id' => $workObj->id,
            'work_status' => $workObj->status,
            'send_department_id' => $workObj->send_department_id,
            'send_department_name' => $workObj->send_department_name,
            'send_group_id' => $workObj->send_group_id,
            'send_group_name' => $workObj->send_group_name,
            'send_staff_id' => $workObj->send_staff_id,
            'send_staff_history_id' => $workObj->send_staff_history_id,
            'status' => 0, // 状态0可处理;1不可处理
            'operate_staff_id' => $operate_staff_id,// $workObj->operate_staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,// $workObj->operate_staff_history_id,
        ];
        $workSendsObj = null;
        Common::getObjByModelName("CompanyWorkSends", $workSendsObj);
        Common::create($workSendsObj, $workSends);
    }


    /**
     * 工单来电统计
     *
     * @param obj $workObj 当前工单对象
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    protected function workCallCount($workObj, $operate_staff_id , $operate_staff_history_id){
        $currentNow = Carbon::now();
        // 工单来电统计

        $workCallCountObj = null;
        Common::getObjByModelName("CompanyWorkCallCount", $workCallCountObj);

        $searchConditon = [
            'company_id' => $workObj->company_id,
            'operate_staff_id' => $operate_staff_id,// $workObj->operate_staff_id,
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
     * 工单维修统计
     *
     * @param obj $workObj 当前工单对象
     * @param int $operate_staff_id 操作员工id
     * @param int $operate_staff_history_id 操作员工历史id
     * @return null
     * @author zouyan(305463219@qq.com)
     */
    protected function workRepairCount($workObj, $operate_staff_id , $operate_staff_history_id){
        $currentNow = Carbon::now();
        // 工单来电统计

        $workRepairCountObj = null;
        Common::getObjByModelName("CompanyWorkRepairCount", $workRepairCountObj);

        $searchConditon = [
            'company_id' => $workObj->company_id,
            'operate_staff_id' => $operate_staff_id,// $workObj->operate_staff_id,
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
     * 员工获得主表+历史表对象
     *
     * @param obj $workObj 员工主对象
     * @param obj $staffHistoryObj 员工历史对象
     * @param int $company_id 公司id
     * @param int $staff_id 员工id
     * @return int 员工历史记录id
     * @author zouyan(305463219@qq.com)
     */
    protected function getHistoryStaff(&$staffObj = null , &$staffHistoryObj = null, $company_id = 0, $staff_id = 0 ){

        // 获是员工历史记录id-- 操作员工
        //$staffObj = null;
        Common::getObjByModelName("CompanyStaff", $staffObj);
        // $staffHistoryObj = null;
        Common::getObjByModelName("CompanyStaffHistory", $staffHistoryObj);
        $StaffHistorySearch = [
            'company_id' => $company_id,
            'staff_id' => $staff_id,
        ];

        Common::getHistory($staffObj, $staff_id, $staffHistoryObj,'company_staff_history', $StaffHistorySearch, []);
        $operate_staff_history_id = $staffHistoryObj->id;
        return $operate_staff_history_id;

    }
}
