<?php

namespace App\Http\Controllers;

use App\Business\CompanyExamStaffBusiness;
use App\Business\CompanyStaffBusiness;
use App\Business\CompanySubjectAnswerBusiness;
use App\Business\CompanySubjectBusiness;
use App\Business\CompanySubjectTypeBusiness;
use App\Models\CompanyDepartment;
use App\Models\CompanyExam;
use App\Models\CompanyExamStaff;
use App\Models\CompanyPaperHistory;
use App\Models\CompanyProblemType;
use App\Models\CompanySubject;
use App\Models\CompanySubjectAnswer;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanySubjectController extends CompController
{

    /**
     * 测试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function test(Request $request)
    {
        CompanyExamStaffBusiness::autoExamStaff();
    }
    /**
     * 添加/修改
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function add_save(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // $problem_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');
        $hasTwoArr = true;// 是否二维数组 true:二维数组，false:一维数组
        foreach($save_data as $v){
            if(!is_array($v)){
                $hasTwoArr = false;
            }
            break;
        }
        if(!$hasTwoArr) $save_data = [$save_data];

        // 操作人员历史
        $staffObj = null;
        $staffHistoryObj = null;
        CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );
        $operate_staff_history_id = $staffHistoryObj->id;

        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

        $oprateArr = [
            'operate_staff_id' => $staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,
        ];

        $reObject = [];
        DB::beginTransaction();
        try {
            foreach($save_data as $subjectInfo){
                $subjectId = $subjectInfo['id'] ?? 0;
                if(isset($subjectInfo['id'])) unset($subjectInfo['id']);
                $subject_type = $subjectInfo['subject_type'] ?? '';

                $answer_list = $subjectInfo['answer_list'] ?? [];
                if(isset($subjectInfo['answer_list'])) unset($subjectInfo['answer_list']);

                $subjectInfo['company_id'] = $company_id;
                $title = $subjectInfo['title'] ?? '';
                $subjectInfo = array_merge($subjectInfo, $oprateArr);

                if(empty($answer_list) && in_array($subject_type, [1,2])) throws('记录[' . $title . ']不能没有答案');

                $type_id = $subjectInfo['type_id'] ?? '';
                if(empty($type_id)) throws('记录[' . $title . ']分类不能为空');
                if(is_string($type_id)){
                    $updateFields = $oprateArr;
                    $subjectTypeObj = CompanySubjectTypeBusiness::firstOrCreate($company_id, $type_id, $updateFields);
                    $type_id = $subjectTypeObj->id;
                    if(!is_numeric($type_id)) throws('记录[' . $title . ']分类有误');
                    $subjectInfo['type_id'] = $type_id;
                }

                // $answer_ids = [];// 试题答案id,多个逗号,分隔
                $answer_history_ids = [];// 试题答案历史id,多个逗号,分隔

                $subjectObj = null;
                if($subjectId > 0){
                    $subjectObj = CompanySubject::find($subjectId);
                    if($company_id != $subjectObj->company_id) throws('记录[' . $title . ']没有操作权限');
                    foreach($subjectInfo as $field => $val){
                        $subjectObj->$field = $val;
                    }
                }else{
                    Common::getObjByModelName("CompanySubject", $subjectObj);
                    $subjectObj = Common::create($subjectObj, $subjectInfo);
                    $subjectId = $subjectObj->id;
                }

                if($subjectId <= 0) throws('记录[' . $title . ']不存在');
                // $subjectObj = CompanySubjectBusiness::updateOrCreate($company_id, $subjectId , $subjectInfo);
                // 处理 答案
                $answerNum = count($answer_list);
                $answerVal = 1;
                $answerIds = [];
                $answerOk = 0;
                foreach($answer_list as $answerInfo){
                    $answerInfo = array_merge($answerInfo, $oprateArr);
                    $answerInfo['subject_id'] = $subjectId;
                    $answerInfo['sort_num'] = $answerNum;
                    $answerInfo['answer_val'] = $answerVal;
                    $answer_content = $answerInfo['answer_content'] ?? '';

                    $is_right = $answerInfo['is_right'] ?? 0;
                    if(!in_array($is_right, [0, 1])) throws('记录[' . $title . ']答案[' . $answer_content . ']是否正确参数不正确!');
                    if($is_right == 1) $answerOk = ($answerOk | $answerVal);

                    $answerObj = CompanySubjectAnswerBusiness::updateOrCreate($company_id, $subjectId , $answerVal, $answerInfo);
                    $answerId = $answerObj->id;
                    if($answerId <= 0) throws('记录[' . $title . ']答案[' . $answer_content . ']不存在');
                    array_push($answerIds, $answerId);

                    // 判断版本号是否要+1
                    $vAnswerObj = null;
                    $vAnswerHistoryObj = null;
                    CompanySubjectAnswerBusiness::compareHistoryOrUpdateVersion($vAnswerObj , $vAnswerHistoryObj, $company_id, $answerId);

                    // 获得答案历史
                    $temAnswerObj = null;
                    $answerHistoryObj = null;
                    CompanySubjectAnswerBusiness::getHistoryStaff($temAnswerObj,$answerHistoryObj, $company_id, $answerId);
                    $answerHistoryId = $answerHistoryObj->id;
                    if($answerHistoryId <= 0) throws('记录[' . $title . ']答案[' . $answer_content . ']历史记录不存在');
                    array_push($answer_history_ids, $answerHistoryId);

                    $answerNum--;
                    $answerVal *= 2;
                }
                // 没有一个正确的答案
                if(in_array($subject_type, [1, 2]) && $answerOk <= 0 ) throws('记录[' . $title . ']不能没有正确答案!');
                if(in_array($subject_type, [1, 2])) $subjectObj->answer = $answerOk;// 正确答案

                // 删除多余的答案
                $answerWhere = [
                    ['company_id', '=', $company_id],
                    ['subject_id', '=', $subjectId],
                ];
                if(!empty($answerIds)){
                    CompanySubjectAnswer::where($answerWhere)->whereNotIn('id', $answerIds)->delete();
                }else{
                    CompanySubjectAnswer::where($answerWhere)->delete();
                }
                $subjectObj->answer_ids = implode(',', $answerIds);
                $subjectObj->answer_history_ids = implode(',', $answer_history_ids);
                $subjectObj->save();

                // 判断版本号是否要+1
                $vSubjectObj = null;
                $vSubjectHistoryObj = null;
                CompanySubjectBusiness::compareHistoryOrUpdateVersion($vSubjectObj , $vSubjectHistoryObj, $company_id, $subjectId);


                array_push($reObject, $subjectObj);
            }

        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        if(!$hasTwoArr) $reObject = $reObject[0] ?? [];// 一维数组
        return  okArray($reObject);
    }

    /**
     * 通过id获得试题
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function getSubjectByIds(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // $problem_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $ids = Common::get($request, 'ids');
        Common::judgeEmptyParams($request, 'ids', $ids);
        $relations = Common::get($request, 'relations');
        if(!empty($relations))   jsonStrToArr($relations , 1, '参数[relations]格式有误!');// json 转成数组

        $subjectObj = null;
        Common::getObjByModelName("CompanySubject", $subjectObj);
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                // ['id', '1'],
                // ['phonto_name', 'like', '%知的标题1%']
            ],
            // 'orderBy' => ['id'=>'desc','company_id'=>'asc'],
        ];
        if (strpos($ids, ',') === false) { // 单条
            array_push($queryParams['where'],['id', $ids]);
        }else{
            $queryParams['whereIn']['id'] = explode(',',$ids);
        }

        $relations = ['subjectAnswer', 'answerType'];
        $requestData = Common::getAllModelDatas($subjectObj, $queryParams, $relations)->toArray();;
        // 试题历史
        foreach($requestData as $k => $v){
            $subjectId = $v['id'] ?? 0;
            $title = $v['title'] ?? '';
            // 类型名称
            $requestData[$k]['type_name'] = $v['answer_type']['type_name'] ?? '';
            if(isset($requestData[$k]['answer_type'])) unset($requestData[$k]['answer_type']);
            // 获得试题历史
            $temSubjectObj = null;
            $subjectHistoryObj = null;
            CompanySubjectBusiness::getHistoryStaff($temSubjectObj,$subjectHistoryObj, $company_id, $subjectId);
            $subjectHistoryId = $subjectHistoryObj->id;
            if($subjectHistoryId <= 0) throws('记录[' . $title . ']历史记录不存在');
            $requestData[$k]['subject_history_id'] = $subjectHistoryId;
            $requestData[$k]['subject_id'] = $subjectId;
            $requestData[$k]['now_subject'] = 0;// 最新的试题 0没有变化 ;1 已经删除  2 试题不同  4 答案不同
        }
        return okArray($requestData);
    }

    /**
     * 添加/修改考试
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function saveExam(Request $request)
    {
        $this->InitParams($request);
        $company_id = $this->company_id;
        // $exam_id = Common::getInt($request, 'id');
        $staff_id = Common::getInt($request, 'staff_id');// 操作员工
        $save_data = Common::get($request, 'save_data');
        Common::judgeEmptyParams($request, 'save_data', $save_data);
        // json 转成数组
        jsonStrToArr($save_data , 1, '参数[save_data]格式有误!');
        $hasTwoArr = true;// 是否二维数组 true:二维数组，false:一维数组
        foreach($save_data as $v){
            if(!is_array($v)){
                $hasTwoArr = false;
            }
            break;
        }
        if(!$hasTwoArr) $save_data = [$save_data];

        // 操作人员历史
        $staffObj = null;
        $staffHistoryObj = null;
        CompanyStaffBusiness::getHistoryStaff($staffObj , $staffHistoryObj, $company_id, $staff_id );
        $operate_staff_history_id = $staffHistoryObj->id;

        Common::judgeEmptyParams($request, '员工历史记录ID', $operate_staff_history_id);

        $oprateArr = [
            'operate_staff_id' => $staff_id,
            'operate_staff_history_id' => $operate_staff_history_id,
        ];

        $reObject = [];
        DB::beginTransaction();
        try {
            foreach($save_data as $examInfo){
                $examId = $examInfo['id'] ?? 0;
                if(isset($examInfo['id'])) unset($examInfo['id']);

                $staffList = $examInfo['staffList'] ?? [];
                if(isset($examInfo['staffList'])) unset($examInfo['staffList']);
                $paper_history_id = $examInfo['paper_history_id'] ?? 0;
                if(empty($paper_history_id)) throws('没有试卷信息');

                $paperInfo = CompanyPaperHistory::find($paper_history_id);
                if(empty($paperInfo)) throws('没有试卷信息!');
                $examInfo['subject_amount'] = $paperInfo['subject_amount'];
                $examInfo['total_score'] = $paperInfo['total_score'];

                $examInfo['company_id'] = $company_id;
                $exam_num = $examInfo['exam_num'] ?? '';
                $exam_begin_time  = $examInfo['exam_begin_time'] ?? '';// 开考时间
                $exam_minute  = $examInfo['exam_minute'] ?? '';// 考试时长分
                $exam_end_time = date('Y-m-d H:i:s', strtotime($exam_begin_time . ' +' . $exam_minute . ' minute'));
                $examInfo['exam_end_time'] = $exam_end_time;
                $examInfo = array_merge($examInfo, $oprateArr);

                $examObj = null;
                if($examId > 0){
                    $examObj = CompanyExam::find($examId);
                    if($company_id != $examObj->company_id) throws('记录[' . $exam_num . ']没有操作权限');
                    $tem_exam_begin_time = $examObj->exam_begin_time;
                    $now_time = date('Y-m-d H:i:s');
                    if(strtotime($tem_exam_begin_time) <= strtotime($now_time)) throws('已过考试开始时间的场次不能修改!');

                    foreach($examInfo as $field => $val){
                        $examObj->$field = $val;
                    }
                }else{
                    Common::getObjByModelName("CompanyExam", $examObj);
                    $examObj = Common::create($examObj, $examInfo);
                    $examId = $examObj->id;
                }

                if($examId <= 0) throws('记录[' . $exam_num . ']不存在');
                // $examObj = CompanySubjectBusiness::updateOrCreate($company_id, $examId , $examInfo);
                $examStaffIds = [];
                // 处理 员工
                foreach($staffList as $staffInfo){
                    $staffInfo = array_merge($staffInfo, $oprateArr);
                    $staff_id = $staffInfo['staff_id'];
                    $staffInfo['exam_end_time'] = $exam_end_time;
                    $staffObj = CompanyExamStaffBusiness::updateOrCreate($company_id, $examId , $staff_id, $staffInfo);
                    $temId = $staffObj->id;
                    if($temId <= 0) throws('记录[' . $exam_num . ']员工[' . $staff_id . ']不存在');
                    array_push($examStaffIds, $temId);

                }

                // 删除多余的答案
                $answerWhere = [
                    ['company_id', '=', $company_id],
                    ['exam_id', '=', $examId],
                ];
                if(!empty($examStaffIds)){
                    CompanyExamStaff::where($answerWhere)->whereNotIn('id', $examStaffIds)->delete();
                }else{
                    CompanyExamStaff::where($answerWhere)->delete();
                }
                $examObj->save();

            }

        } catch ( \Exception $e) {
            DB::rollBack();
            throws('提交失败；信息[' . $e->getMessage() . ']');
            // throws($e->getMessage());
        }
        DB::commit();
        if(!$hasTwoArr) $reObject = $reObject[0] ?? [];// 一维数组
        return  okArray($reObject);
    }

}
