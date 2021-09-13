<?php

namespace App\Http\Controllers;

use App\Business\Company;
use App\Business\CompanyDepartment;
use App\Business\SiteAdmin;
use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends WorksController
{
    public static $systemArr = [
        '1' => '超级管理平台'
        , '2' => '主管平台'
        , '3' => '派单平台'
        , '4' => '用户平台',//  '员工平台',// '区县平台'
    ];

    /**
     * 注册
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        return view('test');
    }

    public function test2(Request $request)
    {
        return view('test2');
    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function index(Request $request)
//    {
//        $this->InitParams($request);
//        $url = config('public.apiUrl') . config('apiUrl.common.index');
//        // 生成带参数的测试get请求
//        // $requestTesUrl = splicQuestAPI($url , $requestData);
//        $requestData['company_id'] = $this->company_id ;
//        $resData = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
//        $resData['userInfo'] = $this->user_info;
//        return view('index',$resData);
//    }

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $urlArr = $request->server();
        $httpHost = $urlArr['HTTP_HOST'] ?? '';
        return redirect(CommonBusiness::urlRedirect($httpHost, 1));
    }
    /**
     * 文件上传
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function upload(Request $request)
//    {
//        $errArr = [
//            'result' => 'failed',// 文件上传失败
//            'message' => '文件内容包含违规内容',//用于在界面上提示用户的消息
//        ];
//        // return $errArr;
//
//        $requestLog = [
//            'file'       =>$request->file('file'),
//            'files'       => $request->file(),
//            'posts'  => $request->post(),
//            'input'      => $request->input(),
//        ];
//        Log::info('上传文件日志',$requestLog);
//
//
//        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
//            $photo = $request->file('photo');
//            $extension = $photo->extension();
//            //$store_result = $photo->store('photo');
//            $save_path = 'resource/photo/'. date('Y/m/d/',time());
//            $save_name = Tool::createUniqueNumber(20) .'.' . $extension;
//            $store_result = $photo->storeAs($save_path, $save_name);
//            $output = [
//                'extension' => $extension,
//                'store_result' => $store_result
//            ];
//            $sucArr = [
//                'result' => 'ok',// 文件上传成功
//                'id' => 10001, // 文件在服务器上的唯一标识
//                'url'=> url($save_path . $save_name),//'http://example.com/file-10001.jpg',// 文件的下载地址
//                'output'  => $output,
//            ];
//            return $sucArr;
//            Log::info('上传文件日志',$output);
//            print_r($output);exit();
//        }
//        $errArr = [
//            'result' => 'failed',// 文件上传失败
//            'message' => '文件内容包含违规内容',//用于在界面上提示用户的消息
//        ];
//        return $errArr;
//        $sucArr = [
//            'result' => 'ok',// 文件上传成功
//            'id' => 10001, // 文件在服务器上的唯一标识
//            'url'=> 'http://example.com/file-10001.jpg',// 文件的下载地址
//        ];
//        return $sucArr;
//    }


    /**
     * 注册
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function reg(Request $request)
    {
        $reDataArr = $this->reDataArr;
        $reDataArr['module_no_kv'] = Company::MODULE_NO_ARR;
        return view('reg', $reDataArr);
    }

    /**
     * ajax保存数据-- 企业注册
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_reg(Request $request)
    {
        // $this->InitParams($request);
        $id = 0;// Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $company_id = $this->company_id;
        $company_name = Common::get($request, 'company_name');
        $open_status = Company::OPEN_STATUS_OPEN;// Common::getInt($request, 'open_status');
        $module_nos = Common::get($request, 'module_nos');
        $send_work_department_id = 0;// Common::getInt($request, 'send_work_department_id');
        // 如果是字符，则转为数组
        Tool::valToArrVal($module_nos);
        $sel_module_nos = Tool::bitJoinVal($module_nos);// 将位数组，合并为一个数值
        $company_linkman = Common::get($request, 'company_linkman');
        $sex = Common::getInt($request, 'sex');
        $company_mobile = Common::get($request, 'company_mobile');
        $company_status = 2;// Common::getInt($request, 'company_status');

        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');
        $now_time = date('Y-m-d H:i:s');

        $company_vipend = Tool::addMinusDate($now_time, ['+15 day'], 'Y-m-d H:i:s', 1, '时间');// Common::get($request, 'company_vipend');// 到期时间 -- 15天
        Tool::judgeBeginEndDate($company_vipend, '', 1);

        $saveData = [
            'company_name' => $company_name,
            'open_status' => $open_status,
            'module_no' => $sel_module_nos,
            'send_work_department_id' => $send_work_department_id,
            'company_linkman' => $company_linkman,
            'sex' => $sex,
            'company_mobile' => $company_mobile,
            'company_status' => $company_status,
            'company_vipend' => $company_vipend,
        ];
        $saveStaffData = [];
        if($id <= 0) {// 新加;要加入的特别字段
            if(empty($admin_username) || empty($admin_password)){
                throws('用户名或密码不能为空！');
            }
            $saveStaffData = [
                'admin_type' => 2,
                'admin_username' => $admin_username,
                'real_name' => $company_linkman,
            ];
            if($admin_password != '' || $sure_password != ''){
                if ($admin_password != $sure_password){
                    return ajaxDataArr(0, null, '密码和确定密码不一致！');
                }
                $saveStaffData['admin_password'] = $admin_password;
            }

//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
        }

        $resultDatas = Company::replaceById($request, $this, $saveData, $id, false, 1);
        if(!empty($saveStaffData)){
            $staff_id = 0;
            SiteAdmin::replaceById($request, $this, $saveStaffData, $staff_id, false,1, $id);

        }
        $res = [
            'id' => $id,
            'webLoginUrl' => url($id . '/login'),
            'mLoginUrl' => url(config('public.mWebURL') . 'm/' . $id . '/login'),
        ];
        return ajaxDataArr(1, $res, '');
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

        $reDataArr['system_kv'] =  static::$systemArr;
        $reDataArr['defaultSystem'] = -1;// 默认
        try{
            $company_info = Company::loginGetInfo($request, $this, $company_id, 1);
            if(empty($company_info)) throws('企业记录【' . $company_id . '】不存在！');
        } catch ( \Exception $e) {
            $reDataArr['errStr'] = $e->getMessage();
            return view('error', $reDataArr);
        }
        $reDataArr['company_info'] = $company_info;
        return view('login', $reDataArr);
    }

    /**
     * 注销
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function logout(Request $request, $company_id = 1)
//    {
//        $this->InitParams($request);
//        // session_start(); // 初始化session
//        //$userInfo = $_SESSION['userInfo'] ?? [];
//        /*
//        if(isset($_SESSION['userInfo'])){
//            unset($_SESSION['userInfo']); //保存某个session信息
//        }
//        */
//        $this->delUserInfo();
//        return redirect('/login');
//    }

    /**
     * err404
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function err404(Request $request)
    {
        return view('404');
    }

}
