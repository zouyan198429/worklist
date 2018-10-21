<?php
// 工单
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyWork extends BaseBusiness
{
    protected static $model_name = 'CompanyWork';
    protected static $model_doing_name = 'CompanyWorkDoing';

    // 状态 0新工单2待反馈工单[处理中];4待回访工单;8已完成工单
    public static  $status_arr = [
        // '-8' => '重点关注',
        //'0' => '待确认接单',
        '1' => '待确认',
        '2' => '处理中',
        '-4' => '过期未处理',
        '4' => '待回访',
        '8' => '已完成',
    ];

    // 统计类型 1 按天统计[当月天的] ; 2 按月统计[当年的]; 3 按年统计
    public static $countTypeArr = [
        '1' => '按天统计',
        '2' => '按月统计',
        '3' => '按年统计',
    ];

    // 统计总量类型
    // [来电] 0自定义时间段 ;1 今日 ; 2 本周; 3 上周 ;4 本月; 5 上月; 6本年; 7 上年
    // [处理] 8自定义时间段 ;9 今日 ; 10 本周; 11 上周 ;12 本月; 13 上月; 14本年; 15 上年
    public static $sumTypeArr = [
        // 来电记录
        '0' => [
            'name' => '时间段',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '1',
            ],
            'dataUbound' => 'callSumDateRange',
        ],
        '1' =>  [
            'name' => '今日',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '2',
            ],
            'dataUbound' => 'callSumToday',
        ],
        '2' =>  [
            'name' => '本周',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '4',
            ],
            'dataUbound' => 'callSumCurrentWeek',
        ],
        '3' =>  [
            'name' => '上周',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '8',
            ],
            'dataUbound' => 'callSumPreWeek',
        ],
        '4' =>  [
            'name' => '本月',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '16',
            ],
            'dataUbound' => 'callSumCurrentMonth',
        ],
        '5' =>  [
            'name' => '上月',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '32',
            ],
            'dataUbound' => 'callSumPreMonth',
        ],
        '6' =>  [
            'name' => '本年',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '64',
            ],
            'dataUbound' => 'callSumCurrentYear',
        ],
        '7' =>  [
            'name' => '上年',
            'parames' => [
                'operate_no' => '2048',
                'sum_operate_no' => '128',
            ],
            'dataUbound' => 'callSumPreYear',
        ],
        // 工单处理记录
        '8' => [
            'name' => '时间段',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '1',
            ],
            'dataUbound' => 'repairSumDateRange',
        ],
        '9' =>  [
            'name' => '今日',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '2',
            ],
            'dataUbound' => 'repairSumToday',
        ],
        '10' =>  [
            'name' => '本周',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '4',
            ],
            'dataUbound' => 'repairSumCurrentWeek',
        ],
        '11' =>  [
            'name' => '上周',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '8',
            ],
            'dataUbound' => 'repairSumPreWeek',
        ],
        '12' =>  [
            'name' => '本月',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '16',
            ],
            'dataUbound' => 'repairSumCurrentMonth',
        ],
        '13' =>  [
            'name' => '上月',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '32',
            ],
            'dataUbound' => 'repairSumPreMonth',
        ],
        '14' =>  [
            'name' => '本年',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '64',
            ],
            'dataUbound' => 'repairSumCurrentYear',
        ],
        '15' =>  [
            'name' => '上年',
            'parames' => [
                'operate_no' => '4096',
                'sum_repair_operate_no' => '128',
            ],
            'dataUbound' => 'repairSumPreYear',
        ],
    ];

    /**
     * 手机站首页页面初始化要填充的数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 数据
    //        $listData = [
    //            'waitSureCount' => $waitSureCount,// 1 待确认工单数量
    //            'doingCount' => $doingCount,// 2 处理中工单数量
    //            'msgList' => $total,// 4 未读消息
    //        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function mobileInitData(Request $request, Controller $controller)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'operate_no' => 1 + 2 + 4,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.initMobileWork');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
         if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
         }
        // $params = self::formatListParams($request, $controller, $queryParams);
        $status = Common::get($request, 'status');
        $field = Common::get($request, 'field');
        $keyWord = Common::get($request, 'keyWord');
        $model_name = self::$model_name;
        if(in_array($status,["-8", "-4", "0", "1", "2", "4"])){
            $model_name = self::$model_doing_name;
            if(in_array($status,["-8", "-4"])){
                $queryParams['orderBy'] = ['expiry_time'=>'desc','id'=>'desc'];
            }
        }
        if(!empty($status)){
            if($status == "-8"){ // 重点关注
                $queryParams['whereIn']['status'] = [0,1,2];
                array_push($queryParams['where'],['is_focus', 1]);
            }elseif($status == "-4"){ // 过期未处理
                $queryParams['whereIn']['status'] = [0,1,2];
                array_push($queryParams['where'],['is_overdue', 1]);
            }else{
                array_push($queryParams['where'],['status', $status]);
            }
        }

        if(!empty($field) && !empty($keyWord)){
            array_push($queryParams['where'],[$field, 'like' , '%' . $keyWord . '%']);
        }

        $begin_date = Common::get($request, 'begin_date');// 开始日期
        $end_date = Common::get($request, 'end_date');// 结束日期
        $today_date = date("Y-m-d");
        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_date, $end_date, 4 + 32 + 256);
        if(!empty($begin_date)) {
            $begin_date = judge_date($begin_date, 'Y-m-d H:i:s');
            array_push($queryParams['where'],['created_at', '>=' , $begin_date]);
        }
        if(!empty($end_date)) {
            $end_date = judge_date(day_format_time(2, $end_date, 0), 'Y-m-d H:i:s');
            array_push($queryParams['where'],['created_at', '<' , $end_date]);
        }

        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
        if (!empty($ids)) {
            if (strpos($ids, ',') === false) { // 单条
                array_push($queryParams['where'],['id', $ids]);
            }else{
                $queryParams['whereIn']['id'] = explode(',',$ids);
            }
        }
        $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
        if($isExport == 1) $oprateBit = 1;
        // $relations = ['CompanyInfo'];// 关系
        $relations = ['workHistoryStaffCreate', 'workHistoryStaffSend','workCustomer'];//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, $model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 加上 work_id字段
            if(! isset($data_list[$k]['work_id'])) $data_list[$k]['work_id'] = $v['id'];

            if($isExport == 1) {
                $content = $v['content'] ?? '';
                $data_list[$k]['content'] = replace_enter_char($content, 2);

                $win_content = $v['win_content'] ?? '';
                $data_list[$k]['win_content'] = replace_enter_char($win_content, 2);

                $reply_content = $v['reply_content'] ?? '';
                $data_list[$k]['reply_content'] = replace_enter_char($reply_content, 2);
            }else{
                // 去掉内容
                // if(isset($data_list[$k]['content'])) unset($data_list[$k]['content']);
                if(isset($data_list[$k]['win_content'])) unset($data_list[$k]['win_content']);
                if(isset($data_list[$k]['reply_content'])) unset($data_list[$k]['reply_content']);


            }
            // 添加员工名称
            $data_list[$k]['real_name'] = ($v['work_history_staff_create']['real_name'] ?? '') . '[' .  ($v['work_history_staff_create']['work_num'] ?? '') . '；' .  ($v['work_history_staff_create']['mobile'] ?? '') . ']';
            if(isset($data_list[$k]['work_history_staff_create'])) unset($data_list[$k]['work_history_staff_create']);
            // 指派员工名称
            $data_list[$k]['send_real_name'] = ($v['work_history_staff_send']['real_name'] ?? '') . '[' .  ($v['work_history_staff_send']['work_num'] ?? '') . '；' .  ($v['work_history_staff_send']['mobile'] ?? '') . ']';
            if(isset($data_list[$k]['work_history_staff_send'])) unset($data_list[$k]['work_history_staff_send']);

            // 最后来电时间
            $data_list[$k]['last_call_date'] = $v['work_customer']['last_call_date'] ?? '' ;
            $data_list[$k]['call_num'] = $v['work_customer']['call_num'] ?? '' ;
            if(isset($data_list[$k]['work_customer'])) unset($data_list[$k]['work_customer']);

        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
            $headArr = ['work_num'=>'工单号', 'status_text'=>'状态', 'call_number'=>'来电号码', 'contact_number'=>'联系电话', 'caller_type_name'=>'工单来源', 'type_name'=>'工单类型',
                        'business_name'=>'工单业务', 'content'=>'工单内容', 'city_name'=>'客户区县', 'area_name'=>'客户街道', 'address'=>'客户地址',
                        'created_at'=>'下单时间', 'time_name'=>'工单等级', 'expiry_time'=>'到期时间', 'department_name'=>'派单人县区', 'group_name'=>'派单人归属营业厅或片区',
                        'real_name'=>'派单人姓名或渠道名称', 'send_department_name'=>'收单人县区', 'send_group_name'=>'收单人归属营业厅或片区', 'send_real_name'=>'收单人姓名或渠道名称',
                 'win_content'=>'结单内容', 'reply_content'=>'回访内容'];
            ImportExport::export('','工单',$data_list,1, $headArr, 0, ['sheet_title' => '工单']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 格式化列表查询条件-暂不用
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $queryParams 条件数组/json字符
     * @return  array 参数数组 一维数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function formatListParams(Request $request, Controller $controller, &$queryParams = []){
//        $params = [];
//        $title = Common::get($request, 'title');
//        if(!empty($title)){
//            $params['title'] = $title;
//            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
//        }
//
//        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
//        if (!empty($ids)) {
//            $params['ids'] = $ids;
//            if (strpos($ids, ',') === false) { // 单条
//                array_push($queryParams['where'],['id', $ids]);
//            }else{
//                $queryParams['whereIn']['id'] = explode(',',$ids);
//                $params['idArr'] = explode(',',$ids);
//            }
//        }
//        return $params;
//    }

    /**
     * 获得当前记录前/后**条数据--二维数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $nearType 类型 1:前**条[默认]；2后**条 ; 4 最新几条;8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
     * @param int $limit 数量 **条
     * @param int $offset 偏移数量
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据 - 二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearList(Request $request, Controller $controller, $id = 0, $nearType = 1, $limit = 1, $offset = 0, $queryParams = [], $relations = '', $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 前**条[默认]
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
//                ['id', '>', $id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'asc'],
            'limit' => $limit,
            'offset' => $offset,
            // 'count'=>'0'
        ];
        if(($nearType & 1) == 1){// 前**条
            $defaultQueryParams['orderBy'] = ['id'=>'asc'];
            array_push($defaultQueryParams['where'],['id', '>', $id]);
        }

        if(($nearType & 2) == 2){// 后*条
            array_push($defaultQueryParams['where'],['id', '<', $id]);
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 4) == 4){// 4 最新几条
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 8) == 8){// 8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
            $defaultQueryParams['count'] = 0;
        }

        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, $notLog);
        // 格式化数据
        $data_list = $result['result']['data_list'] ?? [];
        if($nearType == 1) $data_list = array_reverse($data_list); // 相反;
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['result']['data_list'] = $data_list;
        return $data_list;
    }

    /**
     * 导入模版
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function importTemplate(Request $request, Controller $controller)
    {
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }
    /**
     * 通过状态获得列表数据--数据[待处理]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param mixed $relations 关系
     * @param int $status 状态0新工单2待反馈工单;4待回访工单;8已完成工单 , 如果要查多个用逗号分隔 ,
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getListByStatus(Request $request, Controller $controller, $oprateBit = 2 + 4, $status = 0, $relations = '', $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['send_staff_id', $controller->user_id],
               // ['status', $status],
            ],
//            'whereIn' => [
//                'status'=> [0,1,2,4,8],
//            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            // 'orderBy' => ['expiry_time'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
            // 'orderBy' => ['expiry_time'=>'desc','id'=>'desc'],
        ];
        $model_name = self::$model_name;
        $statusArr = explode(',', $status);
        if(!empty($statusArr) && !in_array(8, $statusArr) ){// 不是空数组 且 没有已完成的状态
            $model_name = self::$model_doing_name;
            if(in_array('-4', $statusArr) || in_array('-8', $statusArr)){
                $queryParams['orderBy'] = ['expiry_time'=>'desc','id'=>'desc'];
            }
        }
        if(count($statusArr) > 1){
            $queryParams['whereIn'] = [
                'status'=> $statusArr,
            ];
        }else{
            if($status == "-8"){ // 重点关注
                $queryParams['whereIn']['status'] = [0,1,2];
                array_push($queryParams['where'],['is_focus', 1]);
            }elseif($status == "-4"){ // 过期未处理
                $queryParams['whereIn']['status'] = [0,1,2];
                array_push($queryParams['where'],['is_overdue', 1]);
            }else{
                array_push($queryParams['where'],['status', $status]);
            }
        }
//        if(in_array($status, [1,2])){
//            $queryParams['orderBy'] = ['book_time'=>'desc','id'=>'desc'];
//        }else{
//            $queryParams['orderBy'] = ['id'=>'desc'];
//        }
        // 查询条件参数
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, $model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 加上 work_id字段
            if(! isset($data_list[$k]['work_id'])) $data_list[$k]['work_id'] = $v['id'];
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
        }
        // Tool::formatTwoArrKeys($data_list,Tool::arrEqualKeyVal(['id', 'work_num', 'caller_type_name', 'customer_id', 'customer_name']), false);
        $result['data_list'] = $data_list;
        // return ajaxDataArr(1, $result, '');
        // 格式化数据
        // return $result;
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function delAjax(Request $request, Controller $controller)
//    {
//        return self::delAjaxBase($request, $controller, self::$model_name);
//
//    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param mixed $relations 关系
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $relations = ''){
        $company_id = $controller->company_id;
        // $relations = '';
        $resultDatas = CommonBusiness::getinfoApi(self::$model_name, $relations, $company_id , $id);
        // $resultDatas = self::getInfoDataBase($request, $controller, self::$model_name, $id, $relations);
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        // 加上 work_id字段
        if(! isset($resultDatas['work_id'])) $resultDatas['work_id'] = $resultDatas['id'];
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
        return $resultDatas;
    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getShowInfoData(Request $request, Controller $controller, $id){
        // 工单日志和派发记录
        $relations = ['workSends.workSendHistoryStaffCreate', 'workSends.workSendHistoryStaffSend', 'workLogs.workLogHistoryStaffCreate'];
        $resultDatas = self::getInfoData($request, $controller, $id, $relations);

        // 操作日志
        $workLogs = $resultDatas['work_logs'] ?? [];
        if(isset($resultDatas['work_logs'])) unset($resultDatas['work_logs']);
        $temLogs = [];
        $temNeeds = ['created_at', 'content'];
        foreach($workLogs as $v){
            $temNameArr = [
                'real_name' => $v['work_log_history_staff_create']['real_name'] . "[" . $v['work_log_history_staff_create']['work_num'] . "；" . $v['work_log_history_staff_create']['mobile'] . "]",
            ];
            Tool::formatArrKeys($v , Tool::arrEqualKeyVal($temNeeds));
            $temLogs[] = array_merge($v, $temNameArr);
        }
        $resultDatas['logs'] = $temLogs;

        // 分派日志
        $sendLogs = $resultDatas['work_sends'] ?? [];
        if(isset($resultDatas['work_sends'])) unset($resultDatas['work_sends']);
        $temSends = [];
        $temNeeds = ['created_at', 'work_status', 'send_department_id', 'send_department_name',
            'send_group_id', 'send_group_name', 'send_staff_id', 'operate_staff_id'];
        foreach($sendLogs as $v){
            $temNameArr = [
                'send_staff_name' => $v['work_send_history_staff_send']['real_name'] . "[" . $v['work_send_history_staff_send']['work_num'] . "；" . $v['work_send_history_staff_send']['mobile'] . "]",
                'operate_staff_name' => $v['work_send_history_staff_create']['real_name'] . "(" . $v['work_send_history_staff_create']['work_num'] . "；" . $v['work_send_history_staff_create']['mobile'] . ")",
            ];
            Tool::formatArrKeys($v , Tool::arrEqualKeyVal($temNeeds));
            $temSends[] = array_merge($v, $temNameArr);
        }
        $resultDatas['sendLogs'] = $temSends;
        return $resultDatas;
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
//    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
//        $company_id = $controller->company_id;
//        if($id > 0){
//            // 判断权限
//            $judgeData = [
//                'company_id' => $company_id,
//            ];
//            $relations = '';
//            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
//            if($modifAddOprate) self::addOprate($request, $controller, $saveData);
//        }else {// 新加;要加入的特别字段
//            $addNewData = [
//                'company_id' => $company_id,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//              // 加入操作人员信息
//              self::addOprate($request, $controller, $saveData);
//        }
//        // 新加或修改
//        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
//    }


    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function saveById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            // if($modifAddOprate) self::addOprate($request, $controller, $saveData);
        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            // self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        // return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);

        // 参数
        $requestData = [
            'id' => $id,
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.saveWork');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        $result = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
        if($id <= 0){
            $id = $result['id'] ?? 0;
        }
        return $result;
    }

    /**
     * 添加页面初始化要填充的数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 数据
    //        $listData = [
    //            'workFirstList' => $workFirstList,// 1  工单分类第一级
    //            'workCallTypeList' => $page,//  2 工单来电类型
    //            'serviceTagList' => $total,// 4 业务标签
    //            'serviceTimeList' => $aaa,// 8 业务时间
    //            'customerTypeList' => $requestData,// 16 客户类型
    //            'areaCityList' => $requestData,// 32 客户地区
    //            'departmentFirstList' => $requestData,// 64 部门信息
    //        ];
     * @author zouyan(305463219@qq.com)
     */
    public static function addInitData(Request $request, Controller $controller)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'operate_no' => 1 + 2 + 4 + 8 + 16 + 32 + 64 ,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workAddInit');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 确认工单
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function workSure(Request $request, Controller $controller, $saveData , &$id, $notLog = 0)
    {
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
                'send_staff_id' => $controller->user_id,
            ];
            $relations = '';
            $infoData = CommonBusiness::judgePower($id, $judgeData, self::$model_doing_name, $company_id, $relations, $notLog);
            // 判断状态
            $status = $infoData['status'] ?? '';
            if($status != 1){
                throws('当前记录不可进行此操作!');
            }
        }else {// 新加;要加入的特别字段
            throws("参数有误!");
        }
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'id' => $id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workSure');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 结单
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function workWin(Request $request, Controller $controller, $saveData , &$id, $notLog = 0)
    {
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
                'send_staff_id' => $controller->user_id,
            ];
            $relations = '';
            $infoData = CommonBusiness::judgePower($id, $judgeData, self::$model_doing_name, $company_id, $relations, $notLog);
            // 判断状态
            $status = $infoData['status'] ?? '';
            if($status != 2){
                throws('当前记录不可操作!');
            }
        }else {// 新加;要加入的特别字段
            throws("参数有误!");
        }
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'id' => $id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workWin');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 回访
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function workReply(Request $request, Controller $controller, $saveData , &$id, $notLog = 0)
    {
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
                // 'operate_staff_id' => $controller->user_id,
            ];
            $relations = '';
            $infoData = CommonBusiness::judgePower($id, $judgeData, self::$model_doing_name, $company_id, $relations, $notLog);
            // 判断状态
            $status = $infoData['status'] ?? '';
            if($status != 4){
                throws('当前记录不可操作!');
            }
        }else {// 新加;要加入的特别字段
            throws("参数有误!");
        }
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'id' => $id,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workReply');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }


    /**
     * 工单状态统计
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function statusCount(Request $request, Controller $controller, $staff_id = 0, $operate_staff_id = 0, $notLog = 0)
    {
        $company_id = $controller->company_id;
        // $staff_id = Common::getInt($request, 'staff_id');// 接收员工id
        // $operate_staff_id = Common::getInt($request, 'operate_staff_id');// 添加员工id
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $staff_id,
            'operate_staff_id' => $operate_staff_id,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workStatusCount');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 工单状态统计
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $params
        $params = [
            'operate_no' => '操作编号',
            'send_department_id' => '派到部门id',
            'send_group_id' => '派到小组id',
            'department_id' => '部门id',
            'group_id' => '小组id',
            'staff_id' => '接收员工id',
            'operate_staff_id' => '添加员工id',
            'begin_date' => '开始日期',
            'end_date' => '结束日期',
        ];
        操作编号的值及下标
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
     *
     * @param int $operate_staff_id 添加员工id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function workCount(Request $request, Controller $controller, $params = [], $notLog = 0)
    {
        $company_id = $controller->company_id;
        // $staff_id = Common::getInt($request, 'staff_id');// 接收员工id
        // $operate_staff_id = Common::getInt($request, 'operate_staff_id');// 添加员工id
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'operate_no' => $params['operate_no'] ?? 0,// 操作编号
            'sum_operate_no' => $params['sum_operate_no'] ?? 0,// 来电数量统计操作编号
            'sum_repair_operate_no' => $params['sum_repair_operate_no'] ?? 0,// 处理来电数量统计操作编号
            'send_department_id' => $params['send_department_id'] ?? 0,// 派到部门id
            'send_group_id' => $params['send_group_id'] ?? 0,// 派到小组id
            'department_id' => $params['department_id'] ?? 0,// 部门id
            'group_id' => $params['group_id'] ?? 0,// 小组id
            'staff_id' =>  $params['staff_id'] ?? 0,// 接收员工id
            'operate_staff_id' => $params['operate_staff_id'] ?? 0,// 添加员工id
            'begin_date' => $params['begin_date'] ?? 0,// 开始日期
            'end_date' => $params['end_date'] ?? 0,// 结束日期
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.workCount');
        // 生成带参数的测试get请求
        $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }
    /**
     * 工单状态统计
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $staff_id 接收员工id
     * @param int $operate_staff_id 添加员工id
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return array [ $sumTypeArr 下标=>['amount' => 0 , 'begin_date' => '', 'end_date' => ''],...]
     * @author zouyan(305463219@qq.com)
     */
    public static function sumCount(Request $request, Controller $controller, $staff_id = 0, $operate_staff_id = 0, $notLog = 0)
    {
//        $company_id = $controller->company_id;
        $begin_date = Common::get($request, 'begin_date');// 开始日期
        $end_date = Common::get($request, 'end_date');// 结束日期
        if(empty($begin_date)) $begin_date = date("Y-01-01");
        if(empty($end_date)) $end_date = date("Y-m-d");

        $sumStatus = Common::get($request, 'sum_status');// $sumTypeArr 下标 统计总量类型;多个用,逗号分隔
        if(is_string($sumStatus)){
            $sumStatus = explode(',', $sumStatus);
        }
        $nowTime = time();
        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($begin_date, $end_date, 4 + 32 + 256);
        $operate_no = 0;
        $sum_operate_no = 0;
        $sum_repair_operate_no = 0;
        $sumTypeArr = self::$sumTypeArr;
        foreach($sumStatus as $sum_status){
           if(!isset($sumTypeArr[$sum_status])) continue;
            $temStatus = $sumTypeArr[$sum_status];
            $temOperateNo = $temStatus['parames']['operate_no'] ?? 0;
            $temSumOperateNo = $temStatus['parames']['sum_operate_no'] ?? 0;
            $temSumRepairOperateNo = $temStatus['parames']['sum_repair_operate_no'] ?? 0;
            $operate_no = ($operate_no | $temOperateNo);
            $sum_operate_no = ($sum_operate_no | $temSumOperateNo);
            $sum_repair_operate_no = ($sum_repair_operate_no | $temSumRepairOperateNo);
        }

        $params = [
            'operate_no' => $operate_no,// 操作编号
            'sum_operate_no' => $sum_operate_no,// 来电数量统计操作编号
            'sum_repair_operate_no' => $sum_repair_operate_no,// 处理来电数量统计操作编号
            'send_department_id' => '0',// 派到部门id
            'send_group_id' => '0',// 派到小组id
            'department_id' => '0',// 部门id
            'group_id' => '0',// 小组id
            'staff_id' => $staff_id,// 接收员工id
            'operate_staff_id' => $operate_staff_id,// 添加员工id
            'begin_date' => $begin_date,// 开始日期
            'end_date' => $end_date,// 结束日期
        ];
        $sumArr = self::workCount($request, $controller, $params);
        $reData = [];
        foreach($sumStatus as $sum_status) {
            if (!isset($sumTypeArr[$sum_status])) continue;
            $temStatus = $sumTypeArr[$sum_status];
            $temDataUbound = $temStatus['dataUbound'] ?? 0;
            $temSumArr = $sumArr[$temDataUbound] ?? ['amount' => 0 , 'begin_date' => '', 'end_date' => ''];
            $reData[$sum_status] = $temSumArr;
        }
        return $reData;
    }

}
