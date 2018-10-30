<?php
// 考次的人员
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyExamStaff extends BaseBusiness
{
    protected static $model_name = 'CompanyExamStaff';
    // 状态1待考试2考试中3已考试4缺考
    public static $selStatus = [
        '1' => '待考试',
        '2' => '考试中',
        '3' => '已考试',
        '4' => '缺考',
    ];

    /**
     * 获得列表数据--所有数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateBit 操作类型位 1:获得所有的; 2 分页获取[同时有1和2，2优先]；4 返回分页html翻页代码
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param array $extParams 其它扩展参数，
     *    $extParams = [
     *        'useQueryParams' => '是否用来拼接查询条件，true:用[默认];false：不用'
     *   ];
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function getList(Request $request, Controller $controller, $oprateBit = 2 + 4, $queryParams = [], $relations = '', $extParams = [], $notLog = 0){
        $company_id = $controller->company_id;

        // 获得数据
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
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
        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $isExport = 0;

        $useSearchParams = $extParams['useQueryParams'] ?? true;// 是否用来拼接查询条件，true:用[默认];false：不用
        if($useSearchParams) {
            // $params = self::formatListParams($request, $controller, $queryParams);
            $exam_id = Common::getInt($request, 'exam_id');
            if ($exam_id > 0) {
                array_push($queryParams['where'], ['exam_id', $exam_id]);
            }

            $status = Common::getInt($request, 'status');
            if ($status > 0) {
                array_push($queryParams['where'], ['status', $status]);
            }

            $ids = Common::get($request, 'ids');// 多个用逗号分隔,
            if (!empty($ids)) {
                if (strpos($ids, ',') === false) { // 单条
                    array_push($queryParams['where'], ['id', $ids]);
                } else {
                    $queryParams['whereIn']['id'] = explode(',', $ids);
                }
            }
            $isExport = Common::getInt($request, 'is_export'); // 是否导出 0非导出 ；1导出数据
            if ($isExport == 1) $oprateBit = 1;
        }
        // $relations = ['CompanyInfo'];// 关系
        // $relations = '';//['CompanyInfo'];// 关系
        $result = self::getBaseListData($request, $controller, self::$model_name, $queryParams,$relations , $oprateBit, $notLog);

        // 格式化数据
        $data_list = $result['data_list'] ?? [];
        foreach($data_list as $k => $v){
            $data_list[$k]['exam_num'] = $v['staff_exam']['exam_num'] ?? '';
            $data_list[$k]['exam_subject'] = $v['staff_exam']['exam_subject'] ?? '';
            $data_list[$k]['subject_amount'] = $v['staff_exam']['subject_amount'] ?? '';
            $data_list[$k]['total_score'] = $v['staff_exam']['total_score'] ?? '';
            $data_list[$k]['pass_score'] = $v['staff_exam']['pass_score'] ?? '';

//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){
//            $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//            ImportExport::export('','excel文件名称',$data_list,1, $headArr, 0, ['sheet_title' => 'sheet名称']);
            die;
        }
        // 非导出功能
        return ajaxDataArr(1, $result, '');
    }

    /**
     * 格式化列表查询条件-暂不用
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $queryParams 条件数组/json字符
     * @return  array 参数数组 一维数据
     * @author zouyan(305463219@qq.com)
     */
//    public static function formatListParams(Request $request, Controller $controller, &$queryParams = []){
//        $params = [];
//        $title = Common::get($request, 'title');
//        if(!empty($title)){
//            $params['title'] = $title;
//            array_push($queryParams['where'],['title', 'like' , '%' . $title . '%']);
//        }
//
//        $ids = Common::get($request, 'ids');// 多个用逗号分隔,
//        if (!empty($ids)) {
//            $params['ids'] = $ids;
//            if (strpos($ids, ',') === false) { // 单条
//                array_push($queryParams['where'],['id', $ids]);
//            }else{
//                $queryParams['whereIn']['id'] = explode(',',$ids);
//                $params['idArr'] = explode(',',$ids);
//            }
//        }
//        return $params;
//    }

    /**
     * 获得当前记录前/后**条数据--二维数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id 当前记录id
     * @param int $nearType 类型 1:前**条[默认]；2后**条 ; 4 最新几条;8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
     * @param int $limit 数量 **条
     * @param int $offset 偏移数量
     * @param string $queryParams 条件数组/json字符
     * @param mixed $relations 关系
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 列表数据 - 二维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getNearList(Request $request, Controller $controller, $id = 0, $nearType = 1, $limit = 1, $offset = 0, $queryParams = [], $relations = '', $notLog = 0)
    {
        $company_id = $controller->company_id;
        // 前**条[默认]
        $defaultQueryParams = [
            'where' => [
                ['company_id', $company_id],
//                ['id', '>', $id],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            'orderBy' => ['id'=>'asc'],
            'limit' => $limit,
            'offset' => $offset,
            // 'count'=>'0'
        ];
        if(($nearType & 1) == 1){// 前**条
            $defaultQueryParams['orderBy'] = ['id'=>'asc'];
            array_push($defaultQueryParams['where'],['id', '>', $id]);
        }

        if(($nearType & 2) == 2){// 后*条
            array_push($defaultQueryParams['where'],['id', '<', $id]);
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 4) == 4){// 4 最新几条
            $defaultQueryParams['orderBy'] = ['id'=>'desc'];
        }

        if(($nearType & 8) == 8){// 8 有count下标则是查询数量, 返回的数组中total 就是真实的数量
            $defaultQueryParams['count'] = 0;
        }

        if(empty($queryParams)){
            $queryParams = $defaultQueryParams;
        }
        $result = self::getList($request, $controller, 1 + 0, $queryParams, $relations, [], $notLog);
        // 格式化数据
        $data_list = $result['result']['data_list'] ?? [];
        if($nearType == 1) $data_list = array_reverse($data_list); // 相反;
//        foreach($data_list as $k => $v){
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
//        }
//        $result['result']['data_list'] = $data_list;
        return $data_list;
    }

    /**
     * 导入模版
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function importTemplate(Request $request, Controller $controller)
    {
//        $headArr = ['work_num'=>'工号', 'department_name'=>'部门'];
//        $data_list = [];
//        ImportExport::export('','员工导入模版',$data_list,1, $headArr, 0, ['sheet_title' => '员工导入模版']);
        die;
    }
    /**
     * 删除单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function delAjax(Request $request, Controller $controller)
    {
        return self::delAjaxBase($request, $controller, self::$model_name);

    }

    /**
     * 根据id获得单条数据
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getInfoData(Request $request, Controller $controller, $id, $relations = ''){
        $company_id = $controller->company_id;
        // $relations = '';
        $resultDatas = CommonBusiness::getinfoApi(self::$model_name, $relations, $company_id , $id);
        // $resultDatas = self::getInfoDataBase($request, $controller, self::$model_name, $id, $relations);
        // 判断权限
        $judgeData = [
            'company_id' => $company_id,
        ];
        CommonBusiness::judgePowerByObj($resultDatas, $judgeData );
        return $resultDatas;
    }

    /**
     * 根据id新加或修改单条数据-id 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param array $saveData 要保存或修改的数组
     * @param int $id id
     * @param boolean $modifAddOprate 修改时是否加操作人，true:加;false:不加[默认]
     * @param int $notLog 是否需要登陆 0需要1不需要
     * @return  array 单条数据 - -维数组 为0 新加，返回新的对象数组[-维],  > 0 ：修改对应的记录，返回true
     * @author zouyan(305463219@qq.com)
     */
    public static function replaceById(Request $request, Controller $controller, $saveData, &$id, $modifAddOprate = false, $notLog = 0){
        $company_id = $controller->company_id;
        if($id > 0){
            // 判断权限
            $judgeData = [
                'company_id' => $company_id,
            ];
            $relations = '';
            CommonBusiness::judgePower($id, $judgeData, self::$model_name, $company_id, $relations, $notLog);
            if($modifAddOprate) self::addOprate($request, $controller, $saveData);
        }else {// 新加;要加入的特别字段
            $addNewData = [
                'company_id' => $company_id,
            ];
            $saveData = array_merge($saveData, $addNewData);
            // 加入操作人员信息
            self::addOprate($request, $controller, $saveData);
        }
        // 新加或修改
        return self::replaceByIdBase($request, $controller, self::$model_name, $saveData, $id, $notLog);
    }

    /**
     * 在线考试 公共判断
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id 考试人员表id
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function doingJudge(Request $request, Controller $controller, $id = 0){
        $company_id = $controller->company_id;
        $user_id = $controller->user_id;
        // 获得详情数据
        $infoDatas = self::getInfoData($request, $controller, $id, ['staffExam']);
        $staff_id = $infoDatas['staff_id'] ?? '';// 考试所属人
        // 一、比对是否是本人操作
        if($user_id != $staff_id) throws('非法访问，不是考试本人操作!');
        // 二、比对是否在开始考试时间内
        // 开始时间
        $exam_begin_time = $infoDatas['staff_exam']['exam_begin_time'] ?? '';
        // 结束时间
        $exam_end_time = $infoDatas['staff_exam']['exam_end_time'] ?? '';
        if(empty($exam_begin_time) || empty($exam_end_time)) throws('开始时间、结束时间参数有误!');

        // 判断开始结束日期[ 1 判断开始日期不能为空 ; 2 判断结束日期不能为空；
        //  16 开始日期 不能小于 <  当前日 128 结束日期 不能小于 <  当前日 256 开始日期 不能大于 >  结束日期
        $now_time = date('Y-m-d H:i:s');
        // Tool::judgeBeginEndDate($exam_begin_time, $exam_end_time, 1 + 2 + 16 + 128 + 256, 1, $now_time, '时间');
        if(strtotime($exam_begin_time) > strtotime($now_time)) throws('还没有到考试时间!');
        if(strtotime($exam_end_time) < strtotime($now_time)) throws('考试已过期,如果您未交卷，系统已自动为您交卷!');

        // 三、比对是否已经答完题
        $status = $infoDatas['status'] ?? '';
        if(in_array($status, [3,4])) throws('已考试或缺考!');
        return $infoDatas;
    }

    /**
     * 在线考试初始化
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id 考试人员表id
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function initExam(Request $request, Controller $controller, $id = 0)
    {
        $company_id = $controller->company_id;
        $user_id = $controller->user_id;
        // 在线考试 公共判断
        $infoDatas = self::doingJudge($request,  $controller, $id);

        // 四、操作：1、如果第一次答题，生成答题顺序并保存到 subject_history_ids 字段;2、正在答题，不做任何处理
        $subject_history_ids = $infoDatas['subject_history_ids'] ?? '';
        if(empty($subject_history_ids)){// 生成答题顺序
            $paper_history_id = $infoDatas['paper_history_id'] ?? '';
            if(empty($paper_history_id))  throws('没有试卷信息!');
            // 获得试卷信息-根据试卷历史id
            $paperInfo = CompanyPaper::getExamSubject($request, $controller, $paper_history_id, 2, '');
            if(empty($paperInfo))  throws('没有试卷信息!!');
            $subjectTypes = $paperInfo['subjectTypes'] ?? [];// 试题类型数组
            $subject_list = $paperInfo['subject_list'] ?? [];// 试题数组
            $subject_order_type = $paperInfo['subject_order_type'] ?? 0;//试题顺序0固定顺序1随机顺序
            $sort_subject_list = [];// 排序好的试题数组
            // 二维数组排序
            $orderKeys = [
                ['key' => 'rand_num', 'sort' => 'desc', 'type' => 'numeric'],
                ['key' => 'id', 'sort' => 'asc', 'type' => 'numeric'],
            ];
            foreach($subjectTypes as $v){
                $tem_type_id = $v['type_id'] ?? 0;
                $tem_subject_list = $subject_list['subject_type_' . $tem_type_id] ?? [];
                if(empty($tem_subject_list)) continue;
                if($subject_order_type == 1){//  1随机顺序
                    foreach($tem_subject_list as $t_k => $t_v){
                        $tem_subject_list[$t_k]['rand_num'] = mt_rand(1,10000);
                    }
                    $tem_subject_list = Tool::php_multisort($tem_subject_list, $orderKeys);
                }
                $sort_subject_list = array_merge($sort_subject_list, $tem_subject_list);
            }
            // 获得试题历史id
            $subject_history_ids = array_column($sort_subject_list, 'subject_history_id');
            if(empty($subject_history_ids))  throws('没有试题信息!');
            // 保存试题历史id信息
            $subject_history_ids = implode(',', $subject_history_ids);
            $saveData = [
                'subject_history_ids' => $subject_history_ids,
                'answer_begin_time' => date('Y-m-d H:i:s'),
            ];
            self::replaceById($request, $controller, $saveData, $id);
        }
        return $subject_history_ids;
    }

    /**
     * 正在答题
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id 考试人员表id
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function doingExam(Request $request, Controller $controller, $id = 0)
    {
        $company_id = $controller->company_id;
        $user_id = $controller->user_id;
        // 在线考试 公共判断
        $infoDatas = self::doingJudge($request,  $controller, $id);
        // 四、获得 生成的答题顺序 subject_history_ids 字段
        $subject_history_ids = $infoDatas['subject_history_ids'] ?? '';
        if(empty($subject_history_ids))  throws('还没有生成答题顺序!');
        $subject_history_id_arr = explode(',', $subject_history_ids);

        $paper_history_id = $infoDatas['paper_history_id'] ?? '';
        if(empty($paper_history_id))  throws('没有试卷信息!');

        $exam_id = $infoDatas['exam_id'] ?? '';// 考次id
        if(empty($exam_id))  throws('没有考次信息!');

        $exam_end_time = $infoDatas['staff_exam']['exam_end_time'] ?? '';// 结束时间

        // 获得试卷信息-根据试卷历史id
        $paperInfo = CompanyPaper::getExamSubject($request, $controller, $paper_history_id, 1, '');
        if(empty($paperInfo))  throws('没有试卷信息!!');
        $subjectTypes = $paperInfo['subjectTypes'] ?? [];// 试题类型数组
        $subject_list = $paperInfo['subject_list'] ?? [];// 试题数组
        $subject_order_type = $paperInfo['subject_order_type'] ?? 0;//试题顺序0固定顺序1随机顺序

        // 格式化试题-试题历史id 为下标
        $format_subject_list = [];
        foreach($subject_list as $v){
            $format_subject_list[$v['subject_history_id']] = $v;
        }

        $subject_history_id = Common::getInt($request, 'subject_history_id');// 试题历史id 0:首次
        $subject_type = Common::getInt($request, 'subject_type');// 题目类型1单选；2多选；4判断
        $subject_id = Common::getInt($request, 'subject_id');// 试题

        $submit_type = Common::getInt($request, 'submit_type');// 请求类型 1 上一题 ; 2 下一题 3 初始页面

        // 处理保存答案
        if($subject_history_id > 0){// 保存答案
            // 用户填写的答案
            $form_answer = Common::get($request, 'answer'); // 判断题答案 0错 1对
            $form_answer_val = Common::get($request, 'answer_val');// 单选题答案
            $form_check_answer_vals = Common::get($request, 'check_answer_val');// 多选题答案
            if(empty($form_check_answer_vals)) $form_check_answer_vals = [];
            if(!is_array($form_check_answer_vals)) $form_check_answer_vals = explode(',', $form_check_answer_vals);

            // 当前回答问题的试题信息
            $subjectInfo = $format_subject_list[$subject_history_id] ?? [];
            if(empty($subjectInfo))  throws('试题[' . $subject_id . ']信息不存在!');
            $subject_answer = $subjectInfo['subject_answer'] ?? [];// 答案列表
            if(!is_array($subject_answer)) $subject_answer = [];

            $tem_subject_type = $subjectInfo['subject_type'] ?? '';// 题目类型1单选；2多选；4判断
            if(empty($tem_subject_type))  throws('试题[' . $subject_id . ']题目类型有误!');
            $score = $subjectInfo['score'] ?? 0;// 答正确了，得分
            $answer = $subjectInfo['answer'] ?? 0;// 正确答案--数值

            $user_doing = false;// 用户是否做答了，true:做答，false:未做答

            $user_answer_val = 0;// 用户选择的答案值 --数值
            $user_answer_colum = '';// 用户选择的答案值字母 --A、B、C... 或 √ ×

            $user_is_right = 1;// 用户是否正确 1:正确[默认];0不正确
            $user_score = 0 ;// 用户得分
            if($tem_subject_type == 4){// 判断题
                if($form_answer !== ''){
                    $user_doing = true;
                    $user_answer_val = $form_answer;
                    $user_answer_colum = ($form_answer == 1) ? '√' : '×';
                    if($user_answer_val == $answer){// 用户答正确了
                        $user_score = $score;
                    }else{
                        $user_is_right = 0;
                    }
                }
            }else{// 1单选；2多选
                if( ($tem_subject_type == 1 && !empty($form_answer_val) ) ||  ($tem_subject_type == 2 && !empty($form_check_answer_vals) ) ){// 有选择值
                    $user_doing = true;
                    if($tem_subject_type == 1){// 单选
                        $user_answer_val = $form_answer_val;
                    }else if($tem_subject_type == 2){
                        foreach($form_check_answer_vals as $tem_val){
                            $user_answer_val = $user_answer_val | $tem_val;
                        }
                    }
                    $user_colum = [];
                    $key = ord("A");
                    foreach($subject_answer as  $an_k => $an_answer){
                        $colum = chr($key);
                        $an_answer_val = $an_answer['answer_val'] ?? 0 ;// 答案值-数字
                        $an_is_right = $an_answer['is_right'] ?? 0 ;//是否正确答案0错误1正确

                        if(($an_answer_val & $user_answer_val) == $an_answer_val){// 用户选了此值
                            array_push($user_colum, $colum);// 用户选择
                            if($an_is_right != 1){// 题不是正确答案
                                $user_is_right = 0;
                            }
                        }else{// 用户没有选此值
                            if($an_is_right == 1){// 题是正确答案
                                $user_is_right = 0;
                            }
                        }
                        $key += 1;
                    }
                    if($user_is_right == 1) $user_score = $score ;
                    $user_answer_colum = implode(',' , $user_colum);
                }
            }
            if($user_doing){// 作答了
                $searchConditon = [
                    'exam_id' => $exam_id,// 考次id
                    'exam_staff_id' => $id,// 考次人员id
                    'paper_id' => $infoDatas['paper_id'] ?? '',// 试卷id
                    'subject_id' => $subjectInfo['subject_id'] ?? '',// 试题id
                ];
                $updateFields = [
                    'paper_history_id' => $paper_history_id,// 试卷历史id
                    'subject_history_id' => $subject_history_id,// 试题历史id
                    'answer_val' => $user_answer_val,// 答案值1,2,4,8,16
                    'answer_colum' => $user_answer_colum,// 答案值字母 A、B，判断题：对、错
                    'is_right' => $user_is_right,// 是否正确答案0错误1正确
                    'score' => $user_score,// 分值
                    'sort_num' => (array_search($subject_history_id,$subject_history_id_arr) + 1),// 排序[降序]
                ];
                // 加入操作人员信息
                self::addOprate($request, $controller, $updateFields);
                self::updateOrCreate('CompanyExamStaffSubject', $searchConditon, $updateFields, $company_id);
            }
        }

        // 交卷--并返回 win
        //exam_results 考试成绩 ;status 状态1等考试2考试中3已考试4缺考;
        //is_pass  是否及格0待考1未及格2及格  answer_end_time 结束答题时间
        if($submit_type == 4){
            // 获得答案
            $queryParams = [
                'where' => [
                    //['company_id', $company_id],
                    //['mobile', $keyword],
                ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
              //  'orderBy' => ['id'=>'desc'],
            ];// 查询条件参数
            $searchConditon = [
                'exam_id' => $exam_id,// 考次id
                'exam_staff_id' => $id,// 考次人员id
                'paper_id' => $infoDatas['paper_id'] ?? '',// 试卷id
                //'subject_id' => $currentSubjectInfo['subject_id'] ?? '',// 试题id
                'paper_history_id' => $paper_history_id,// 试卷历史id
                //'subject_history_id' => $subject_history_id,// 试题历史id
            ];
            foreach($searchConditon as $t_field => $t_val){
                array_push($queryParams['where'], [$t_field, $t_val]);
            }
            $result = CompanyExamStaffSubject::getList($request, $controller, 1 + 0, $queryParams );
            // 格式化数据
            $data_list = $result['result']['data_list'] ?? [];
            $exam_results = 0;
            foreach($data_list as $t_v){
                $exam_results += $t_v['score'];
            }
            // 获得考次详情
            $examInfo = CompanyExam::getInfoData($request, $controller, $exam_id);
            if(empty($examInfo))  throws('考次信息不存在!');
            $pass_score = $examInfo['pass_score'];
            // 保存记录
            //exam_results 考试成绩 ;status 状态1等考试2考试中3已考试4缺考;
            //is_pass  是否及格0待考1未及格2及格  answer_end_time 结束答题时间
            $saveData= [
                'exam_results' => $exam_results,// 考试成绩
                'status' => 3,// 状态1等考试2考试中3已考试4缺考;
                'is_pass' => ($exam_results >= $pass_score) ? 2 : 1,// 是否及格0待考1未及格2及格
                'answer_end_time' => date('Y-m-d H:i:s'),// 结束答题时间
            ];
            self::replaceById($request, $controller, $saveData, $id);
            return ['win' => 1];// 完成
        }

        $subject_count = count($subject_history_id_arr); // 总题数

        // 获得 redis缓存数据  ; 1:缓存读,读到则直接返回
        $cache_subject_history_id = 0;// 缓存的当前题历史id

        // 缓存
        $cachePre = 'exam_staff_subject' ;// __FUNCTION__;// 缓存前缀
        $cacheKey = '';// 缓存键[没算前缀]
        if( ($controller->cache_sel & 1) == 1){
            $paramKeyValArr = [$company_id, $exam_id, $paper_history_id, 'subject_history_id'];//[$company_id, $operate_no];// 关键参数  $request->input()
            $cacheResult =$controller->getCacheData($cachePre,$cacheKey, $paramKeyValArr,2, 1);
            if($cacheResult !== false) {
                $cache_subject_history_id =  $cacheResult;
            }
        }

        // 获得新一题的内容[第一题、上一题、下一题]
        if($submit_type == 3 || $subject_history_id <= 0) {// 新入页面
            $subject_history_id = $cache_subject_history_id; // 先用缓存的
            if($subject_history_id <= 0) {// 还没有数据，则用第一题
                $subject_history_id = $subject_history_id_arr[0] ?? 0;
                if($subject_history_id <= 0)  throws('没有试题信息!');
            }
        }else{// 上一题或下一题   $submit_type = 请求类型 1 上一题 ; 2 下一题 3 初始页面
            $current_key = array_search($subject_history_id,$subject_history_id_arr);
            if($submit_type == 1){// 1 上一题
                if($current_key <=0 )  throws('已经是第一题了!');
                $subject_history_id = $subject_history_id_arr[$current_key - 1] ;
            }else if($submit_type == 2){// ; 2 下一题
                if($current_key >= ($subject_count - 1) )  throws('已经是最后一题了!');
                $subject_history_id = $subject_history_id_arr[$current_key + 1] ;
            }else{
                throws('未知操作，请联系系统管理员!');
            }
        }
        if($subject_history_id <= 0)  throws('没有试题信息!');

        // 缓存当前试题历史id数据 180分钟
        if( ($controller->cache_sel & 2) == 2) {
            $controller->setCacheData($cachePre, $cacheKey, $subject_history_id, 180*60, 2);
        }

        // 当前试题信息
        // 当前回答问题的试题信息
        $currentSubjectInfo = $format_subject_list[$subject_history_id] ?? [];
        if(empty($currentSubjectInfo))  throws('试题[' . $subject_history_id . ']信息不存在!');
        $currentSubjectAnswer = $currentSubjectInfo['subject_answer'] ?? [];// 答案列表
        if(!is_array($currentSubjectAnswer)) $currentSubjectAnswer = [];
        $currentSubjectInfo['data_list'] = $currentSubjectAnswer;
        if(isset($currentSubjectInfo['subject_answer'])) unset($currentSubjectInfo['subject_answer']);

        $tem_subject_type = $currentSubjectInfo['subject_type'] ?? '';// 题目类型1单选；2多选；4判断
        if(empty($tem_subject_type))  throws('试题历史[' . $subject_history_id . ']题目类型有误!');

        $currentSubjectInfo['subject_num'] = array_search($subject_history_id,$subject_history_id_arr) +1 ;// 当前试题序号
        $currentSubjectInfo['subject_count'] = $subject_count;// 试题总数量

        $now_time = date('Y-m-d H:i:s');

        $currentSubjectInfo['surplus_time'] = strtotime($exam_end_time) - strtotime($now_time);// 剩余时间 毫秒
        // 如果用户已经答过的题，获取答案
        $queryParams = [
            'where' => [
                // ['company_id', $company_id],
                //['mobile', $keyword],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
            // 'orderBy' => ['id'=>'desc'],
        ];// 查询条件参数
        $searchConditon = [
            'exam_id' => $exam_id,// 考次id
            'exam_staff_id' => $id,// 考次人员id
            'paper_id' => $infoDatas['paper_id'] ?? '',// 试卷id
            'subject_id' => $currentSubjectInfo['subject_id'] ?? '',// 试题id
            'paper_history_id' => $paper_history_id,// 试卷历史id
            'subject_history_id' => $subject_history_id,// 试题历史id
        ];
        foreach($searchConditon as $t_field => $t_val){
            array_push($queryParams['where'], [$t_field, $t_val]);
        }
        $examStaffSubjectInfo = self::getInfoByQuery('CompanyExamStaffSubject', $company_id, $queryParams, '');
        $default_answer_user = ($tem_subject_type == 4 ) ? -1 : 0; // 默认值 判断时为-1,其它为0
        $answer_user = $default_answer_user; // 默认用-1 ，因为对用1,错用了0 ,所以默认不能用0
        if(!empty($examStaffSubjectInfo)){
            $answer_user = $examStaffSubjectInfo['answer_val'] ?? $default_answer_user;
        }
        $currentSubjectInfo['answer_user'] = $answer_user ; // 用户作答过的答案

        $currentSubjectInfo['now_time'] = date('Y-m-d H:i:s');// 当前服务器时间
        $currentSubjectInfo['win'] = 0 ;//是否完成考试0 正在答题 1 交卷

        return $currentSubjectInfo;
    }
    /**
     * 完成答题，答题结果页面
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function examStaffInfo(Request $request, Controller $controller, $id){

        // 获得考试结果
        // 获得考次员工表详情
        $examStaffInfo = CompanyExamStaff::getInfoData($request, $controller, $id, ['staffExam', 'examStaffSubject']);
        $staffSubject = $examStaffInfo['exam_staff_subject'] ?? [];
        if(isset($examStaffInfo['exam_staff_subject'])) unset($examStaffInfo['exam_staff_subject']);
        $answer_begin_time = $examStaffInfo['answer_begin_time'];// 答题开始时间
        $answer_end_time = $examStaffInfo['answer_end_time'];// 答题结束时间
        $answer_time = strtotime($answer_end_time) - strtotime($answer_begin_time);

        $wight_num = 0;
        foreach($staffSubject as $v){
            if($v['is_right'] == 1) $wight_num++;
        }
        $examStaffInfo['do_time'] = $answer_time;//
        $examStaffInfo['do_time_mimute'] = floor($answer_time / 60);// 分
        $examStaffInfo['do_time_second'] = ceil($answer_time % 60);// 秒
        $examStaffInfo['wight_num'] = $wight_num;// 正确数量
        $examStaffInfo['do_num'] = count($staffSubject);// 答题数量
        return $examStaffInfo;
    }
}
