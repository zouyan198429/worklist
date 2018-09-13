<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyWorkType;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class WorkTypeController extends AdminController
{
    /**
     * ajax获得获得子类业务数据--一维
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $parent_id = Common::getInt($request, 'parent_id');
        // 获得第一级部门分类一维数组[$k=>$v]
        $workTypeChildKV = CompanyWorkType::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $workTypeChildKV, '');;
    }

}
