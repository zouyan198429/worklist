<?php
// 同事/员工
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
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
}
