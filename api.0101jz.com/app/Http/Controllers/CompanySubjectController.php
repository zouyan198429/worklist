<?php

namespace App\Http\Controllers;

use App\Business\CompanyStaffBusiness;
use App\Business\CompanySubjectAnswerBusiness;
use App\Business\CompanySubjectBusiness;
use App\Models\CompanyDepartment;
use App\Models\CompanyProblemType;
use App\Models\CompanySubject;
use App\Models\CompanySubjectAnswer;
use App\Services\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanySubjectController extends CompController
{
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

}