<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyDepartment;
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
        $reDataArr['department_kv'] = CompanyDepartment::getChildListKeyVal($request, $this, 0, 1 + 0);

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

        $saveData = [
            'company_name' => $company_name,
            'open_status' => $open_status,
            'module_no' => $sel_module_nos,
            'send_work_department_id' => $send_work_department_id,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = Company::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
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
