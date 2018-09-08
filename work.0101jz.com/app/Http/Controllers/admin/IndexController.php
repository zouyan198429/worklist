<?php

namespace App\Http\Controllers\admin;

use App\Business\SiteAdmin;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class IndexController extends AdminController
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
        return view('admin.index', $reDataArr);
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
        $reDataArr = $this->reDataArr;
        return view('admin.login', $reDataArr);
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
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('admin.admin.password', $reDataArr);
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
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('admin.admin.info', $reDataArr);
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
        $reDataArr = $this->reDataArr;
        return view('404', $reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_login(Request $request)
    {
        // $this->InitParams($request);
        // $company_id = $this->company_id;
        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
//        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
//        if(!is_numeric($preKey)){
//            $preKey = 1;
//        }
        // 数据验证 TODO
        $userInfo = SiteAdmin::login($admin_username,$admin_password);
        return ajaxDataArr(1, $userInfo, '');
    }

    /**
     * 注销
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function logout(Request $request)
    {
        // $this->InitParams($request);
        SiteAdmin::loginOut();
        $reDataArr = $this->reDataArr;
        return redirect('admin/login', $reDataArr);
    }
}
