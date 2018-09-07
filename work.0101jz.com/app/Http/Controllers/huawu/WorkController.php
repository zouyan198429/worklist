<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class WorkController extends LoginController
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
        return view('huawu.work.index',[]);
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
        return view('huawu.work.list',[]);
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
        return view('huawu.work.history',[]);
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
        return view('huawu.work.hot',[]);
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
        return view('huawu.work.re_list',[]);
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
        return view('huawu.work.add',[]);
    }


}
