<?php
// 同事/员工
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
class CompanyStaff extends BaseBusiness
{
    protected static $model_name = 'CompanyStaff';
    public static $admin_type = 0;
    public static $admin_types = [
         '0' => '员工',
//        '1' => '管理员',
//        '2' => '超级管理员',
    ];

    /**
     * 登录
     * @author zouyan(305463219@qq.com)
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param boolean $judgeSuper 控制对象 true : 部门id department_id = 120分公司 才能访问;false:其它人员
     * @return  array 用户信息[一维数组]
     */
    public static function login_back(Request $request, Controller $controller, $judgeSuper = true){
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
        if(!is_numeric($preKey)){
            $preKey = 1;
        }
        // 数据验证 TODO

        // $company_id = config('public.company_id');

        $modelName = self::$model_name;
        // 查询用户名是否有
        $queryParams = [
            'where' => [
                // ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
                ['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id','admin_username','real_name','issuper','account_status','work_num',
//                'department_id','group_id','position_id','sex'
//                ,'tel','mobile','qq_number','lastlogintime'
//            ],
//            'orWhere' => [
//                ['mobile',$admin_username],
//                ['admin_password',md5($admin_password)],
//            ],
            // 'limit' => 1
        ];
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];
        $relations = ['staffDepartment', 'staffGroup', 'staffPosition', 'staffCompany'];// , 'staffRoles'
        //if($preKey == 0) {
        //$relations = '';//['CompanyInfo.CompanyRank'];
        //}
        $resultDatas = CommonBusiness::ajaxGetList($modelName, $pageParams, 0,$queryParams ,$relations, 1);

        $dataList = $resultDatas['dataList'] ?? [];
        $userInfo = $dataList[0] ?? [];
        if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)) {
            // 查询手机号是否有
            $queryParams = [
                'where' => [
                    //['company_id',$company_id],
                    ['mobile', $admin_username],
                    ['admin_password', md5($admin_password)],
                    ['admin_type',self::$admin_type],
                ],
//                'select' => [
//                    'id','company_id','real_name',
//                ],
                // 'limit' => 1
            ];
            $pageParams = [
                'page' => 1,
                'pagesize' => 1,
                'total' => 1,
            ];
            $resultDatas = CommonBusiness::ajaxGetList($modelName, $pageParams, 0, $queryParams, $relations, 1);
            $dataList = $resultDatas['dataList'] ?? [];
            $userInfo = $dataList[0] ?? [];

            if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)){
                throws('用户名或密码有误！');
                // return ajaxDataArr(0, null, '用户名或密码有误！');
            }
        }

        $department_id = $userInfo['department_id'] ?? '';
        if($judgeSuper && !in_array($department_id,[120]))  throws('您不是分公司员工，没有权限访问！');

        if(!$judgeSuper && in_array($department_id,[120]))  throws('您是分公司员工，没有权限访问！');

        $account_id = $userInfo['id'] ?? 0;
        $company_id = $userInfo['company_id'] ?? 0;
        $account_status = $userInfo['account_status'] ?? 1;
        if($account_status != 0){
            throws('账号已冻结！');
            //return ajaxDataArr(0, null, '账号已冻结！');
        }
        // 部门
        $userInfo['department_name'] = $userInfo['staff_department']['department_name'] ?? '';
        if(isset($userInfo['staff_department'])) unset($userInfo['staff_department']);

        // 小组
        $userInfo['group_name'] = $userInfo['staff_group']['department_name'] ?? '';
        if(isset($userInfo['staff_group'])) unset($userInfo['staff_group']);

        // 职位
        $userInfo['position_name'] = $userInfo['staff_position']['position_name'] ?? '';
        if(isset($userInfo['staff_position'])) unset($userInfo['staff_position']);



        //开始时间
//        $company_vipbegin = $userInfo['company_info']['company_vipbegin'] ?? '';
//        $company_vipbegin = judgeDate($company_vipbegin,"Y-m-d");
//        if($company_vipbegin !== false) {
//            $userInfo['company_info']['company_vipbegin'] = $company_vipbegin;
//        }
//        // 结束时间
//        $company_vipend = $userInfo['company_info']['company_vipend'] ?? '';
//        $company_vipend = judgeDate($company_vipend,"Y-m-d");
//        if($company_vipend !== false) {
//            $userInfo['company_info']['company_vipend'] = $company_vipend;
//        }

        //更新上次登陆时间
//        $company_id = $userInfo['company_id'] ??  0;
//        $saveData = [
//            'company_lastlogintime' => date('Y-m-d H:i:s',time()),
//        ];
//        CommonBusiness::saveByIdApi('Company', $company_id, $saveData, $company_id, 1);

        $saveData = [
            'lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        CommonBusiness::saveByIdApi($modelName, $account_id, $saveData, $company_id, 1);


        $userInfo['modifyTime'] = time();
        // 保存session
        // 存储数据到session...
        if (!session_id()) session_start(); // 初始化session
        // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
        $redisKey = $controller->setUserInfo($userInfo, $preKey);
        $userInfo['redisKey'] = $redisKey;
        return ajaxDataArr(1, $userInfo, '');
    }


    /**
     * 登录
     * @author zouyan(305463219@qq.com)
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param boolean $judgeSuper 控制对象 true : 部门id department_id = 120分公司 才能访问;false:其它人员
     * @return  array 用户信息[一维数组]
     */
    public static function login(Request $request, Controller $controller, $judgeSuper = true){
        $company_id = Common::getInt($request, 'company_id');
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
        if(!is_numeric($preKey)){
            $preKey = 1;
        }
        // 数据验证 TODO

        // $company_id = config('public.company_id');

        $modelName = self::$model_name;
        // 查询用户名是否有
        $queryParams = [
            'where' => [
                 ['company_id',$company_id],
                // ['admin_username',$admin_username],
                ['work_num',$admin_username],
                ['admin_password',md5($admin_password)],
                 ['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id','admin_username','real_name','issuper','account_status','work_num',
//                'department_id','group_id','position_id','sex'
//                ,'tel','mobile','qq_number','lastlogintime'
//            ],
//            'orWhere' => [
//                ['mobile',$admin_username],
//                ['admin_password',md5($admin_password)],
//            ],
            // 'limit' => 1
        ];
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];
        $relations = ['staffDepartment', 'staffGroup', 'staffPosition', 'staffCompany'];// , 'staffRoles'
        //if($preKey == 0) {
        //$relations = '';//['CompanyInfo.CompanyRank'];
        //}
        $resultDatas = CommonBusiness::ajaxGetList($modelName, $pageParams, 0,$queryParams ,$relations, 1);

        $dataList = $resultDatas['dataList'] ?? [];
        if(count($dataList) > 1) throws('帐号有重复，请联系管理员修改重复帐号！');
        $userInfo = $dataList[0] ?? [];
        if(true){
            if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)){
                throws('帐号或密码有误！');
                // return ajaxDataArr(0, null, '用户名或密码有误！');
            }
        }else{
            if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)) {
                // 查询手机号是否有
                $queryParams = [
                    'where' => [
                        ['company_id',$company_id],
                        ['mobile', $admin_username],
                        ['admin_password', md5($admin_password)],
                        ['admin_type',self::$admin_type],
                    ],
//                'select' => [
//                    'id','company_id','real_name',
//                ],
                    // 'limit' => 1
                ];
                $pageParams = [
                    'page' => 1,
                    'pagesize' => 1,
                    'total' => 1,
                ];
                $resultDatas = CommonBusiness::ajaxGetList($modelName, $pageParams, 0, $queryParams, $relations, 1);
                $dataList = $resultDatas['dataList'] ?? [];
                $userInfo = $dataList[0] ?? [];

                if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)){
                    throws('用户名或密码有误！');
                    // return ajaxDataArr(0, null, '用户名或密码有误！');
                }
            }
        }

        $staff_company = $userInfo['staff_company'];
        if(empty($staff_company)) throws('企业信息不存在！');
        $company_name = $staff_company['company_name'] ?? '';// 企业名称

        $module_no = $staff_company['module_no'] ?? 0;// 开通模块编号
        $module_no_text = $staff_company['module_no_text'] ?? '';// 开通模块编号名称

        $open_status = $staff_company['open_status'] ?? 0;// 开通状态1开通；2关闭；4作废【过时关闭】；
        $open_status_text = $staff_company['open_status_text'] ?? '';// 开通状态文字
        if($open_status != Company::OPEN_STATUS_OPEN) throws('企业【' . $company_name . '】状态【' . $open_status_text . '】，不可以进行登录！');

        $send_work_department_id = $staff_company['send_work_department_id'] ?? 0;// 接线部门id

        $department_id = $userInfo['department_id'] ?? '';
        if($send_work_department_id > 0 && $judgeSuper && $department_id != $send_work_department_id){
            throws('您不是接线部门的员工，没有权限访问！');
        }
        // if($judgeSuper && !in_array($department_id,[120]))  throws('您不是分公司员工，没有权限访问！');

        // if(!$judgeSuper && in_array($department_id,[120]))  throws('您是分公司员工，没有权限访问！');

        $account_id = $userInfo['id'] ?? 0;
        $company_id = $userInfo['company_id'] ?? 0;
        $account_status = $userInfo['account_status'] ?? 1;
        if($account_status != 0){
            throws('账号已冻结！');
            //return ajaxDataArr(0, null, '账号已冻结！');
        }

        // 部门
        $userInfo['department_name'] = $userInfo['staff_department']['department_name'] ?? '';
        if(isset($userInfo['staff_department'])) unset($userInfo['staff_department']);

        // 小组
        $userInfo['group_name'] = $userInfo['staff_group']['department_name'] ?? '';
        if(isset($userInfo['staff_group'])) unset($userInfo['staff_group']);

        // 职位
        $userInfo['position_name'] = $userInfo['staff_position']['position_name'] ?? '';
        if(isset($userInfo['staff_position'])) unset($userInfo['staff_position']);



        //开始时间
//        $company_vipbegin = $userInfo['company_info']['company_vipbegin'] ?? '';
//        $company_vipbegin = judgeDate($company_vipbegin,"Y-m-d");
//        if($company_vipbegin !== false) {
//            $userInfo['company_info']['company_vipbegin'] = $company_vipbegin;
//        }
//        // 结束时间
//        $company_vipend = $userInfo['company_info']['company_vipend'] ?? '';
//        $company_vipend = judgeDate($company_vipend,"Y-m-d");
//        if($company_vipend !== false) {
//            $userInfo['company_info']['company_vipend'] = $company_vipend;
//        }

        //更新上次登陆时间
//        $company_id = $userInfo['company_id'] ??  0;
//        $saveData = [
//            'company_lastlogintime' => date('Y-m-d H:i:s',time()),
//        ];
//        CommonBusiness::saveByIdApi('Company', $company_id, $saveData, $company_id, 1);

        $saveData = [
            'lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        CommonBusiness::saveByIdApi($modelName, $account_id, $saveData, $company_id, 1);


        $userInfo['modifyTime'] = time();
        // 保存session
        // 存储数据到session...
        if (!session_id()) session_start(); // 初始化session
        // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
        $redisKey = $controller->setUserInfo($userInfo, $preKey);
        $userInfo['redisKey'] = $redisKey;
        return ajaxDataArr(1, $userInfo, '');
    }

    /**
     * 退出登录
     *
     * @author zouyan(305463219@qq.com)
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     */
    public static function loginOut(Request $request, Controller $controller)
    {

        // $this->InitParams($request);
        // session_start(); // 初始化session
        //$userInfo = $_SESSION['userInfo'] ?? [];
        /*
        if(isset($_SESSION['userInfo'])){
            unset($_SESSION['userInfo']); //保存某个session信息
        }
        */
        return $controller->delUserInfo();
    }

    /**
     * 获得列表数据--所有数据[根据区域]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $city_id 区县id
     * @param int $area_id 街道id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据[一维的键=>值数组]
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListKeyValByArea(Request $request, Controller $controller, $city_id, $area_id, $oprateBit = 2 + 4, $notLog = 0){
        $parentData = self::getChildListByArea($request, $controller, $city_id, $area_id, $oprateBit, $notLog);
        $department_list = $parentData['result']['data_list'] ?? [];
        foreach($department_list as $k => $v){
            $department_list[$k]['real_name'] = $v['real_name'] . '[' . $v['work_num'] . ']';
        }
        return Tool::formatArrKeyVal($department_list, 'id', 'real_name');
    }

    /**
     * 获得列表数据--所有数据[根据区域]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $city_id 区县id
     * @param int $area_id 街道id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListByArea(Request $request, Controller $controller, $city_id, $area_id, $oprateBit = 2 + 4, $notLog = 0){
        $company_id = $controller->company_id;
        // 获得数据
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['city_id', $city_id],
                ['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id', 'admin_username', 'real_name','mobile'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
            //'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(is_numeric($area_id) && $area_id > 0){
            array_push($queryParams['where'],['area_id', $area_id]);
        }
        // $relations = ['CompanyInfo'];// 关系
        $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 获得列表数据--所有数据[根据部门小组]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据[一维的键=>值数组]
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListKeyValByDepartment(Request $request, Controller $controller, $department_id, $group_id, $oprateBit = 2 + 4, $notLog = 0){
        $parentData = self::getChildListByDepartment($request, $controller, $department_id, $group_id, $oprateBit, $notLog);
        $department_list = $parentData['result']['data_list'] ?? [];
        foreach($department_list as $k => $v){
            $department_list[$k]['real_name'] = $v['real_name'] . '[' . $v['work_num'] . '；' . $v['mobile'] . ']';
        }
        return Tool::formatArrKeyVal($department_list, 'id', 'real_name');
    }

    /**
     * 获得列表数据--所有数据[根据部门小组]
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $department_id 部门id
     * @param int $group_id 小组id
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getChildListByDepartment(Request $request, Controller $controller, $department_id, $group_id, $oprateBit = 2 + 4, $notLog = 0){
        $company_id = $controller->company_id;
        // 获得数据
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['department_id', $department_id],
                ['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id', 'admin_username', 'real_name','mobile'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
            //'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(is_numeric($group_id) && $group_id > 0){
            array_push($queryParams['where'],['group_id', $group_id]);
        }
        // $relations = ['CompanyInfo'];// 关系
        $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param array $extParams 其它扩展参数，
     *    $extParams = [
     *        'useQueryParams' => '是否用来拼接查询条件，true:用[默认];false：不用'
     *   ];
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $extParams = [], $notLog = 0){
        $company_id = $controller->company_id;
          // 获得数据
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
                ['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $isExport = 0;

        $useSearchParams = $extParams['useQueryParams'] ?? true;// 是否用来拼接查询条件，true:用[默认];false：不用
        if($useSearchParams) {
            // $params = self::formatListParams($request, $controller, $queryParams);
            $department_id = Common::getInt($request, 'department_id');
//            $keyword = Common::get($request, 'keyword');
            $field = Common::get($request, 'field');
            $keyWord = Common::get($request, 'keyWord');

            $position_ids = Common::get($request, 'position_ids');// 职位数组
            if(!empty($position_ids)){
                if(!is_array($position_ids)) $position_ids = explode(',', $position_ids);
                $queryParams['whereIn']['position_id'] = $position_ids;
            }

            if ($department_id > 0) {
                array_push($queryParams['where'], ['department_id', $department_id]);
            }
            $group_id = Common::getInt($request, 'group_id');
            if ($group_id > 0) {
                array_push($queryParams['where'], ['group_id', $group_id]);
            }

//            if (!empty($keyword)) {
//                array_push($queryParams['where'], ['real_name', 'like', '%' . $keyword . '%']);
//            }
            if (!empty($field) && !empty($keyWord)) {
                array_push($queryParams['where'], [$field, 'like', '%' . $keyWord . '%']);
            }
            $ids = Common::get($request, 'ids');// 多个用逗号分隔,
            if (!empty($ids)) {
                if (strpos($ids, ',') === false) { // 单条
                    array_push($queryParams['where'], ['id', $ids]);
                } else {
                    $queryParams['whereIn']['id'] = explode(',', $ids);
                }
            }
            $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
            if ($isExport == 1) $oprateBit = 1;
        }
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $relations = ['staffDepartment','staffGroup','staffPosition'];// 关系
        //pr($queryParams);
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            // 公司名称
            // $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
            // if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
            // 部门名称
            $data_list[$k]['department_name'] = $v['staff_department']['department_name'] ?? '';
            if(isset($data_list[$k]['staff_department'])) unset($data_list[$k]['staff_department']);
            // 小组名称
            $data_list[$k]['group_name'] = $v['staff_group']['department_name'] ?? '';
            if(isset($data_list[$k]['staff_group'])) unset($data_list[$k]['staff_group']);
            // 职位
            $data_list[$k]['position_name'] = $v['staff_position']['position_name'] ?? '';
            if(isset($data_list[$k]['staff_position'])) unset($data_list[$k]['staff_position']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
            $headArr = ['work_num'=>'帐号', 'department_name'=>'县区', 'group_name'=>'归属营业厅或片区', 'real_name'=>'姓名或渠道名称', 'sex_text'=>'性别', 'position_name'=>'职务', 'mobile'=>'手机号'];
            ImportExport::export('','员工列表',$data_list,1, $headArr, 0, ['sheet_title' => '员工列表']);
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
                ['admin_type',self::$admin_type],
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
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, [], $notLog);
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
        $headArr = ['work_num'=>'帐号', 'department_name'=>'县区', 'group_name'=>'归属营业厅或片区', 'real_name'=>'姓名或渠道名称', 'sex_text'=>'性别', 'position_name'=>'职务', 'mobile'=>'手机号'];
        $data_list = [];
        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }


    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller)
    {
        return self::delAjaxBase($request, $controller, self::$model_name);

    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
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
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
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
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;

        $work_num = $saveData['work_num'] ?? '';
        $mobile = $saveData['mobile'] ?? '';
        $admin_username = $saveData['admin_username'] ?? '';
        // 新加时
        if( $id <= 0 && (empty($work_num) || empty($mobile) || empty($admin_username))){
            throws('帐号、手机号、用户名不能为空！');
        }

        if(isset($saveData['work_num']) && empty($saveData['work_num'])  ){
            throws('帐号不能为空！');
        }

        if(isset($saveData['mobile']) && empty($saveData['mobile'])  ){
            throws('帐号不能为空！');
        }

        if(isset($saveData['admin_username']) && empty($saveData['admin_username'])  ){
            throws('用户名不能为空！');
        }

        //判断员工号
        if( isset($saveData['work_num']) && self::judgeFieldExist($request, $controller, $id ,"work_num", $saveData['work_num'], $notLog)){
            throws('工帐号已存在！');
        }

        // 查询手机号是否已经有企业使用--账号表里查
        if( isset($saveData['mobile']) && self::judgeFieldExist($request, $controller, $id ,"mobile", $saveData['mobile'], $notLog)){
            throws('手机号已存在！');
        }
        // 用户名
        if( isset($saveData['admin_username']) && self::judgeFieldExist($request, $controller, $id ,"admin_username", $saveData['admin_username'], $notLog)){
            throws('用户名已存在！');
        }


        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            if($modifAddOprate) self::addOprate($request, $controller, $saveData);

        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
                'admin_type' => self::$admin_type,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        $result = self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);

        // 判断版本号是否要+1
        $historySearch = [
            'company_id' => $company_id,
            'staff_id' => $id,
        ];
        self::compareHistoryOrUpdateVersion($request, $controller, 'CompanyStaff' , $id, 'CompanyStaffHistory'
            , 'company_staff_history', $historySearch, ['staff_id'], 1, $company_id);
        return $result;
    }
    /**
     * 按部门分组同事/员工数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function staffGroupDepartment(Request $request, Controller $controller){
        $department_list = [];
        // 获得第一级分类
        $parentData = CompanyDepartment::getChildList($request, $controller, 0, 1 + 0);
        $tem_department_list = $parentData['result']['data_list'] ?? [];
        foreach($tem_department_list as $k=> $v){
            $v['staff'] = [];
            $department_list[$v['id']] = $v;
        }
        $departmentIds = array_column($department_list, 'id');

        // 获得所有同事
        $staffData = self::getList($request, $controller, 1 + 0);
        $staff_list = $staffData['result']['data_list'] ?? [];
        foreach($staff_list as $v){
            $department_id = $v['department_id'] ?? 0;
            if(in_array($department_id ,$departmentIds)){
                $department_list[$department_id]['staff'][] = $v;
            }else{
                $department_list[0]['staff'][] = $v;
            }
        }

        if(isset($department_list[0])){
            $department_list[0]['id'] = 0;
            $department_list[0]['department_name'] = '未指定部门';
        }

        return $department_list;
    }


    /**
     * 修改密码
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @author zouyan(305463219@qq.com)
     */
    public static function modifyPassWord(Request $request, Controller $controller){

        // $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $id = $controller->user_id;
        $company_id = $controller->company_id;
        $old_password = Common::get($request, 'old_password');// 旧密码，如果为空，则不校验
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');

        if (empty($admin_password) || $admin_password != $sure_password){
            return ajaxDataArr(0, null, '密码和确定密码不一致！!');
        }

        $saveData = [
            'admin_password' => $admin_password,
        ];

        // 修改
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        $relations = '';
        CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id,$relations);
        // 如果有旧密码，则验证旧密码是否正确
        if(!empty($old_password)){
            $queryParams = [
                'where' => [
                    ['id',$id],
                    ['admin_password',md5($old_password)],
                    // ['admin_type',self::$admin_type],
                ],
                // 'limit' => 1
            ];
            $relations = '';
            $infoData = CommonBusiness::getInfoByQuery(self::$model_name, $company_id, $queryParams, $relations);
            if(empty($infoData)){
                return ajaxDataArr(0, null, '原始密码不正确！');
            }
        }
        $resultDatas = CommonBusiness::saveByIdApi(self::$model_name, $id, $saveData, $company_id);

        return ajaxDataArr(1, $resultDatas, '');
    }


    /**
     * 判断后机号是否已经存在 true:已存在;false：不存在
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param string $fieldName 需要判断的字段名 mobile  admin_username  work_num
     * @param string $fieldVal 当前要判断的值
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function judgeFieldExist(Request $request, Controller $controller, $id ,$fieldName, $fieldVal, $notLog = 0){
        $company_id = $controller->company_id;
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                [$fieldName,$fieldVal],
                ['admin_type',self::$admin_type],
            ],
            // 'limit' => 1
        ];
        if( is_numeric($id) && $id >0){
            array_push($queryParams['where'],['id', '<>' ,$id]);
        }

        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];
        $resultDatas = CommonBusiness::ajaxGetList(self::$model_name, $pageParams, $company_id, $queryParams ,'', $notLog);
        $dataList = $resultDatas['dataList'] ?? [];
        if(empty($dataList) || count($dataList)<=0){
            return false;
        }
        return true;
    }

    /**
     * 批量导入员工
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function import(Request $request, Controller $controller, $saveData , $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'admin_type' =>  self::$admin_type,
            'save_data' => $saveData,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.staffImport');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        return HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
    }

    /**
     * 批量导入员工--通过文件路径
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $fileName 文件全路径
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function staffImportByFile(Request $request, Controller $controller, $fileName = '', $notLog = 0){
        // $fileName = 'staffs.xlsx';
        $dataStartRow = 1;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
        $headRowNum = 1;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
        $headArr = [
            '县区' => 'department',
            '归属营业厅或片区' => 'group',
            '姓名或渠道名称' => 'channel',
            //'姓名' => 'real_name',
            '帐号' => 'work_num',
            '职务' => 'position',
            '手机号' => 'mobile',
            '性别' => 'sex',
        ];
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        try{
            $dataArr = ImportExport::import($fileName, $dataStartRow, $headRowNum, $headArr);
        } catch ( \Exception $e) {
            throws($e->getMessage());
        }
        return self::import($request, $controller, $dataArr, $notLog);
    }

}
