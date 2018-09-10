<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyLoreType;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class LoreTypeController extends AdminController
{
    /**
     * 知识分类
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('admin.lore_type.index', $reDataArr);
    }

    /**
     * 添加/修改
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;

        $resultDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyLoreType::getInfoData($request, $this, $id);
        }

        $reDataArr = array_merge($reDataArr, $resultDatas);
        return view('admin.lore_type.add', $reDataArr);
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
        return  CompanyLoreType::getList($request, $this, 1 + 0);
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
        $company_id = $this->company_id;
        $type_name = Common::get($request, 'type_name');
        $sort_num = Common::getInt($request, 'sort_num');
        $saveData = [
            'type_name' => $type_name,
            'sort_num' => $sort_num,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyLoreType::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
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
        return CompanyLoreType::delAjax($request, $this);
    }

}
