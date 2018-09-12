<?php
// 系统管理员
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class SiteAdmin extends BaseBusiness
{
    protected static $model_name = 'SiteAdmin';


    /**
     * 登录
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 用户数组
     * @author zouyan(305463219@qq.com)
     */
    public static function login(Request $request, Controller $controller){
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
//        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
//        if(!is_numeric($preKey)){
//            $preKey = 1;
//        }
        // 数据验证 TODO
        $company_id = config('public.company_id');
        $queryParams = [
            'where' => [
                ['company_id',$company_id],
                ['admin_username',$admin_username],
                ['admin_password',md5($admin_password)],
            ],
            'select' => ['id','company_id','admin_username','real_name','admin_type'],
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

        return ajaxDataArr(1, $userInfo, '');
    }

    /**
     * 退出登录
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @author zouyan(305463219@qq.com)
     */
    public static function loginOut(Request $request, Controller $controller){
        if(isset($_SESSION['userInfo'])){
            unset($_SESSION['userInfo']); //保存某个session信息
        }
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
            return ajaxDataArr(0, null, '密码和确定密码不一致！');
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
}
