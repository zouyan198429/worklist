<?php

namespace App\Http\Controllers\weixiu;

use App\Http\Controllers\WorksController;
use Illuminate\Http\Request;
use App\Business\CompanyProblem;

class ProblemController extends WorksController
{
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
