<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyProblemType;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class ProblemTypeController extends WorksController
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
        $workTypeChildKV = CompanyProblemType::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $workTypeChildKV, '');;
    }

}
