<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;

class ProblemController extends AdminController
{
    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.problem.index', $reDataArr);
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyProblem::getList($request, $this);
    }



    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyProblem::delAjax($request, $this);
    }



    /**
     * 反馈问题的回复
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function return_send(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.problem.return_send', $reDataArr);
    }
}
