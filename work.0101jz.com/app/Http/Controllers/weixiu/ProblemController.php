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
        $typeArr = CompanyProblem::getWorkTypeArr($this);
        $arrArr = CompanyProblem::getAreaArr($this);
        $new_arr = array_merge($reDataArr,array('typearr'=>$typeArr),array('addarr'=>$arrArr));
        return view('weixiu.problem.add', ['arr'=>$new_arr]);
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
        return CompanyProblem::getWorkTypeArr($this, $parent_id);
    }

    /**
     * 获取二级地址
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_getarea(Request $request)
    {
        $parent_id = $_POST['id'];
        $this->InitParams($request);
        return CompanyProblem::getAreaArr($this, $parent_id);
    }
}
