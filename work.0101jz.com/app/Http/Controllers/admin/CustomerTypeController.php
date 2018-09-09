<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyCustomerType;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class CustomerTypeController extends AdminController
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
        return view('admin.customer_type.index', $reDataArr);
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
//        $resultDatas = [
//            'id' => 0,
//            'account_issuper' =>0,
//            'account_status' => 0,
//
//        ];
//        if ($id > 0) { // 获得详情数据
//            $resultDatas = $this->getinfoApi($this->model_name, '', $this->company_id , $id);
//            // 判断权限
//            $judgeData = [
//                'company_id' => $this->company_id,
//            ];
//            $this->judgePowerByObj($request,$resultDatas, $judgeData );
//        }
//        $remarks = $resultDatas['remarks'] ?? '';
//        $resultDatas['remarks'] = replace_enter_char($remarks,2);

        return view('admin.customer_type.add', $reDataArr);
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
        return  CompanyCustomerType::getAllList($request, $this);
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
        return CompanyCustomerType::delAjax($request, $this);
    }
}
