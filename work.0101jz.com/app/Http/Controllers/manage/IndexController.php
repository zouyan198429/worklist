<?php

namespace App\Http\Controllers\manage;

use App\Business\Company;
use App\Business\SiteAdmin;
use App\Business\CompanyWork;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class IndexController extends AdminController
{

    /**
     * 测试图片上传
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function testUpfile(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.testUpfile',$reDataArr);
    }

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
        $statusArr =  CompanyWork::$status_arr;
        // if(isset($statusArr[4]))  unset($statusArr[4]);
        if(isset($statusArr[8]))  unset($statusArr[8]);
        // if(isset($statusArr[-4]))  unset($statusArr[-4]);
        $reDataArr['status'] = $statusArr ;
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4';// 需要播放提示声音的状态，多个逗号,分隔
        $reDataArr['webLoginUrl'] = url($this->company_id . '/login');
        $reDataArr['mLoginUrl'] = url(config('public.mWebURL') . 'm/' . $this->company_id . '/login');
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
    public function login(Request $request, $company_id = 1)
    {
        $reDataArr = $this->reDataArr;
        try{
            $company_info = Company::loginGetInfo($request, $this, $company_id, 1);
            if(empty($company_info)) throws('企业记录【' . $company_id . '】不存在！');
        } catch ( \Exception $e) {
            $reDataArr['errStr'] = $e->getMessage();
            return view('error', $reDataArr);
        }
        $reDataArr['company_info'] = $company_info;
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
        $user_info = $this->user_info;
        $reDataArr = array_merge($reDataArr, $user_info);
        return view('manage.admin.password',$reDataArr);
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
        return SiteAdmin::modifyPassWord($request, $this);
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
        return SiteAdmin::login($request, $this, false);
    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function logout(Request $request, $company_id = 1)
    {
        // $this->InitParams($request);
        SiteAdmin::loginOut($request, $this);
//        return redirect('manage/' . $company_id . '/login');
        return redirect($company_id . '/login');
    }

}
