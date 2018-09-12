<?php

namespace App\Http\Controllers\manage;

use App\Business\SiteAdmin;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class IndexController extends AdminController
{
    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.index', $reDataArr);
    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index_hot(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.index_hot',$reDataArr);
    }

    /**
     * 登陆
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function login(Request $request)
    {
        $reDataArr = $this->reDataArr;
        return view('manage.login',$reDataArr);
    }

    /**
     * 修改密码
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function password(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.admin.password',$reDataArr);
    }

    /**
     * 显示
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function info(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
        return view('manage.admin.info',$reDataArr);
    }


    /**
     * ajax保存数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_login(Request $request)
    {
        // $this->InitParams($request);
        // $company_id = $this->company_id;
        return SiteAdmin::login($request, $this);
    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function logout(Request $request)
    {
        // $this->InitParams($request);
        SiteAdmin::loginOut($request, $this);
        return redirect('manage/login');
    }

}
