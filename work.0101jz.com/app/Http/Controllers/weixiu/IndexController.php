<?php

namespace App\Http\Controllers\weixiu;

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
        return view('weixiu.index',[]);
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
        return view('weixiu.login',[]);
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
        return view('weixiu.admin.info',[]);
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
        return view('weixiu.admin.password',[]);
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
