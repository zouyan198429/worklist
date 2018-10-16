<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyNotice;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class NoticeController extends WorksController
{
    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('weixiu.notice.index', $reDataArr);
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request, $id = 0)
    {
        $this->InitParams($request);

        $reDataArr = $this->reDataArr;

        // 详情信息
        $resultDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas =CompanyNotice::getInfoData($request, $this, $id, ['oprateStaffHistory']);
            // 修改点击点
            $id = $resultDatas['id'] ??  0;
            $volume = $resultDatas['volume'] ??  0;
            $saveData = [
                'volume' => $volume + 1,
            ];
            CompanyNotice::replaceById($request, $this, $saveData, $id, false);
            $resultDatas['volume'] = $volume + 1;
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 上一条
        $preList = CompanyNotice::getNearList($request, $this, $id, 1, 1, 0, [], '');
        $reDataArr['preList'] = $preList;
        // 下一条
        $nextList = CompanyNotice::getNearList($request, $this, $id, 2, 1, 0, [], '');
        $reDataArr['nextList'] = $nextList;
        return view('weixiu.notice.info', $reDataArr);
    }

    /**
     * ajax获得客户列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyNotice::getList($request, $this, 2 + 4, []);// , ['oprateStaffHistory']
    }


}
