<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class WorkController extends WorksController
{
    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.index', $reDataArr);
    }

    /**
     * 列表
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.list', $reDataArr);
    }

    /**
     * history
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function history(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.history', $reDataArr);
    }

    /**
     * hot
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function hot(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.hot', $reDataArr);
    }

    /**
     * re_list
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function re_list(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.re_list', $reDataArr);
    }


    /**
     * 添加
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('huawu.work.add', $reDataArr);
    }


}
