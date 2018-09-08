<?php

namespace App\Business;
use App\Services\CommonBusiness;

/**
 *
 */
class SiteAdmin
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
        $queryParams = [
            'where' => [
                ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
            ],
            'select' => ['id','company_id','real_name','admin_type'],
            // 'limit' => 1
        ];
        $pageParams = [
            'page' =>1,
            'pagesize' => 1,
            'total' => 1,
        ];
        $resultDatas = CommonBusiness::ajaxGetList('SiteAdmin', $pageParams, 0,$queryParams ,'', 1);

        $dataList = $resultDatas['dataList'] ?? [];
        $userInfo = $dataList[0] ?? [];
        if(empty($dataList) || count($dataList) <= 0 || empty($userInfo)){
            throws('用户名或密码有误！');
        }
        // 保存session
        // 存储数据到session...
        if (!session_id()) session_start(); // 初始化session
        $_SESSION['userInfo'] = $userInfo; //保存某个session信息
        return $userInfo;
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
