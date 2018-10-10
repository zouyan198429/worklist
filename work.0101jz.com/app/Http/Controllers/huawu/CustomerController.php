<?php

namespace App\Http\Controllers\huawu;

use App\Business\CompanyWork;
use App\Business\CompanyStaffCustomer;
use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;


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
        return view('huawu.customer.index', $reDataArr);
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
        return view('huawu.customer.dayCount', $reDataArr);
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
        return  CompanyStaffCustomer::getList($request, $this, 2 + 4, [], ['customerType']);
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

}
