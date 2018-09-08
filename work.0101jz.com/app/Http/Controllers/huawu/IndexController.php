<?php

namespace App\Http\Controllers\huawu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class IndexController extends WorksController
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
        return view('huawu.index',[]);
    }

    /**
     * 登陆
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request)
    {
        return view('huawu.login',[]);
    }

    /**
     * 显示
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        return view('huawu.admin.info',[]);
    }

    /**
     * 修改密码
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function password(Request $request)
    {
        return view('huawu.admin.password',[]);
    }

    /**
     * err404
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
        return view('404');
    }


}
