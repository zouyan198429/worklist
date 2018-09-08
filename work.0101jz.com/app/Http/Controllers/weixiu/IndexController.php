<?php

namespace App\Http\Controllers\weixiu;

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
        $preKey = Common::get($request, 'preKey');// 0 小程序 1后台
        if(!is_numeric($preKey)){
            $preKey = 1;
        }
        // 数据验证 TODO
        SiteAdmin::login($admin_username,$admin_password,$preKey);
        return ajaxDataArr(1, [], '');
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
