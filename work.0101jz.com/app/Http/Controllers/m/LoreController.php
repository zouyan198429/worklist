<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyLore;
use App\Business\CompanyLoreType;
use App\Business\CompanyPosition;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class LoreController extends WorksController
{

    /**
     * 学习中心--知识列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得岗位一维数组[$k=>$v]
        $reDataArr['position_kv'] =  CompanyPosition::getListKeyVal($request, $this, 1 + 0);
        //获得知识分类一维数组[$k=>$v]
        $reDataArr['lore_type_kv'] =  CompanyLoreType::getListKeyVal($request, $this, 1 + 0);
        $reDataArr['defaultType'] = 0;// 列表页默认状态

        return view('mobile.lore.index', $reDataArr);
    }

    /**
     * 学习中心-知识详情页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request, $id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;

        $type_id = Common::getInt($request, 'type_id');
        $reDataArr['type_id'] = $type_id;
        // 详情信息
        $resultDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas =CompanyLore::getInfoData($request, $this, $id, ['oprateStaffHistory', 'lorePositions']);
            // 修改点击点
            $id = $resultDatas['id'] ??  0;
            $volume = $resultDatas['volume'] ??  0;
            $saveData = [
                'volume' => $volume + 1,
            ];
            CompanyLore::replaceById($request, $this, $saveData, $id);
            $resultDatas['volume'] = $volume + 1;
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 上一条
        $preList = CompanyLore::getNearList($request, $this, $id, 1, 1, [], '');
        $reDataArr['preList'] = $preList;
        // 下一条
        $nextList = CompanyLore::getNearList($request, $this, $id, 2, 1, [], '');
        $reDataArr['nextList'] = $nextList;
        return view('mobile.lore.info', $reDataArr);
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
        return  CompanyLore::getList($request, $this, 2 + 4, [], ['lorePositions', 'oprateStaffHistory']);
    }

}
