<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanySubject;
use App\Business\CompanySubjectType;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class SubjectController extends AdminController
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
        $reDataArr['selTypes'] =  CompanySubject::$selTypes;
        $reDataArr['defaultSelType'] = 0;// 列表页默认状态

        // 分类一维数组[$k=>$v]
        $reDataArr['type_kv'] = CompanySubjectType::getListKeyVal($request, $this, 1 + 0);
        $reDataArr['default_type_kv'] = 0;// 分类默认值
        return view('manage.subject.index', $reDataArr);
    }

    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request, $id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得详情信息
        $resultDatas = [
            'id'=>$id,
            'answer_list' => [],
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $relations = ['subjectAnswer'];
            $resultDatas = CompanySubject::getInfoData($request, $this, $id, $relations);
            // 题目
            $title = $resultDatas['title'] ?? '';
            $resultDatas['title'] = replace_enter_char($title,2);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        $reDataArr['operate'] = $operate;

        $reDataArr['selTypes'] =  CompanySubject::$selTypes;
        // 分类一维数组[$k=>$v]
        $reDataArr['type_kv'] = CompanySubjectType::getListKeyVal($request, $this, 1 + 0);
        return view('manage.subject.add', $reDataArr);
    }
//
//    /**
//     * 显示
//     *
//     * @param Request $request
//     * @return mixed
//     * @author zouyan(305463219@qq.com)
//     */
//    public function info(Request $request, $id = 0)
//    {
//        $this->InitParams($request);
//        $reDataArr = $this->reDataArr;
//
//        // 详情信息
//        $resultDatas = [
//            'id'=>$id,
//        ];
//
//        if ($id > 0) { // 获得详情数据
//            $resultDatas =CompanySubject::getInfoData($request, $this, $id, ['oprateStaffHistory', 'lorePositions']);
//            // 修改点击点
//            $id = $resultDatas['id'] ??  0;
//            $volume = $resultDatas['volume'] ??  0;
//            $saveData = [
//                'volume' => $volume + 1,
//            ];
//            CompanySubject::replaceById($request, $this, $saveData, $id, false);
//            $resultDatas['volume'] = $volume + 1;
//        }
//        $reDataArr = array_merge($reDataArr, $resultDatas);
//
//        // 上一条
//        $preList = CompanySubject::getNearList($request, $this, $id, 1, 1, 0, [], '');
//        $reDataArr['preList'] = $preList;
//        // 下一条
//        $nextList = CompanySubject::getNearList($request, $this, $id, 2, 1, 0, [], '');
//        $reDataArr['nextList'] = $nextList;
//        return view('manage.lore.info', $reDataArr);
//    }

    /**
     * ajax获得客户列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_alist(Request $request){
        $this->InitParams($request);
        return  CompanySubject::getList($request, $this, 2 + 4, [], ['subjectAnswer', 'answerType', 'oprateStaffHistory']);
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
        CompanySubject::getList($request, $this, 1 + 0, [], ['subjectAnswer', 'answerType', 'oprateStaffHistory']);
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
        CompanySubject::importTemplate($request, $this);
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
        return CompanySubject::delAjax($request, $this);
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
        $company_id = $this->company_id;
        $id = Common::getInt($request, 'id');
        // Common::judgeEmptyParams($request, 'id', $id);
        $type_id = Common::getInt($request, 'type_id');
        $subject_type = Common::getInt($request, 'subject_type');
        $title = Common::get($request, 'title');
        $title =  replace_enter_char($title,1);
        $answer = Common::getInt($request, 'answer');
        // 答案
        $answer_ids = Common::get($request, 'answer_id');
        $answer_contents = Common::get($request, 'answer_content');
        $answer_val = Common::getInt($request, 'answer_val');
        $check_answer_vals = Common::get($request, 'check_answer_val');
        // 答案
        $answerList = [];
        if(in_array($subject_type, [1, 2])){// 单选多选
            $answer = 0;
            $max_sort_num = count($answer_ids);
            $answerVal = 1;
            foreach($answer_ids as $k => $answer_id ){
                $is_right = 0;
                if($subject_type == 1 && $answer_val == $answerVal) $is_right = 1;// 单选
                // 多选
                if($subject_type == 2 && in_array($answerVal, $check_answer_vals)) $is_right = 1;
                $temAnswer = [
                    'company_id' => $company_id,
                    // 'sort_num' => $max_sort_num,
                    'answer_content' => $answer_contents[$k] ?? '',
                    // 'answer_val' => $answerVal,
                    'is_right' => $is_right,
                ];
                array_push($answerList, $temAnswer);
                if($is_right == 1) $answer = ($answer | $answerVal);
                $max_sort_num--;
                $answerVal *= 2;

            }
        }

        $saveData = [
            'id' => $id,
            'company_id' => $company_id,
            'type_id' => $type_id,
            'subject_type' => $subject_type,
            'title' => $title,
            'answer' => $answer,
            'answer_list' => $answerList,
        ];

        $resultDatas = CompanySubject::saveById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }


}
