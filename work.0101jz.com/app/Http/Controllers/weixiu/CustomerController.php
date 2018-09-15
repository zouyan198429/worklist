<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
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
        return  CompanyCustomer::getList($request, $this, 2 + 4, ['customerType'] );
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
        return CompanyCustomer::delAjax($request, $this);
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
