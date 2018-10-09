<?php
// 工单
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
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

    // 统计总量类型 1 按天统计[当月天的] ; 2 按月统计[当年的]; 3 按年统计
    public static $countSumArr = [
        '1' => '当天',
        '2' => '按月统计',
        '3' => '按年统计',
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
        // $relations = ['CompanyInfo'];// 关系
        $relations = ['workHistoryStaffCreate', 'workHistoryStaffSend','workCustomer'];//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, $model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 加上 work_id字段
            if(! isset($data_list[$k]['work_id'])) $data_list[$k]['work_id'] = $v['id'];

            // 去掉内容
           // if(isset($data_list[$k]['content'])) unset($data_list[$k]['content']);
            if(isset($data_list[$k]['win_content'])) unset($data_list[$k]['win_content']);
            if(isset($data_list[$k]['reply_content'])) unset($data_list[$k]['reply_content']);

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
        return ajaxDataArr(1, $result, '');
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
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
//    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $notLog = 0){
//        $company_id = $controller->company_id;
//        if($id > 0){
//            // 判断权限
//            $judgeData = [
//                'company_id' => $company_id,
//            ];
//            $relations = '';
//            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
//
//        }else {// 新加;要加入的特别字段
//            $addNewData = [
//                'company_id' => $company_id,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }
//        // 加入操作人员信息
//        self::addOprate($request, $controller, $saveData);
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
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function saveById(Request $request, Controller $controller, $saveData, &$id, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);

        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
        }
        // 加入操作人员信息
        // self::addOprate($request, $controller, $saveData);
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
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

}
