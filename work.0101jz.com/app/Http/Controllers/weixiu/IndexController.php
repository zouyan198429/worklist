<?php

namespace App\Http\Controllers\weixiu;

use App\Business\Company;
use App\Business\CompanyNotice;
use App\Business\CompanyStaff;
use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
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
        $statusArr =  CompanyWork::$status_arr;
        // if(isset($statusArr[4]))  unset($statusArr[4]);
        if(isset($statusArr[8]))  unset($statusArr[8]);
        // if(isset($statusArr[-4]))  unset($statusArr[-4]);
        $reDataArr['status'] = $statusArr ;
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,1,2';// 需要播放提示声音的状态，多个逗号,分隔
        // 最新6条公告
        $reDataArr['noticeList'] =  CompanyNotice::getNearList($request, $this, 0, 4, 6, 0, [], '');

        $reDataArr['webLoginUrl'] = url($this->company_id . '/login');
        $reDataArr['mLoginUrl'] = url(config('public.mWebURL') . 'm/' . $this->company_id . '/login');
        return view('weixiu.index', $reDataArr);
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
        return view('weixiu.login', $reDataArr);
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
        return view('weixiu.admin.info', $reDataArr);
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
        return view('weixiu.admin.password', $reDataArr);
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
        return CompanyStaff::login($request, $this, false);
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
        $reDataArr = $this->reDataArr;
        $resDel = CompanyStaff::loginOut($request, $this);
        // return ajaxDataArr(1, $resDel, '');
//        return redirect('weixiu/' . $company_id . '/login');
        return redirect($company_id . '/login');
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
