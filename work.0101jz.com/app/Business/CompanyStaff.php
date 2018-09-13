<?php
// 同事/员工
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyStaff extends BaseBusiness
{
    protected static $model_name = 'CompanyStaff';
    /**
     * 登录
     * @author zouyan(305463219@qq.com)
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 用户信息[一维数组]
     */
    public static function login(Request $request, Controller $controller){
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
        if(!is_numeric($preKey)){
            $preKey = 1;
        }
        // 数据验证 TODO

        $company_id = config('public.company_id');

        $modelName = self::$model_name;
        // 查询用户名是否有
        $queryParams = [
            'where' => [
                ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
            ],
            'select' => [
                'id','company_id','admin_username','real_name','issuper','account_status','work_num',
                'department_id','group_id','position_id','sex'
                ,'tel','mobile','qq_number','lastlogintime'
            ],
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
        $relations = ['staffDepartment', 'staffGroup', 'staffPosition' ];// , 'staffRoles'
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
                    ['company_id',$company_id],
                    ['mobile', $admin_username],
                    ['admin_password', md5($admin_password)],
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

        $account_id = $userInfo['id'] ?? 0;
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
            $department_list[$k]['real_name'] = $v['real_name'] . '[' . $v['work_num'] . ']';
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
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4 , $notLog = 0){
        $company_id = $controller->company_id;
          // 获得数据
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        $department_id = Common::getInt($request, 'department_id');
        $keyword = Common::get($request, 'keyword');

        if($department_id > 0){
            array_push($queryParams['where'],['department_id', $department_id]);
        }
        if(!empty($keyword)){
            array_push($queryParams['where'],['real_name', 'like' , '%' . $keyword . '%']);
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
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id){
        $company_id = $controller->company_id;
        $relations = '';
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
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $notLog = 0){
        $company_id = $controller->company_id;

        $work_num = $saveData['work_num'] ?? '';
        $mobile = $saveData['mobile'] ?? '';
        $admin_username = $saveData['admin_username'] ?? '';
        // 新加时
        if( $id <= 0 && (empty($work_num) || empty($mobile) || empty($admin_username))){
            throws('工号、手机号、用户名不能为空！');
        }

        if(isset($saveData['work_num']) && empty($saveData['work_num'])  ){
            throws('工号不能为空！');
        }

        if(isset($saveData['mobile']) && empty($saveData['mobile'])  ){
            throws('工号不能为空！');
        }

        if(isset($saveData['admin_username']) && empty($saveData['admin_username'])  ){
            throws('用户名不能为空！');
        }

        //判断员工号
        if( isset($saveData['work_num']) && self::judgeFieldExist($request, $controller, $id ,"work_num", $saveData['work_num'], $notLog)){
            throws('工号已存在！');
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

        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
        }
        // 加入操作人员信息
        self::addOprate($request, $controller, $saveData);
        // 新加或修改
        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
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

}
