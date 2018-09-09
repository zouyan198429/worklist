<?php

namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyStaff
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
                'id','company_id','real_name','issuper','account_status','work_num',
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
        //$relations = "";
        //if($preKey == 0) {
        $relations = '';//['CompanyInfo.CompanyRank'];
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
                ]
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
     * 获得列表数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller){
        $company_id = $controller->company_id;
        // 获得翻页的三个关键参数
        $pageParams = Common::getPageParams($request);
        // 关键字
        $keyword = Common::get($request, 'keyword');

        list($page, $pagesize, $total) = array_values($pageParams);

        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                //['mobile', $keyword],
            ],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        $relations = ['staffDepartment','staffGroup','staffPosition'];// 关系
        $result = CommonBusiness::ajaxGetList(self::$model_name, $pageParams, $company_id,$queryParams ,$relations);
        if(isset($result['dataList'])){
            $resultDatas = $result['dataList'];
            $pagesize = $result['pageSize'] ?? $pagesize;
            $page = $result['page'] ?? $page;

            if ($total <= 0 ) {
                $total = $result['total'] ?? $total;
            }

            // $totalPage = $result['totalPage'] ?? 0;
        }else{
            $resultDatas = $result;
            //if ($total <= 0 ) {
            $total = count($resultDatas);
            //}
            if($total > 0) $pagesize = $total;
        }
        // 处理图片地址
        // CommonBusiness::resoursceUrl($resultDatas);
        $totalPage = ceil($total/$pagesize);

        // $data_list = [];
        foreach($resultDatas as $k => $v){
            // 部门名称
            $resultDatas[$k]['department_name'] = $v['staff_department']['department_name'] ?? '';
            if(isset($resultDatas[$k]['staff_department'])) unset($resultDatas[$k]['staff_department']);
            // 小组名称
            $resultDatas[$k]['group_name'] = $v['staff_group']['department_name'] ?? '';
            if(isset($resultDatas[$k]['staff_group'])) unset($resultDatas[$k]['staff_group']);
            // 职位
            $resultDatas[$k]['position_name'] = $v['staff_position']['position_name'] ?? '';
            if(isset($resultDatas[$k]['staff_position'])) unset($resultDatas[$k]['staff_position']);

//            $data_list[] = [
//                'id' => $v['id'] ,
//                'company_name' => $v['company_info']['company_name'] ?? '',//  企业名称
//                'resource_url' => $v['site_resources'][0]['resource_url'] ?? '' ,
//                'resource_name' => $v['site_resources'][0]['resource_name'] ?? '' ,
//                'created_at' => $v['created_at'],
//            ];
        }
        $result = [
            'data_list'=>$resultDatas,//array(),//数据二维数组
            'total'=>$total,//总记录数 0:每次都会重新获取总数 ;$total :则>0总数据不会重新获取[除第一页]
            'pageInfo' => showPage($totalPage,$page,$total,12,1),
        ];
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
    public static function delAjax(Request $request, Controller $controller){

        $id = Common::getInt($request, 'id');
        $company_id = $controller->company_id;

        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        $relations = '';
        CommonBusiness::judgePower($id,$judgeData,self::$model_name, $company_id,$relations);

        $queryParams =[// 查询条件参数
            'where' => [
                ['id', $id],
                ['company_id', $company_id]
            ]
        ];
        $resultDatas = CommonBusiness::ajaxDelApi(self::$model_name, $company_id , $queryParams);

        return ajaxDataArr(1, $resultDatas, '');
    }
}
