<?php

namespace App\Http\Controllers\huawu;

use App\Business\Company;
use App\Business\CompanyNotice;
use App\Business\CompanyStaff;
use App\Business\CompanyWork;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use App\Services\CommonBusiness;
use Illuminate\Http\Request;

class IndexController extends WorksController
{

    public function test(Request $request)
    {
        phpinfo();
    }

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
        return view('huawu.testUpfile',$reDataArr);
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
        $reDataArr['countPlayStatus'] = '-8,-4,4';// 需要播放提示声音的状态，多个逗号,分隔
        // 最新6条公告
        $reDataArr['noticeList'] =  CompanyNotice::getNearList($request, $this, 0, 4, 6, 0, [], '');

        $reDataArr['webLoginUrl'] = url($this->company_id . '/login');
        $reDataArr['mLoginUrl'] = url(config('public.mWebURL') . 'm/' . $this->company_id . '/login');
        return view('huawu.index', $reDataArr);
    }

    /**
     * 测试
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function getHistoryIdTest(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得历史记录表id
        $company_id = 1;
        $staff_id = 1;

        $mainObj = "CompanyStaff";
        $primaryVal = $staff_id;
        $historyObj = "CompanyStaffHistory";
        $HistoryTableName = "company_staff_history";
        $historySearch = [
            'company_id' => $company_id,
            'staff_id' => $staff_id,
        ];
        // 获得历史表id
         $resultDatas =  CommonBusiness::getHistoryIdApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, ['staff_id'], $company_id , 0);
        // 版本号是否自加1
        //$ignoreFields = ['lastlogintime'];
        // $resultDatas =  CommonBusiness::compareHistoryOrUpdateVersionApi($mainObj, $primaryVal, $historyObj, $HistoryTableName, $historySearch, $ignoreFields, 1, $company_id , 0);

        pr($resultDatas);
        // return view('huawu.admin.test', $reDataArr);
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
        return view('huawu.login', $reDataArr);
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
        return view('huawu.admin.info', $reDataArr);
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
        return view('huawu.admin.password', $reDataArr);
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
        return CompanyStaff::login($request, $this, true);
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
//        return redirect('huawu/' . $company_id . '/login');
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
