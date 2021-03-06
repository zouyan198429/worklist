<?php

namespace App\Http\Controllers\admin;

use App\Business\CompanySubject;
use App\Business\CompanySubjectType;
use App\Business\Resource;
use App\Http\Controllers\AdminController;
use App\Services\Common;
use App\Services\Tool;
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
        return view('admin.subject.index', $reDataArr);
    }

    /**
     * 试题选择-弹窗
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function select(Request $request)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        $reDataArr['selTypes'] =  CompanySubject::$selTypes;
        $reDataArr['defaultSelType'] = Common::getInt($request, 'subject_type');// 列表页默认状态

        // 分类一维数组[$k=>$v]
        $reDataArr['type_kv'] = CompanySubjectType::getListKeyVal($request, $this, 1 + 0);
        $reDataArr['default_type_kv'] = 0;// 分类默认值
        return view('admin.subject.select', $reDataArr);
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
        return view('admin.subject.add',$reDataArr);
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
     * 单文件上传-导入excel
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function import(Request $request)
    {
        $this->InitParams($request);
        // 上传并保存文件
        $result = Resource::fileSingleUpload($request, $this, 1);
        if($result['apistatus'] == 0) return $result;
        // 文件上传成功
        $fileName = Tool::getPath('public') . '/' . $result['result']['filePath'];
        $resultDatas = CompanySubject::staffImportByFile($request, $this, $fileName);
        return ajaxDataArr(1, $resultDatas, '');
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
