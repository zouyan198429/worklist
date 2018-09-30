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

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        $msg_ids = Common::get($request, 'msg_ids');
        $msgIdArr = explode(',', $msg_ids);
        $company_id = $this->company_id;
        $user_id = $this->user_id;
        $queryParams = [
            'where' => [
                ['company_id', '=', $company_id],
                ['accept_staff_id', '=', $user_id],
                ['is_read', '=', 0],
            ],
            'select' => [
                'id','msg_name','mst_content','is_read'
                ,'accept_staff_id','created_at'
            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        $result = CompanySiteMsg::getList($request, $this, 1 + 0, $queryParams);
        $dataList = $result['result']['data_list'];
        $listIdArr = array_column($dataList, 'id');
        foreach($dataList as $k => $v){
            if(in_array($v['id'], $msgIdArr)){
                unset($dataList[$k]);
                continue;
            }
        }
        $dataList = array_values($dataList);
        $delIdArr = array_values(array_diff($msgIdArr, $listIdArr));
        $result['result']['data_list'] = $dataList;
        $result['result']['add_count'] = count($dataList);
        $result['result']['del_ids'] = $delIdArr;
        return $result;
    }
}
