<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanyExam;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class ExamController extends AdminController
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
        $reDataArr['status'] =  CompanyExam::$status_arr;
        $reDataArr['defaultStatus'] = '0';// 列表页默认状态
        return view('manage.exam.index', $reDataArr);
    }

    /**
     * 添加
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function add(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('manage.exam.add',$reDataArr);
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
        return  CompanyExam::getList($request, $this, 2 + 4, [], ['oprateStaffHistory', 'examPaperHistory']);
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
        CompanyExam::getList($request, $this, 1 + 0, [], [ 'oprateStaffHistory', 'examPaperHistory']);
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
        CompanyExam::importTemplate($request, $this);
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
        return CompanyExam::delAjax($request, $this);
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

        $resultDatas = CompanyExam::saveById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
