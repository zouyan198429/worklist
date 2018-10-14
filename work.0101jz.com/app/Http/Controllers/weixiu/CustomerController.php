<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyWork;
use App\Business\CompanyStaffCustomer;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Business\CompanyCustomer;


class CustomerController extends WorksController
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
        $reDataArr['selTypes'] =  CompanyStaffCustomer::$selTypes;
        $reDataArr['defaultSelType'] = 1;// 列表页默认状态
        return view('weixiu.customer.index', $reDataArr);
    }
    /**
     * 按日统计
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function dayCount(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['count_types'] =  CompanyWork::$countTypeArr;
        $reDataArr['defaultCountType'] = 1;// 列表页默认状态
        return view('weixiu.customer.dayCount', $reDataArr);
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
        return  CompanyStaffCustomer::getList($request, $this, 2 + 4, [], ['customerType'] );
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
        CompanyStaffCustomer::getList($request, $this, 1 + 0);
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
        CompanyStaffCustomer::importTemplate($request, $this);
    }


    /**
     * 客户的删除
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyStaffCustomer::delAjax($request, $this);
    }



    /**
     * 标记功能
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_biaoji(Request $request)
    {
    }

    /**
     * ajax标记/取消标记
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_is_tab(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $is_tab = Common::getInt($request, 'is_tab');
        Tool::dataValid([["input"=>$is_tab,"require"=>"true","validator"=>"custom","regexp"=>"/^([01])$/","message"=>'标签值必须为01']]);
        $saveData = [
            'is_tab' => (1- $is_tab),
        ];
        $resultDatas = CompanyStaffCustomer::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }
}
