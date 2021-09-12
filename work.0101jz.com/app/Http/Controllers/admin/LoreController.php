<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyLore;
use App\Business\CompanyLoreType;
use App\Business\CompanyPosition;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class LoreController extends AdminController
{
    /**
     * 知识
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

        return view('admin.lore.index', $reDataArr);
    }

    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request, $id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 推荐级别
        $reDataArr['level_num_kv'] = CompanyLore::$level_num_arr;

        // 获得详情信息
        $resultDatas = [
            'id'=>$id,
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $relations = ['lorePositions'];
            $resultDatas = CompanyLore::getInfoData($request, $this, $id, $relations);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['operate'] = $operate;

        //获得知识分类一维数组[$k=>$v]
        $reDataArr['lore_type_kv'] =  CompanyLoreType::getListKeyVal($request, $this, 1 + 0);

        // 获得岗位一维数组[$k=>$v]
        $reDataArr['position_kv'] =  CompanyPosition::getListKeyVal($request, $this, 1 + 0);
        return view('admin.lore.add', $reDataArr);
    }

    /**
     * 显示
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
        $infoDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $infoDatas =CompanyLore::getInfoData($request, $this, $id, ['oprateStaffHistory', 'lorePositions']);
            // 修改点击点
            $id = $infoDatas['id'] ??  0;
            $volume = $infoDatas['volume'] ??  0;
            $saveData = [
                'volume' => $volume + 1,
            ];
            CompanyLore::replaceById($request, $this, $saveData, $id, false);
            $infoDatas['volume'] = $volume + 1;
        }
        // $reDataArr = array_merge($reDataArr, $infoDatas);
        $reDataArr['info'] = $infoDatas;

        // 上一条
        $preList = CompanyLore::getNearList($request, $this, $id, 1, 1, 0, [], '');
        $reDataArr['preList'] = $preList;
        // 下一条
        $nextList = CompanyLore::getNearList($request, $this, $id, 2, 1, 0, [], '');
        $reDataArr['nextList'] = $nextList;
        return view('admin.lore.info', $reDataArr);
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

    /**
     * 导出
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function export(Request $request){
        $this->InitParams($request);
        CompanyLore::getList($request, $this, 1 + 0);
    }

    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function import_template(Request $request){
        $this->InitParams($request);
        CompanyLore::importTemplate($request, $this);
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
        $this->InitParams($request);
        return CompanyLore::delAjax($request, $this);
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
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $type_id = Common::getInt($request, 'type_id');
        $title = Common::get($request, 'title');
        $content = Common::get($request, 'content');
        $level_num = Common::getInt($request, 'level_num');
        $positionIds = Common::get($request, 'position_ids');
        if(!empty($positionIds)){
            if(!is_array($positionIds) && is_string($positionIds)){// 转为数组
                $positionIds = explode(',',$positionIds);
            }
        }else{
            $positionIds = [];
        }

        $content = stripslashes($content);
        $position_ids = implode(',', $positionIds);
        if(!empty($position_ids)) $position_ids = ',' . $position_ids . ',';
        $saveData = [
            'type_id' => $type_id,
            'title' => $title,
            'content' => $content,
            'level_num' => $level_num,
            'position_ids' => $position_ids,
            'positionIds' => $positionIds,// 此下标为职位关系
        ];
        // throws(\GuzzleHttp\json_encode($saveData));
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyLore::replaceById($request, $this, $saveData, $id);

        return ajaxDataArr(1, $resultDatas, '');
    }


}
