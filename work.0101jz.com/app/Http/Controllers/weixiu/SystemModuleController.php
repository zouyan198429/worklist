<?php

namespace App\Http\Controllers\weixiu;

use App\Business\SiteSystemModule;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class SystemModuleController extends WorksController
{
    /**
     * ajax获得获得子类栏目数据--一维
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $system_id = Common::getInt($request, 'system_id');
        $parent_id = Common::getInt($request, 'parent_id');
        // 获得第一级部门分类一维数组[$k=>$v]
        $moduleChildKV = SiteSystemModule::getChildListKeyVal($request, $this, $system_id, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $moduleChildKV, '');;
    }

}
