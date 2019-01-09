<?php

namespace App\Http\Controllers\weixiu;

use App\Business\CompanyExamStaff;
use App\Http\Controllers\WorksController;
use App\Services\Common;
use Illuminate\Http\Request;

class ExamController extends WorksController
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
        $reDataArr['selStatus'] =  CompanyExamStaff::$selStatus;
        $reDataArr['defaultSelStatus'] = 1;// 列表页默认状态
        return view('weixiu.exam.index', $reDataArr);
    }

    /**
     * 在线考试
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function doing(Request $request,$id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $resultDatas = [
            'id'=>$id,
        ];

        if ($id > 0) { // 获得详情数据
            $resultDatas = CompanyExamStaff::getInfoData($request, $this, $id, ['staffExam']);
            // 时间处理
            $exam_begin_time = $resultDatas['answer_begin_time'] ?? '';
            $exam_end_time = $resultDatas['exam_end_time'] ?? '';
            $exam_minute = $resultDatas['exam_minute'] ?? 0;// 考试时长
            if(empty($exam_begin_time) || is_null($exam_begin_time)) {
                $exam_begin_time = date('Y-m-d H:i:s');
                $exam_end_time =  date('Y-m-d H:i:s', strtotime($exam_begin_time . ' +' . $exam_minute . ' minute'));
            }
            // $resultDatas['exam_begin_time'] = $exam_begin_time;
            $resultDatas['answer_begin_time'] = $exam_begin_time;
            $resultDatas['exam_end_time'] = $exam_end_time;
            // $resultDatas['staff_exam']['exam_begin_time'] = $exam_begin_time;
            // $resultDatas['staff_exam']['exam_end_time'] = $exam_end_time;
//            $staff_id = $resultDatas['staff_id'] ?? '';// 考试所属人
//
//            // 开始时间
//            $exam_begin_time = $resultDatas['exam_begin_time'] ?? '';
//            // 结束时间
//            $exam_end_time = $resultDatas['exam_end_time'] ?? '';
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);
        return view('weixiu.exam.doing', $reDataArr);
    }

    /**
     * 在线考试完成
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function win(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $examStaffInfo = CompanyExamStaff::examStaffInfo($request, $this, $id);
        $reDataArr = array_merge($reDataArr, $examStaffInfo);
        return view('weixiu.exam.win', $reDataArr);
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
        $company_id = $this->company_id;
        $user_id = $this->user_id;
        $queryParams = [
            'where' => [
                ['company_id', $company_id],
                ['staff_id', $user_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        return  CompanyExamStaff::getList($request, $this, 2 + 4, $queryParams, ['staffExam']);
    }

    /**
     * ajax在线考试初始化地址
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_init_exam(Request $request){
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        $return  = CompanyExamStaff::initExam($request, $this, $id);
        return ajaxDataArr(1, $return, '');

    }

    /**
     * ajax获得客户列表数据
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function doing_ajax_save(Request $request){
        $this->InitParams($request);
        $id = Common::getInt($request, 'id');
        $return  = CompanyExamStaff::doingExam($request, $this, $id);
        return ajaxDataArr(1, $return, '');
    }
}
