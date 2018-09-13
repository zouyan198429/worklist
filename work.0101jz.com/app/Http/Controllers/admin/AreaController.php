<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyArea;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class AreaController extends AdminController
{
    /**
     * 区域管理
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('admin.area.index', $reDataArr);
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
            'area_parent_id' => -1,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyArea::getInfoData($request, $this, $id);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 获得第一级县区分类一维数组[$k=>$v]
        $reDataArr['area_kv'] = CompanyArea::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('admin.area.add', $reDataArr);
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
        return  CompanyArea::getList($request, $this, 1 + 0);
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
        $area_name = Common::get($request, 'area_name');
        $sort_num = Common::getInt($request, 'sort_num');
        $area_parent_id = Common::getInt($request, 'area_parent_id');

        $saveData = [
            'area_name' => $area_name,
            'sort_num' => $sort_num,
            'area_parent_id' => $area_parent_id,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyArea::replaceById($request, $this, $saveData, $id);
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
        return CompanyArea::delAjax($request, $this);
    }

}
