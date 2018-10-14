<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanyProblemType;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;

class ProblemController extends AdminController
{
    /**
     * 列表
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 第一级业务
        // 获得第一级部门分类一维数组[$k=>$v]
        $reDataArr['problem_type_kv'] = CompanyProblemType::getChildListKeyVal($request, $this, 0, 1 + 0);
        return view('admin.problem.index', $reDataArr);
    }

    /**
     * ajax获得列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanyProblem::getIndexList($request, $this,2 + 4);
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
        CompanyProblem::getList($request, $this, 1 + 0);
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
        CompanyProblem::importTemplate($request, $this);
    }


    /**
     * 子帐号管理-删除
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_del(Request $request)
    {
        $this->InitParams($request);
        return CompanyProblem::delAjax($request, $this);
    }



    /**
     * 反馈问题的回复
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function reply(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
            //  'call_number' => $this->user_info['mobile'] ?? '',
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyProblem::getInfoData($request, $this, $id, '');
            $reply_content = $resultDatas['reply_content'] ?? '';
            $resultDatas['reply_content'] = replace_enter_char($reply_content,2);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        return view('admin.problem.reply',$reDataArr);
    }

    /**
     * ajax保存数据
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function reply_ajax_save(Request $request)
    {
        $this->InitParams($request);
        $resultDatas = CompanyProblem::replayAjaxSave( $request, $this);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
