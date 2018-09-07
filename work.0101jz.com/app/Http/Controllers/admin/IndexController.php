<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;

class IndexController extends LoginController
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
        return view('admin.index',[]);
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
        return view('admin.login',[]);
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
        return view('admin.admin.password',[]);
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
        return view('admin.admin.info',[]);
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
