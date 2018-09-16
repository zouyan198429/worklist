<?php

namespace App\Http\Controllers\m;


use App\Business\CompanySiteMsg;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;


class SiteMsgController extends WorksController
{


    /**
     * ajax确认
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_sure(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $company_id = $this->company_id;
        $saveData = [
            'is_read' => 1,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanySiteMsg::sureById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
