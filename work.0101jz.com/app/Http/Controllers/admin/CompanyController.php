<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
use App\Business\SiteAdmin;
use App\Services\Common;
use App\Business\Company;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class CompanyController extends AdminController
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

        $reDataArr['module_no_kv'] = Company::MODULE_NO_ARR;
        // 开通状态1开通；2关闭；4作废【过时关闭】；
        $reDataArr['openStatus'] =  Company::OPEN_STATUS_ARR;
        // $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0, 0, $id);
        // 公司状态;1新注册2试用客户4VIP 8VIP 将过期  16过期会员
        $reDataArr['companyStatus'] =  Company::$companyStatusArr;
        return view('admin.company.index', $reDataArr);
    }

    /**
     * 添加/修改
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;

        $resultDatas = [
            'id'=>$id,
            'module_no' => 0,
            'open_status' => 0,
            'send_work_department_id' => 0,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = Company::getInfoData($request, $this, $id);
        }

        $reDataArr = array_merge($reDataArr, $resultDatas);
        $reDataArr['module_no_kv'] = Company::MODULE_NO_ARR;
        // 开通状态1开通；2关闭；4作废【过时关闭】；
        $reDataArr['openStatus'] =  Company::OPEN_STATUS_ARR;
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0, 0, $id);
        // 公司状态;1新注册2试用客户4VIP 8VIP 将过期  16过期会员
        $reDataArr['companyStatus'] =  Company::$companyStatusArr;
        return view('admin.company.add', $reDataArr);
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  Company::getList($request, $this, 1 + 0);
    }

    /**
     * 导出
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function export(Request $request){
        $this->InitParams($request);
        Company::getList($request, $this, 1 + 0);
    }

    /**
     * 导入模版
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function import_template(Request $request){
        $this->InitParams($request);
        Company::importTemplate($request, $this);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_save(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $company_id = $this->company_id;
        $company_name = Common::get($request, 'company_name');
        $open_status = Common::getInt($request, 'open_status');
        $module_nos = Common::get($request, 'module_nos');
        $send_work_department_id = Common::getInt($request, 'send_work_department_id');
        // 如果是字符，则转为数组
        Tool::valToArrVal($module_nos);
        $sel_module_nos = Tool::bitJoinVal($module_nos);// 将位数组，合并为一个数值
        $company_linkman = Common::get($request, 'company_linkman');
        $sex = Common::getInt($request, 'sex');
        $company_mobile = Common::get($request, 'company_mobile');
        $company_status = Common::getInt($request, 'company_status');

        $admin_username = Common::get($request, 'admin_username');
        $admin_password = Common::get($request, 'admin_password');
        $sure_password = Common::get($request, 'sure_password');

        $company_vipend = Common::get($request, 'company_vipend');// 到期时间
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

        $resultDatas = Company::replaceById($request, $this, $saveData, $id);
        if(!empty($saveStaffData)){

            $staff_id = 0;
            SiteAdmin::replaceById($request, $this, $saveStaffData, $staff_id, false,0, $id);
        }
        $res = [
              'id' => $id,
              'webLoginUrl' => url($id . '/login'),
              'mLoginUrl' => url(config('public.mWebURL') . 'm/' . $id . '/login'),
        ];
        return ajaxDataArr(1, $res, '');
    }

    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        $id = Common::get($request, 'id');
        if($id == 1){
            throws('此记录不可以删除!');
        }
        return Company::delAjax($request, $this);
    }
}
