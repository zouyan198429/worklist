<?php

namespace App\Http\Controllers\m;

use App\Business\Company;
use App\Business\CompanyNotice;
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
//        // 初始化数据
//        $arrList = CompanyWork::mobileInitData($request, $this);
//        $reDataArr = array_merge($reDataArr, $arrList);
        $statusArr =  CompanyWork::$status_arr;
        if(isset($statusArr[4]))  unset($statusArr[4]);
        if(isset($statusArr[8]))  unset($statusArr[8]);
        if(isset($statusArr[-4]))  unset($statusArr[-4]);
        $reDataArr['status'] = $statusArr ;
        $reDataArr['defaultStatus'] = 1;// 列表页默认状态
        $reDataArr['countStatus'] = [-8,-4,0,1,2,4];// 列表页需要统计的状态数组
        $reDataArr['countPlayStatus'] = '-8,-4,1';// 需要播放提示声音的状态，多个逗号,分隔 状态2的声音不需要。
        $reDataArr['msgList'] = [];
        // 最新6条公告
        $reDataArr['noticeList'] =  CompanyNotice::getNearList($request, $this, 0, 4, 2, 0, [], '');

        $reDataArr['webLoginUrl'] = url($this->company_id . '/login');
        $reDataArr['mLoginUrl'] = url(config('public.mWebURL') . 'm/' . $this->company_id . '/login');
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
    public function login(Request $request, $company_id = 1)
    {
        $this->reDataArr['webType'] = config('public.webType');// 网站类型 1  外网 2内网
        $reDataArr = $this->reDataArr;
        try{
            $company_info = Company::loginGetInfo($request, $this, $company_id, 1);
            if(empty($company_info)) throws('企业记录【' . $company_id . '】不存在！');
        } catch ( \Exception $e) {
            $reDataArr['errStr'] = $e->getMessage();
            return view('error', $reDataArr);
        }
        $reDataArr['company_info'] = $company_info;
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
        $resDel = CompanyStaff::loginOut($request, $this);
        // return ajaxDataArr(1, $resDel, '');
        return redirect('m/' . $company_id . '/login');
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
