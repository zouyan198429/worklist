<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyWork;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class WorkController extends AdminController
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
        $reDataArr['status'] =  CompanyWork::$status_arr;
        return view('admin.work.index', $reDataArr);
    }

    /**
     * 详情
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request,$id = 0)
    {
        $this->InitParams($request);

        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            'department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $relations = ['workHistoryStaffCreate', 'workHistoryStaffSend'];
            $resultDatas = CompanyWork::getInfoData($request, $this, $id, $relations);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyWork::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('admin.work.info', $reDataArr);
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
        return  CompanyWork::getList($request, $this, 2 + 4);
    }

    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_del(Request $request)
//    {
//        $this->InitParams($request);
//        return CompanyWork::delAjax($request, $this);
//    }


}
