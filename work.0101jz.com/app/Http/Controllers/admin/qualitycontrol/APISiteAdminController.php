<?php

namespace App\Http\Controllers\admin\qualitycontrol;

use App\Business\SiteAdmin;
use App\Http\Controllers\admin\SiteAdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class APISiteAdminController extends SiteAdminController
{
    public $source = 3;// 来源-1网站页面，2ajax；3小程序

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitAPIParams($request);
        return  SiteAdmin::getList($request, $this, 1 + 0);
    }


    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitAPIParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $admin_type = Common::getInt($request, 'admin_type');
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');
        $real_name = Common::get($request, 'real_name');

        $saveData = [
            'admin_type' => $admin_type,
            'admin_username' => $admin_username,
            'real_name' => $real_name,
        ];
        if($admin_password != '' || $sure_password != ''){
            if ($admin_password != $sure_password){
                return ajaxDataArr(0, null, '密码和确定密码不一致！');
            }
            $saveData['admin_password'] = $admin_password;
        }

//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = SiteAdmin::replaceById($request, $this, $saveData, $id);
//        return ajaxDataArr(1, $resultDatas, '');
        return ajaxDataArr(1, $id, '');
    }

    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitAPIParams($request);
        return SiteAdmin::delAjax($request, $this);
    }
}
