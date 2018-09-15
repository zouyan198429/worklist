<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyWorkType;
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
        // 第一级业务
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['work_type_kv'] = CompanyWorkType::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('admin.problem.index', $reDataArr);
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
        return  CompanyProblem::getIndexList($request, $this,2 + 4);
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
        return view('manage.problem.return_send');
    }

}
