<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class WorkController extends WorksController
{

    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function list(Request $request)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//        $reDataArr['status'] =  CompanyWork::$status_arr;
//        return view('m.work.list', $reDataArr);
//    }

    /**
     * 结单页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function win(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.work.win', $reDataArr);
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
     * ajax获得所有数据 根据状态值
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_doing_list(Request $request){
        $this->InitParams($request);
        $status = Common::get($request, 'status');
        if(empty($status)){
            $status = 1;
        }
        return  CompanyWork::getListByStatus($request, $this, 1 + 4, $status);
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
        $saveData = [];
        $resultDatas = CompanyWork::workSure($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
