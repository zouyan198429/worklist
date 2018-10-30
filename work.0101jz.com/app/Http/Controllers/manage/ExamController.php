<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanyExam;
use App\Business\CompanyExamStaff;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use App\Services\Tool;
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
    public function add(Request $request, $id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        // 获得详情信息
        $resultDatas = [
            'id'=>$id,
            'now_paper' => 0,
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $relations = ['examPaperHistory', 'examPaper'];
            $resultDatas = CompanyExam::getInfoData($request, $this, $id, $relations);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        $reDataArr['operate'] = $operate;
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
        $exam_num = Common::get($request, 'exam_num');
        $exam_subject = Common::get($request, 'exam_subject');

        $exam_begin_time = Common::get($request, 'exam_begin_time');// 开考时间
        $nowTime = time();
        // 判断开始结束日期[ 可为空,有值的话-；4 开始日期 不能大于 >  当前日；32 结束日期 不能大于 >  当前日;256 开始日期 不能大于 >  结束日期]
        Tool::judgeBeginEndDate($exam_begin_time, '', 1 + 8 + 16);

        $exam_minute = Common::getInt($request, 'exam_minute');

        $paper_id = Common::getInt($request, 'paper_id');
        $paper_history_id = Common::getInt($request, 'paper_history_id');

        $pass_score = Common::get($request, 'pass_score');

        // 员工信息
        $staff_ids = Common::get($request, 'staff_ids');
        $staff_history_ids = Common::get($request, 'staff_history_ids');
        $department_ids = Common::get($request, 'department_ids');
        $department_names = Common::get($request, 'department_names');
        $group_ids = Common::get($request, 'group_ids');
        $group_names = Common::get($request, 'group_names');
        $position_ids = Common::get($request, 'position_ids');
        $position_names = Common::get($request, 'position_names');
        // 答案
        $staffList = [];

        foreach($staff_ids as $k => $staff_id ){
            $temStaff = [
                'company_id' => $company_id,
                // 'exam_id' => $exam_id,
                'staff_id' => $staff_id,
                'staff_history_id' => $staff_history_ids[$k] ?? 0,
                'exam_begin_time' => $exam_begin_time,
               // 'exam_end_time' => $exam_end_time,
                'exam_minute' => $exam_minute,
                'paper_history_id' => $paper_history_id,
                'paper_id' => $paper_id,
                'department_id' => $department_ids[$k] ?? 0,
                'department_name' => $department_names[$k] ?? '',
                'group_id' => $group_ids[$k] ?? 0,
                'group_name' => $group_names[$k] ?? '',
                'position_id' => $position_ids[$k] ?? 0,
                'position_name' => $position_names[$k] ?? '',
            ];
            array_push($staffList, $temStaff);
        }

        $saveData = [
            'id' => $id,
            'company_id' => $company_id,
            'exam_num' => $exam_num,
            'exam_subject' => $exam_subject,
            'paper_history_id' => $paper_history_id,
            'paper_id' => $paper_id,
            // 'subject_amount' => 0,
            // 'total_score' => 0,
            'exam_begin_time' => $exam_begin_time,
            'exam_minute' => $exam_minute,
            // 'exam_end_time' => $exam_end_time,
            'pass_score' => $pass_score,
            'subject_num' => count($staff_ids),
            'status' => 1,
            'staffList' => $staffList,
        ];

        $resultDatas = CompanyExam::saveById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

    /**
     * ajax获得试题数据-根据试卷id
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_get_staff(Request $request){
        $this->InitParams($request);
        $result = CompanyExamStaff::getList($request, $this, 1 + 0, [], ['examStaffs','examStaffHistory']);
        $data_list = $result['result']['data_list'] ?? [];
        foreach($data_list as $k => $v){
            $historyVersion = $v['exam_staff_history']['version_num'] ?? '';
            $version = $v['exam_staffs']['version_num'] ?? '';
            $now_staff = 0;
            if($version === ''){
                $now_staff = 1;
            }elseif($historyVersion != $version){
                $now_staff = 2;
            }
            $data_list[$k]['now_staff'] = $now_staff;// 最新的试题 0没有变化 ;1 已经删除  2 员工不同
            $data_list[$k]['work_num'] = $v['exam_staff_history']['work_num'] ?? '';
            $data_list[$k]['real_name'] = $v['exam_staff_history']['real_name'] ?? '';
            $data_list[$k]['sex_text'] = $v['exam_staff_history']['sex_text'] ?? '';
            $data_list[$k]['mobile'] = $v['exam_staff_history']['mobile'] ?? '';
        }
        return ajaxDataArr(1, ['data_list' => $data_list], '');
    }
    /**
     * ajax增加员工数据-根据试卷id,多个,号分隔
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_add_staff(Request $request){
        $this->InitParams($request);
        $relations = ['subjectAnswer', 'answerType'];
        $subjectData = CompanyExam::addStaffData($request, $this, '', $relations);
        return ajaxDataArr(1, $subjectData, '');
    }
    /**
     * ajax添加/修改试卷地址
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_add_paper(Request $request){
        $this->InitParams($request);
        $relations = ['subjectAnswer', 'answerType'];
        $subjectData = CompanyExam::addPaperData($request, $this, '', $relations);
        return ajaxDataArr(1, $subjectData, '');
    }
}
