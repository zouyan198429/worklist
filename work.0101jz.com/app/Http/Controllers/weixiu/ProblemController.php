<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyProblemType;
use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;

class ProblemController extends WorksController
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
        $reDataArr['status_kv'] =  CompanyProblem::$status_arr;
        $reDataArr['defaultStatus'] = -1;// 列表页默认状态
        return view('weixiu.problem.index', $reDataArr);
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
        $request->merge([
            'department_id' => $this->user_info['department_id']
            , 'group_id' => $this->user_info['group_id']
            , 'operate_staff_id' => $this->user_info['id']
        ]);
        return  CompanyProblem::getIndexList($request, $this,2 + 4);
    }

    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
           //  'call_number' => $this->user_info['mobile'] ?? '',
            'resource_list'=> [],
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyProblem::getInfoData($request, $this, $id, '');
            $content = $resultDatas['content'] ?? '';
            $resultDatas['content'] = replace_enter_char($content,2);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        // 初始化数据
        $arrList = CompanyProblem::addInitData( $request, $this);
        $reDataArr = array_merge($reDataArr, $arrList);
        return view('weixiu.problem.add', $reDataArr);
    }

    /**
     * 获取二级分类
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_gettype(Request $request)
//    {
//        $parent_id = $_POST['id'];
//        $this->InitParams($request);
//        return CompanyProblem::getWorkTypeArr($this, $parent_id);
//    }

    /**
     * 获取二级地址
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_getarea(Request $request)
//    {
//        $parent_id = $_POST['id'];
//        $this->InitParams($request);
//        return CompanyProblem::getAreaArr($this, $parent_id);
//    }


    /**
     * 获取二级地址
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
//    public function ajax_problem_add(Request $request)
//    {
//        $this->InitParams($request);
//        return CompanyProblem::problemAdd($request,$this);
//    }

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
        $resultDatas = CompanyProblem::ajaxSave( $request, $this);
        return ajaxDataArr(1, $resultDatas, '');
    }


}
