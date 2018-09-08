<?php

namespace App\Business;
use App\Services\CommonBusiness;

/**
 *
 */
class CompanyStaff
{
    /**
     * 登录
     *
     * @param string $admin_username 用户名
     * @param string $admin_password 密码
     * @return  int $notLog 是否需要登陆 0需要1不需要
     * @author zouyan(305463219@qq.com)
     */
    public static function login($admin_username,$admin_password){
        $company_id = config('public.company_id');
        $modelName = 'CompanyStaff';
        // 查询用户名是否有
        $queryParams = [
            'where' => [
                ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
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
            return ajaxDataArr(0, null, '账号已冻结！');
        }
        //开始时间
        $company_vipbegin = $userInfo['company_info']['company_vipbegin'] ?? '';
        $company_vipbegin = judgeDate($company_vipbegin,"Y-m-d");
        if($company_vipbegin !== false) {
            $userInfo['company_info']['company_vipbegin'] = $company_vipbegin;
        }
        // 结束时间
        $company_vipend = $userInfo['company_info']['company_vipend'] ?? '';
        $company_vipend = judgeDate($company_vipend,"Y-m-d");
        if($company_vipend !== false) {
            $userInfo['company_info']['company_vipend'] = $company_vipend;
        }
        //更新上次登陆时间
        $company_id = $userInfo['company_id'] ??  0;
        $saveData = [
            'company_lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        $this->saveByIdApi('Company', $company_id, $saveData, $company_id, 1);

        $saveData = [
            'lastlogintime' => date('Y-m-d H:i:s',time()),
        ];
        $this->saveByIdApi('CompanyAccounts', $account_id, $saveData, $company_id, 1);


        // 获得管理的生产单元
        // 获得当前帐户管理的所有生产单元
        /*
        if($preKey == 0){
            // 'accountProUnits.companyProConfig.siteResources',
            $relations = ['accountProUnits.siteResources'];
        }else{
            $relations = ['accountProUnits'];
        }
        $resultDatas = $this->getinfoApi('CompanyAccounts', $relations, 0 , $account_id,1);

        $account_pro_units = $resultDatas['account_pro_units'] ?? [];
        $proUnits = [];
        foreach($account_pro_units as $v){
            $status = $v['status'] ?? 0;
            if($preKey == 1 && (! in_array($status,[1]))){// 后台
                continue;
            }elseif($preKey == 1 && (! in_array($status,[1]))){// 小程序 [0,1]
                continue;
            }
            $begin_time = $v['begin_time'] ?? '';
            $end_time = $v['end_time'] ?? '';
            //判断开始
            $begin_time_unix = judgeDate($begin_time);
            if($begin_time_unix === false){
                continue;
                // ajaxDataArr(0, null, '开如日期不是有效日期');
            }

            //判断期限结束
            $end_time_unix = judgeDate($end_time);
            if($end_time_unix === false){
                continue;
                // ajaxDataArr(0, null, '结束日期不是有效日期');
            }

            if($end_time_unix < $begin_time_unix){
                continue;
                // ajaxDataArr(0, null, '结束日期不能小于开始日期');
            }
             $time = time();
             if($end_time_unix < $time ){// 过期
                continue;
             }

            $tem = [
                'unit_id' => $v['id'],
                'pro_input_name' => $v['pro_input_name'],
                'status' => $v['status'],
                'status_text' => $v['status_text'],
                'begin_time' => judge_date($v['begin_time'],'Y-m-d'),
                'end_time' => judge_date($v['end_time'],'Y-m-d'),
            ];

            if($preKey == 0) {
                // $resource_url = $v['company_pro_config']['site_resources'][0]['resource_url'] ?? '';
                $resource_url = $v['site_resources'][0]['resource_url'] ?? '';
                $tem['resource_url'] = $resource_url;
                $this->resourceUrl($tem, 1);
            }
            $proUnits[$v['id']] = $tem;
        }
        */
        $proUnits = $this->getUnits($userInfo);
        $userInfo['proUnits'] = $proUnits;
        $userInfo['modifyTime'] = time();
        // 保存session
        // 存储数据到session...
        if (!session_id()) session_start(); // 初始化session
        // $_SESSION['userInfo'] = $userInfo; //保存某个session信息
        $redisKey = $this->setUserInfo($userInfo, $preKey);
        $userInfo['redisKey'] = $redisKey;
    }

    /**
     * 退出登录
     *
     * @author zouyan(305463219@qq.com)
     */
    public static function loginOut(){
        if(isset($_SESSION['userInfo'])){
            unset($_SESSION['userInfo']); //保存某个session信息
        }
    }
}
