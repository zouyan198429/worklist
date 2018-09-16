<?php

namespace App\Http\Controllers\m;

use App\Business\CompanySiteMsg;
use App\Business\CompanyStaff;
use App\Business\CompanyWork;
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
        // 获得系统消息
        $msgList = CompanySiteMsg::getListByRead( $request, $this, 1 + 0, 0);
        $reDataArr["msgList"] = $msgList['data_list'] ?? [];
        // 待处理工单
        $workList = CompanyWork::getListByStatus($request, $this, 1 + 0, 2);
        $reDataArr["waitWorkList"] = $workList['data_list'] ?? [];
        // 已完成的 - ajax请求
//        pr($reDataArr);
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
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
        return view('mobile.admin.password', $reDataArr);
    }

    /**
     * ajax修改密码
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_password_save(Request $request)
    {
        $this->InitParams($request);
        return CompanyStaff::modifyPassWord($request, $this);
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
