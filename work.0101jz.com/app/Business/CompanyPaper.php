<?php
// 试卷
namespace App\Business;

use App\Services\Common;
use App\Services\CommonBusiness;
use App\Services\Excel\ImportExport;
use App\Services\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;

/**
 *
 */
class CompanyPaper extends BaseBusiness
{
    protected static $model_name = 'CompanyPaper';

    // 试题顺序0固定顺序1随机顺序
    public static $order_type_arr = [
        '0' => '固定顺序',
        '1' => '随机顺序',
    ];

    // subject_types 字段大分隔符
    public static $bigSplitType = '<##>';
    // subject_types 字段小分隔符
    public static $smallSplitType = '||>';

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
            $subject_order_type = Common::get($request, 'subject_order_type');
            if (is_numeric($subject_order_type) && $subject_order_type >= 0) {
                array_push($queryParams['where'], ['subject_order_type', $subject_order_type]);
            }

            $paper_name = Common::get($request, 'paper_name');
            if (!empty($paper_name)) {
                array_push($queryParams['where'], ['paper_name', 'like', '%' . $paper_name . '%']);
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
            // 添加人
            $data_list[$k]['real_name'] = $v['oprate_staff_history']['real_name'] ?? '';
            if(isset($data_list[$k]['oprate_staff_history'])) unset($data_list[$k]['oprate_staff_history']);
            // 试题
            $subject_types = $v['subject_types'] ?? '';
            $subjectTypes = [];
            $subjectTypeTextArr = [];
            if(!empty($subject_types)){
                $bigArr = explode(self::$bigSplitType, $subject_types);

                foreach($bigArr as $b_k => $small){
                    if(!empty($small)){
                        $smallArr = explode(self::$smallSplitType, $small);
                        $temArr = [
                            'type_id' => $smallArr[0] ?? 0,
                            'type_name' => $smallArr[1] ?? '',
                            'subject_count' => $smallArr[2] ?? 0,
                            'subject_score' => $smallArr[3] ?? 0,
                        ];
                        array_push($subjectTypes, $temArr);
                        $temText = $temArr['type_name'] . ':共' . $temArr['subject_count'] . '题,总分' . $temArr['subject_score'] ;
                        array_push($subjectTypeTextArr, $temText);
                    }
                }
            }
            $data_list[$k]['subjectTypes'] = $subjectTypes;
            if($isExport == 1) {// 导出
                $data_list[$k]['subjectTypeText'] = implode(PHP_EOL, $subjectTypeTextArr);
            }else{
                $data_list[$k]['subjectTypeText'] = implode('<br/>', $subjectTypeTextArr);
            }
//            // 公司名称
//            $data_list[$k]['company_name'] = $v['company_info']['company_name'] ?? '';
//            if(isset($data_list[$k]['company_info'])) unset($data_list[$k]['company_info']);
        }
        $result['data_list'] = $data_list;
        // 导出功能
        if($isExport == 1){

            $headArr = ['paper_name'=>'试卷名称', 'order_type_text'=>'试题顺序', 'subjectTypeText'=>'试题', 'subject_amount'=>'试题总数'
                , 'total_score'=>'试题总分', 'created_at'=>'添加时间', 'real_name'=>'添加人'];
            ImportExport::export('','试卷',$data_list,1, $headArr, 0, ['sheet_title' => '试卷']);
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

        // 试题
        $subject_types = $resultDatas['subject_types'] ?? '';
        $subjectTypes = [];
        $subjectTypeTextArr = [];
        if(!empty($subject_types)){
            $bigArr = explode(self::$bigSplitType, $subject_types);

            foreach($bigArr as $b_k => $small){
                if(!empty($small)){
                    $smallArr = explode(self::$smallSplitType, $small);
                    $temArr = [
                        'type_id' => $smallArr[0] ?? 0,
                        'type_name' => $smallArr[1] ?? '',
                        'subject_count' => $smallArr[2] ?? 0,
                        'subject_score' => $smallArr[3] ?? 0,
                    ];
                    array_push($subjectTypes, $temArr);
                    $temText = $temArr['type_name'] . ':共' . $temArr['subject_count'] . '题,总分' . $temArr['subject_score'] ;
                    array_push($subjectTypeTextArr, $temText);
                }
            }
        }
        $resultDatas['subjectTypes'] = $subjectTypes;
        $resultDatas['subjectTypeText'] = implode('<br/>', $subjectTypeTextArr);
        return $resultDatas;
    }

    /**
     * [开始答题用]根据id获得单条数据的试题信息 试卷使用[考试]--试卷历史
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id 试卷历史表id
     * @param int $format 是否按分类格式化试题 1不格式化 2 格式化
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getExamSubject(Request $request, Controller $controller, $id = '', $format = 1, $relations = ''){
        $subjectInfo = self::getSubjectData($request, $controller, 1, $id, $relations);
        if($format == 2){// 2 格式化
            $subject_list = $subjectInfo['subject_list'] ?? [];
            $formatSubjectList = [];
            foreach($subject_list as $v){
                $formatSubjectList['subject_type_' . $v['subject_type']][] = $v;
            }
            $subjectInfo['subject_list'] = $formatSubjectList;
        }
        return $subjectInfo;
    }

    /**
     * 根据id获得单条数据的试题信息
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $oprateNo 操作类型 1 试卷使用[考试]--试卷历史 2 试卷修改--试卷
     * @param int $id id
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function getSubjectData(Request $request, Controller $controller, $operate_no = 1, $id = '', $relations = ''){
        $company_id = $controller->company_id;
        if(empty($id)) $id = Common::get($request, 'id');// 试卷id 或 试卷历史id
        if(($operate_no & 2) == 2 ){// 试卷
            $paperInfo = self::getInfoData($request, $controller, $id, $relations);
        }else{// 试卷历史
            $paperInfo = CompanyPaperHistory::getInfoData($request, $controller, $id, $relations);
            $paperInfo['paper_history_id'] = $paperInfo['id'] ?? '';
            $paperInfo['id'] =  $paperInfo['paper_id'] ?? '';
        }
        $subjectTypes = $paperInfo['subjectTypes'] ?? [];
        $formatSubjectTypes = [];
        foreach($subjectTypes as $k => $v){
            $formatSubjectTypes[$v['type_id']] = $v;
        }
        $subject_ids = $paperInfo['subject_ids'] ?? '';//  试题id,多个逗号,分隔
        $subjectIds = explode(',', $subject_ids);

        $subject_history_ids = $paperInfo['subject_history_ids'] ?? '';//  试题历史id,多个逗号,分隔
        $subjectHistoryIds = explode(',', $subject_history_ids);

        $formatSubjectHistorys = []; // 格式化的历史试题
        $formatAnswerHistorys = [];// 格式化历史答案
        if(!empty($subject_history_ids)){
            // 获得历史试题
            $queryParams = [
                'where' => [
                    ['company_id', $company_id],
//                ['id', '>', $id],
                ],
                'whereIn' => [
                    'id' => $subjectHistoryIds,
                ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
                //'orderBy' => ['id'=>'asc'],
                //'limit' => $limit,
                //'offset' => $offset,
                // 'count'=>'0'
            ];
            $temRelations = ['answerType'];
            $resultList = CompanySubjectHistory::getList($request, $controller, 1 + 0, $queryParams, $temRelations, ['useQueryParams' => false],0);
            $subjectHistorys = $resultList['result']['data_list'] ?? [];
            $answerHistoryIdsStr = [];
            foreach($subjectHistorys as $k => $v){
                $temAnswerHistoryId = $v['answer_history_ids'] ?? '';
                if(!empty($temAnswerHistoryId)) array_push($answerHistoryIdsStr,$temAnswerHistoryId);
                $formatSubjectHistorys[$v['id']] = $v;
            }
            if(!empty($answerHistoryIdsStr)){// 获得答案
                $answerHistoryIds = explode(',', implode(',', $answerHistoryIdsStr));
                // 获得历史试题
                $queryParams = [
                    'where' => [
                        ['company_id', $company_id],
//                ['id', '>', $id],
                    ],
                    'whereIn' => [
                        'id' => $answerHistoryIds,
                    ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
                    //'orderBy' => ['id'=>'asc'],
                    //'limit' => $limit,
                    //'offset' => $offset,
                    // 'count'=>'0'
                ];
                $temAnswerHistoryRelations = '';
                // if(($operate_no & 2) == 2 ) $temAnswerHistoryRelations = ['historyAnswer', 'answerSubject'];
                $resultList = CompanySubjectAnswerHistory::getList($request, $controller, 1 + 0, $queryParams, $temAnswerHistoryRelations, ['useQueryParams' => false]);
                $answerHistorys = $resultList['result']['data_list'] ?? [];
                foreach($answerHistorys as $answer_k => $answer_v){
                    $formatAnswerHistorys[$answer_v['id']] = $answer_v;
                }
            }

        }
        $subjectArr = [];
        // 历史试题
        $serialNum = 1;
        foreach($subjectHistoryIds as $temSubjectHistoryId){
            $historySubject = $formatSubjectHistorys[$temSubjectHistoryId] ?? [];
            $answer_history_ids = $historySubject['answer_history_ids'] ?? '';
            $historySubject['subject_history_id'] = $temSubjectHistoryId;
            // 试题答案
            $temHistoryAnswer = [];
            if(!empty($answer_history_ids)){
                $historyAnswerIds = explode(',', $answer_history_ids);
                foreach($historyAnswerIds as $historyAnswerId){
                    $historyAnswer =$formatAnswerHistorys[$historyAnswerId] ?? [];
                    $historyAnswer['id'] = $historyAnswer['answer_id'] ?? '';
                    if(empty($historyAnswer)) continue;
                    $historyAnswer['answer_history_id'] = $historyAnswerId;
                    array_push($temHistoryAnswer, $historyAnswer);
                }
            }
            $historySubject['id'] = $historySubject['subject_id'] ?? '';
            // if(!empty($temHistoryAnswer)) $temHistoryAnswer = Tool::php_multisort($temHistoryAnswer, CompanySubject::$orderKeys);
            $historySubject['subject_answer'] = $temHistoryAnswer;
            CompanySubject::formatAnswer($request, $controller, $historySubject, 0);
            // 当前题的分数
            $temSubjectType = $historySubject['subject_type'] ?? '';
            $subject_count = $formatSubjectTypes[$temSubjectType]['subject_count'] ?? 0;// 题数量
            $subject_score = $formatSubjectTypes[$temSubjectType]['subject_score'] ?? 0;// 题分数
            $subject_type_name = $formatSubjectTypes[$temSubjectType]['type_name'] ?? 0;// 题类型
            $score = 0;
            if($subject_count > 0) $score = round($subject_score / $subject_count,3);
            $historySubject['score'] = $score;
            $historySubject['subject_type_name'] = $subject_type_name;
            $historySubject['serial_num'] = $serialNum;
            array_push($subjectArr, $historySubject);
            $serialNum++;
        }

        // 获得最新的试题及答案
        if(($operate_no & 2) == 2 && !empty($subject_ids)){
            // 获得试题及答案
            $queryParams = [
                'where' => [
                    ['company_id', $company_id],
//                ['id', '>', $id],
                ],
                'whereIn' => [
                    'id' => $subjectIds,
                ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_history_id'
//                ,'created_at'
//            ],
//            'orderBy' => ['sort_num'=>'desc','id'=>'desc'],
                //'orderBy' => ['id'=>'asc'],
                //'limit' => $limit,
                //'offset' => $offset,
                // 'count'=>'0'
            ];
            $temRelations = ['subjectAnswer'];
            $resultList = CompanySubject::getList($request, $controller, 1 + 0, $queryParams, $temRelations, ['useQueryParams' => false]);
            $subject = $resultList['result']['data_list'] ?? [];
            $formatSubject = [];// 格式化试题
            foreach($subject as $v){
                $subject_answer = $v['subject_answer'] ?? [];// 答案
                $v['answer_version'] = array_column($subject_answer, 'version_num');
                $formatSubject[$v['id']] = $v;
            }
            // 比较试题及答案
            foreach($subjectArr as $k => $v){
                $temSubjectId = $v['subject_id'] ?? '';
                $temNowSubject = $formatSubject[$temSubjectId] ?? [];
                if(empty($temNowSubject)) {// 最新的试题 0没有变化 ;1 已经删除  2 试题不同  4 答案不同
                    $subjectArr[$k]['now_subject'] = 1;
                    continue;
                }
                if($v['version_num'] != $temNowSubject['version_num']){
                    $subjectArr[$k]['now_subject'] = 2;
                    continue;
                }
                // 判断答案版本号

                $historyAnswerVersion = array_column($v['subject_answer'],'version_num');
                $nowAnswerVersions = $temNowSubject['answer_version'];
                if( !empty(array_diff_assoc($historyAnswerVersion, $nowAnswerVersions)) || !empty(array_diff_assoc($nowAnswerVersions, $historyAnswerVersion)) ){
                    $subjectArr[$k]['now_subject'] = 4;
                    continue;
                }
                // 相同
                $subjectArr[$k]['now_subject'] = 0;

            }
        }

        $paperInfo['subject_list'] = $subjectArr;

        return $paperInfo;
    }

    /**
     * 根据id获得单条数据的试题信息
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param int $id id  试题 id
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function updateSubjectData(Request $request, Controller $controller, $id = '', $relations = '')
    {
        $company_id = $controller->company_id;
        if (empty($id)) $id = Common::getInt($request, 'id');// 试卷id
        $relations = ['subjectAnswer', 'answerType'];
        $infoDatas = CompanySubject::getInfoData($request, $controller, $id, $relations);
        // 获得试题历史id
        $subject_history_id = self::getHistoryId($request, $controller, 'CompanySubject', $id
            , 'CompanySubjectHistory', 'company_subject_history', ['company_id' => $company_id,'subject_id' => $id], []
            , $company_id, 0);
        $infoDatas['subject_id'] = $infoDatas['id'] ?? 0;
        $infoDatas['subject_history_id'] = $subject_history_id ;
        $infoDatas['now_subject'] = 0;// 最新的试题 0没有变化 ;1 已经删除  2 试题不同  4 答案不同

        CompanySubject::formatAnswer($request, $controller, $infoDatas, 0);
        return $infoDatas;

    }

    /**
     * 根据id获得单条数据的试题信息
     *
     * @param Request $request 请求信息
     * @param Controller $controller 控制对象
     * @param string $id id  试题 id,多个,号分隔
     * @param mixed $relations 关系
     * @return  array 单条数据 - -维数组
     * @author zouyan(305463219@qq.com)
     */
    public static function addSubjectData(Request $request, Controller $controller, $id = '', $relations = '')
    {
        $company_id = $controller->company_id;
        if (empty($id)) $id = Common::get($request, 'id');// 试卷id

        // 参数
        $requestData = [
            'company_id' => $company_id,
            'staff_id' =>  $controller->user_id,
            'ids' =>  $id,// explode(',' , $id),
            'relations' => $relations,
        ];
        $url = config('public.apiUrl') . config('apiUrl.apiPath.getSubjectByIds');
        // 生成带参数的测试get请求
        // $requestTesUrl = splicQuestAPI($url , $requestData);
        $subjectList = HttpRequest::HttpRequestApi($url, $requestData, [], 'POST');
        foreach($subjectList as $k => $v){
            CompanySubject::formatAnswer($request, $controller, $subjectList[$k], 0);
        }
        return $subjectList;

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
}
