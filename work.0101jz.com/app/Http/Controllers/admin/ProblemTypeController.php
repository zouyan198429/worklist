<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyProblemType;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class ProblemTypeController extends AdminController
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
        return view('admin.problem_type.index', $reDataArr);
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
            'type_parent_id' => -1,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyProblemType::getInfoData($request, $this, $id);
        }

        $reDataArr = array_merge($reDataArr, $resultDatas);

        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['problem_type_kv'] = CompanyProblemType::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('admin.problem_type.add', $reDataArr);
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
        return  CompanyProblemType::getList($request, $this, 1 + 0);
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
        CompanyProblemType::getList($request, $this, 1 + 0);
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
        CompanyProblemType::importTemplate($request, $this);
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
        $type_name = Common::get($request, 'type_name');
        $sort_num = Common::getInt($request, 'sort_num');
        $type_parent_id = Common::getInt($request, 'type_parent_id');

        $saveData = [
            'type_name' => $type_name,
            'sort_num' => $sort_num,
            'type_parent_id' => $type_parent_id,
        ];
//        if($id <= 0) {// 新加;要加入的特别字段
//            $addNewData = [
//                // 'account_password' => $account_password,
//            ];
//            $saveData = array_merge($saveData, $addNewData);
//        }

        $resultDatas = CompanyProblemType::replaceById($request, $this, $saveData, $id);
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
        return CompanyProblemType::delAjax($request, $this);
    }

    /**
     * ajax获得获得子类业务数据--一维
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function ajax_get_child(Request $request){
        $this->InitParams($request);
        $parent_id = Common::getInt($request, 'parent_id');
        // 获得第一级部门分类一维数组[$k=>$v]
        $workTypeChildKV = CompanyProblemType::getChildListKeyVal($request, $this, $parent_id, 1 + 0);
        return  ajaxDataArr(1, $workTypeChildKV, '');;
    }

}
