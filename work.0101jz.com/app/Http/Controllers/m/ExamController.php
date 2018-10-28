<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyExamStaff;
use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class ExamController extends WorksController
{

    /**
     * 在线考试 --考试列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.exam.index', $reDataArr);
    }

    /**
     * 考试成绩-成绩列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function score(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.exam.score', $reDataArr);
    }

    /**
     * 考试成绩-维修业务知识测评--成绩查询
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function search(Request $request)
    {
       // $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.exam.search', $reDataArr);
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
        return  CompanyExamStaff::getList($request, $this, 2 + 4, [], ['staffExam']);
    }
}
