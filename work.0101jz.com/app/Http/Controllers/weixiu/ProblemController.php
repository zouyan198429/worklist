<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;

class ProblemController extends WorksController
{
    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $arr = CompanyProblem::getWorkTypeArr($this, 'company_work_type');
        return view('weixiu.problem.add', ['arr'=>$arr]);
    }

    /**
     * 获取二级分类
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_gettype(Request $request)
    {
        $parent_id = $_POST['id'];
        $this->InitParams($request);
        return CompanyProblem::getWorkTypeArr($this, 'company_work_type', $parent_id);
    }


}
