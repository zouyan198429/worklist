<?php

namespace App\Http\Controllers\manage;

use App\Business\CompanyPaper;
use App\Business\CompanySubject;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use Illuminate\Http\Request;

class PaperController extends AdminController
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
        $reDataArr['selTypes'] =  CompanyPaper::$order_type_arr;
        $reDataArr['defaultSelType'] = '';// 列表页默认状态
        return view('manage.paper.index', $reDataArr);
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
        ];
        $operate = "添加";

        if ($id > 0) { // 获得详情数据
            $operate = "修改";
            $relations = '';
            $resultDatas = CompanyPaper::getInfoData($request, $this, $id, $relations);
        }
        $reDataArr = array_merge($reDataArr, $resultDatas);

        $reDataArr['operate'] = $operate;
        // 试题类型
        $subjectTypes = $reDataArr['subjectTypes'] ?? [];
        $selTypeIds = array_column($subjectTypes,'type_id');
        // 其它试题类型
        $selTypes = CompanySubject::$selTypes;
        foreach($selTypes as $type_id => $type_name){
            if(in_array($type_id, $selTypeIds)) continue;
            $temArr = [
                'type_id' => $type_id,
                'type_name' => $type_name,
                'subject_count' => 0,
                'subject_score' => 0,
            ];
            array_push($subjectTypes, $temArr);
        }
        $reDataArr['subjectTypes'] = $subjectTypes;

        // 试题顺序0固定顺序1随机顺序
        $reDataArr['selTypes'] =  CompanyPaper::$order_type_arr;
        // $reDataArr['subject_order_type'] = '';// 列表页默认状态
        return view('manage.paper.add', $reDataArr);
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
        return  CompanyPaper::getList($request, $this, 2 + 4, [], ['oprateStaffHistory']);
    }

    /**
     * ajax获得试题数据-根据试卷id
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_get_subject(Request $request){
        $this->InitParams($request);
        $subjectInfo = CompanyPaper::getSubjectData($request, $this, 2);
        $subject_list = $subjectInfo['subject_list'] ?? [];
        $formatSubjectList = [];
        foreach($subject_list as $v){
            $formatSubjectList['subject_type_' . $v['subject_type']][] = $v;
        }
        $subjectInfo['subject_list'] = $formatSubjectList;
        return ajaxDataArr(1, $subjectInfo, '');
    }

    /**
     * ajax更新试题数据-根据试卷id
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_update_subject(Request $request){
        $this->InitParams($request);
        $subjectInfo = CompanyPaper::updateSubjectData($request, $this);
        return ajaxDataArr(1, [$subjectInfo], '');
    }

    /**
     * ajax增加试题数据-根据试卷id,多个,号分隔
     *
     * @param Request $request
     * @return mixed
     * @author liuxin
     */
    public function ajax_add_subject(Request $request){
        $this->InitParams($request);
        $relations = ['subjectAnswer', 'answerType'];
        $subjectData = CompanyPaper::addSubjectData($request, $this, '', $relations);
        return ajaxDataArr(1, $subjectData, '');
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
        CompanyPaper::getList($request, $this, 1 + 0, [], [ 'oprateStaffHistory']);
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
        CompanyPaper::importTemplate($request, $this);
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
        return CompanyPaper::delAjax($request, $this);
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
        $paper_name = Common::get($request, 'paper_name');// 试卷名称
        $subject_order_type = Common::getInt($request, 'subject_order_type');// 试题顺序

        // 试题类型
        $type_ids = Common::get($request, 'type_id');// 类型id
        if(is_string($type_ids)) $type_ids = explode(',', $type_ids);

        $type_names = Common::get($request, 'type_name');// 类型名称
        if(is_string($type_names)) $type_names = explode(',', $type_names);

        $subject_counts = Common::get($request, 'subject_count');// 类型试题数量
        if(is_string($subject_counts)) $subject_counts = explode(',', $subject_counts);

        $subject_scores = Common::get($request, 'subject_score');// 类型试题分数
        if(is_string($subject_scores)) $subject_scores = explode(',', $subject_scores);


        // 试题
        $subject_ids = Common::get($request, 'subject_ids');// 试题id
        if(is_string($subject_ids)) $subject_ids = explode(',', $subject_ids);

        $subject_history_ids = Common::get($request, 'subject_history_ids');// 试题历史id
        if(is_string($subject_history_ids)) $subject_history_ids = explode(',', $subject_history_ids);

        $subject_amount = 0;
        $total_score = 0;
        $subject_types = [];
        foreach($type_ids as $k => $type_id){
            $type_name = $type_names[$k] ?? '';
            $subject_count = $subject_counts[$k] ?? 0;
            $subject_score = $subject_scores[$k] ?? 0;
            $temArr = [$type_id, $type_name, $subject_count, $subject_score];
            array_push($subject_types,implode(CompanyPaper::$smallSplitType, $temArr));

            $subject_amount += $subject_count;
            $total_score += $subject_score;
        }

        $saveData = [
            // 'id' => $id,
            // 'company_id' => $company_id,
            'paper_name' => $paper_name,
            'subject_order_type' => $subject_order_type,
            'subject_amount' => $subject_amount,
            'total_score' => $total_score,
            'subject_types' => implode(CompanyPaper::$bigSplitType, $subject_types),
            'subject_ids' => implode(',', $subject_ids),
            'subject_history_ids' => implode(',', $subject_history_ids),
            'operate_staff_id' => $this->user_id,
        ];
        $saveData['subject_ids_search'] = ',' . $saveData['subject_ids'] . ',' ;
        $saveData['subject_history_ids_search'] = ',' . $saveData['subject_history_ids'] . ',' ;
        $resultDatas = CompanyPaper::replaceById($request, $this, $saveData, $id);
        return ajaxDataArr(1, $resultDatas, '');
    }

}
