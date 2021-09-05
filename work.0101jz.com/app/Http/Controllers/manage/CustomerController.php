<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\AdminController;
use App\Services\Common;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Business\CompanyCustomer;

class CustomerController extends AdminController
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
        $reDataArr['selTypes'] =  CompanyCustomer::$selTypes;
        $reDataArr['defaultSelType'] = 1;// 列表页默认状态
        return view('manage.customer.index', $reDataArr);
    }

    /**
     * ajax获得客户列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyCustomer::getList($request, $this, 2 + 4, [], ['customerType']);
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
        CompanyCustomer::getList($request, $this, 1 + 0);
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
        CompanyCustomer::importTemplate($request, $this);
    }


    /**
     * 客户的删除
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyCustomer::delAjax($request, $this);
    }



    /**
     * 标记功能
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_biaoji(Request $request)
    {
    }

    /**
     * ajax标记/取消标记
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_is_tab(Request $request)
    {
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $is_tab = Common::getInt($request, 'is_tab');
        Tool::dataValid([["input"=>$is_tab,"require"=>"true","validator"=>"custom","regexp"=>"/^([01])$/","message"=>'标签值必须为01']]);
        $saveData = [
            'is_tab' => (1- $is_tab),
        ];
        $resultDatas = CompanyCustomer::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }
}
