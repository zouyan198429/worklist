<?php

namespace App\Http\Controllers\m;

use App\Business\CompanyStaff;
use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;

class IndexController extends WorksController
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
        return view('mobile.index', $reDataArr);
    }

    /**
     * 首页-back
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function indexBack(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('mobile.index_back', $reDataArr);
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
        return view('mobile.login', $reDataArr);
    }

    /**
     * 显示
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function info(Request $request)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//        return view('mobile.admin.info', $reDataArr);
//    }


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

        return CompanyStaff::login($request, $this);
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
        $resDel = CompanyStaff::loginOut($request, $this);
        // return ajaxDataArr(1, $resDel, '');
        return redirect('m/login');
    }

    /**
     * err404
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
        $reDataArr = $this->reDataArr;
        return view('404', $reDataArr);
    }
}
