<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyArea;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class AreaController extends WorksController
{

    /**
     * ajax获得获得子类街道数据--一维
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $parent_id = Common::getInt($request, 'parent_id');
        // 获得第一级部门分类一维数组[$k=>$v]
        $areaChildKV = CompanyArea::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $areaChildKV, '');;
    }

}
